<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\Tenant\DashboardController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
| Tenant routes
| Public (auth pages, whoami) + Protected (app)
*/

# Public tenant routes (no auth)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/whoami', fn () => tenant('id') ?? 'no-tenant');
    require base_path('routes/auth.php'); // tenant login/register/password routes
});

# Protected tenant routes
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth',
    'verified',
    'subscribed',
])->group(function () {
    // dashboard name MUST be "dashboard" for auth redirects
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // campaigns
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('tenant.campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('tenant.campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('tenant.campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('tenant.campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('tenant.campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('tenant.campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('tenant.campaigns.destroy');
});
