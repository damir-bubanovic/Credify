<?php

use App\Http\Controllers\Admin\DashboardController as AdminDash;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;

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
