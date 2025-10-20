<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillingController;
use Laravel\Cashier\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::view('/admin', 'admin.dashboard');
    Route::get('/billing', [BillingController::class,'show'])->name('billing.show');
    Route::post('/billing/checkout/{price}', [BillingController::class,'checkout'])->name('billing.checkout');
    Route::get('/billing/portal', [BillingController::class,'portal'])->name('billing.portal');
});

Route::post('/stripe/webhook', [WebhookController::class,'handleWebhook'])->name('stripe.webhook');

require __DIR__.'/auth.php';
