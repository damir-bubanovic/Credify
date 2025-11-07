<?php

use App\Http\Controllers\Admin\DashboardController as AdminDash;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Stancl\Tenancy\Middleware\PreventAccessFromTenantDomains;

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

# Admin area (central only)
Route::middleware(['auth', 'role:admin', PreventAccessFromTenantDomains::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard (invokable)
        Route::get('/', AdminDash::class)->name('dashboard');

        // Billing (central)
        Route::get('/billing', [BillingController::class, 'show'])->name('billing.show');
        Route::post('/billing/checkout/{price}', [BillingController::class, 'checkout'])->name('billing.checkout');
        Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');

        // Tenants management
        Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');
        Route::patch('/tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
        Route::patch('/tenants/{tenant}/activate', [TenantController::class, 'activate'])->name('tenants.activate');
        Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');
        Route::patch('/tenants/{id}/restore', [TenantController::class, 'restore'])->name('tenants.restore');
    });

# Stripe webhook (CSRF-exempt in bootstrap/app.php)
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('stripe.webhook');

require __DIR__ . '/auth.php';

# -------------------------------------------------------------------
# Local-only utility: recreate a tenant without Artisan
# -------------------------------------------------------------------
if (app()->environment('local')) {
    Route::get('/__dev/recreate/{id}/{domain}', function (string $id, string $domain) {
        try {
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

            $t = \App\Models\Tenant::create(['id'=>$id]);
            $t->domains()->firstOrCreate(['domain'=>$domain]);

            $db = $t->database()->getName();
            DB::statement('CREATE DATABASE IF NOT EXISTS `'.str_replace('`','``',$db).'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

            tenancy()->initialize($t);
            $migrator = app('migrator');
            $path = database_path('migrations/tenant');

            if (! $migrator->repositoryExists()) {
                $migrator->getRepository()->createRepository();
            }
            $migrator->run([$path]);
            tenancy()->end();

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
                'at' => class_basename($e),
            ], 500);
        }
    });
}
