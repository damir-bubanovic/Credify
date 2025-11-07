<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\Tenant\BillingController as TB;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\CreditController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
| Tenant routes
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

    // IMPORTANT:
    // Do NOT include central auth routes here.
    // They define /login, /register, etc. and would override central /login
    // and then PreventAccessFromCentralDomains would 404 on credify.localhost.
    //
    // So this stays commented:
    //
    // require base_path('routes/auth.php');
});

# Protected tenant routes (require tenant tenancy + auth)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth',
    'verified',
    'tenant.subscribed', // tenant-level subscription check
])->group(function () {
    // Use a distinct route NAME to avoid clashing with central "dashboard"
    // Path /dashboard remains, which is fine.
    Route::get('/dashboard', DashboardController::class)->name('tenant.dashboard');

    // campaigns
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('tenant.campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('tenant.campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('tenant.campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('tenant.campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('tenant.campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('tenant.campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('tenant.campaigns.destroy');

    // credits
    Route::get('/credits', [CreditController::class, 'index'])->name('tenant.credits.index');
    Route::post('/credits/settings', [CreditController::class, 'updateSettings'])->name('tenant.credits.settings');
});
