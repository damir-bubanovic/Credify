<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\Tenant\ApiKeyController;
use App\Http\Controllers\Tenant\BillingController as TB;
use App\Http\Controllers\Tenant\CreditController;
use App\Http\Controllers\Tenant\DashboardController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant routes
|--------------------------------------------------------------------------
| Public (whoami, billing) + Protected (app)
*/

# Public tenant routes (no auth required)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/whoami', fn () => tenant('id') ?? 'no-tenant');

    // Billing (checkout/portal can be hit pre-login)
    Route::get('/billing', [TB::class, 'show'])->name('tenant.billing.show');
    Route::get('/billing/success', [TB::class, 'success'])->name('tenant.billing.success');
    Route::post('/billing/checkout/{price}', [TB::class, 'checkout'])->name('tenant.billing.checkout');
    Route::get('/billing/portal', [TB::class, 'portal'])->name('tenant.billing.portal');
});

# Protected tenant APP routes (require tenancy + auth + subscription)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth',
    'verified',
    'tenant.subscribed', // tenant-level subscription check
])->group(function () {
    // Tenant dashboard
    Route::get('/dashboard', DashboardController::class)->name('tenant.dashboard');

    // Campaigns
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('tenant.campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('tenant.campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('tenant.campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('tenant.campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('tenant.campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('tenant.campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('tenant.campaigns.destroy');

    // Credits
    Route::get('/credits', [CreditController::class, 'index'])->name('tenant.credits.index');
    Route::post('/credits/settings', [CreditController::class, 'updateSettings'])->name('tenant.credits.settings');
});


# Tenant API key management (admin only on tenant domain)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'role:admin',
])->group(function () {
    Route::get('/api-keys', [ApiKeyController::class, 'index'])->name('tenant.api-keys.index');
    Route::post('/api-keys', [ApiKeyController::class, 'store'])->name('tenant.api-keys.store');
    Route::delete('/api-keys/{apiKey}', [ApiKeyController::class, 'destroy'])->name('tenant.api-keys.destroy');
});