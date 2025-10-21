<?php

use App\Http\Controllers\Admin\DashboardController as AdminDash;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

Route::get('/', fn () => view('welcome'));

# Central dashboard (optional sample page)
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

# Central authenticated user profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

# Admin area
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', AdminDash::class)->name('admin.dashboard'); // use controller only
    Route::get('/billing', [BillingController::class, 'show'])->name('billing.show');
    Route::post('/billing/checkout/{price}', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
});

# Stripe webhook (CSRF-exempt in bootstrap/app.php)
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('stripe.webhook');

require __DIR__ . '/auth.php';

# -------------------------------------------------------------------
# Local-only route to recreate a tenant safely (no Artisan, no console output)
# -------------------------------------------------------------------
if (app()->environment('local')) {
    Route::get('/__dev/recreate/{id}/{domain}', function (string $id, string $domain) {
        try {
            // drop existing tenant + DB or leftover DB
            if ($t = \App\Models\Tenant::find($id)) {
                $db = $t->database()->getName();
                \Stancl\Tenancy\Database\Models\Domain::where('tenant_id', $t->id)->delete();
                $t->delete();
                if ($db && !in_array($db, ['mysql','information_schema','performance_schema','sys'], true)) {
                    DB::statement('DROP DATABASE IF EXISTS `'.str_replace('`','``',$db).'`');
                }
            } else {
                $tmp = new \App\Models\Tenant(['id'=>$id]);
                $db  = $tmp->database()->getName();
                if ($db && !in_array($db, ['mysql','information_schema','performance_schema','sys'], true)) {
                    DB::statement('DROP DATABASE IF EXISTS `'.str_replace('`','``',$db).'`');
                }
            }

            // fresh tenant + domain
            $t = \App\Models\Tenant::create(['id'=>$id]);
            $t->domains()->firstOrCreate(['domain'=>$domain]);

            // ensure DB exists
            $db = $t->database()->getName();
            DB::statement('CREATE DATABASE IF NOT EXISTS `'.str_replace('`','``',$db).'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

            // run tenant migrations WITHOUT Artisan
            tenancy()->initialize($t);
            $migrator = app('migrator');
            $path = database_path('migrations/tenant');

            if (! $migrator->repositoryExists()) {
                $migrator->getRepository()->createRepository();
            }
            $migrator->run([$path]); // runs pending tenant migrations
            tenancy()->end();

            // seed starting credits (central)
            tenancy()->central(function () use ($t) {
                app(\App\Services\CreditService::class)->add(
                    $t,
                    (int) config('credits.starting_balance', 100),
                    'initial.credits'
                );
            });

            return response()->json(['ok' => true, 'tenant' => $id, 'db' => $db]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'error' => $e->getMessage(),
                'at' => class_basename($e)
            ], 500);
        }
    });
}
