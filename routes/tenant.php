<?php

use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
| These routes run under the tenant context using domain identification.
| Public routes (e.g., whoami, register, login) are outside auth middleware.
| Campaign routes require auth + verified + subscribed.
|--------------------------------------------------------------------------
*/

// Public tenant routes (no auth)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/whoami', fn () => tenant('id') ?? 'no-tenant');

    // Enable authentication pages for tenant users
    require base_path('routes/auth.php');
});

// Protected tenant routes (require login)
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth',
    'verified',
    'subscribed',
])->group(function () {
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('tenant.campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('tenant.campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('tenant.campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('tenant.campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('tenant.campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('tenant.campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('tenant.campaigns.destroy');
});
