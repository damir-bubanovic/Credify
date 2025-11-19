<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Services\CreditService;
use App\Http\Controllers\CampaignTrackingWebhookController;

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'tenant.api-key',
])->group(function () {

    // Credit-usage webhook (already existing)
    Route::post('/v1/run', function (Request $request, CreditService $credits) {
        // capture the tenant while tenancy is initialized
        $tenant = tenant();

        $cost = (int) config('credits.costs.api.request', 1);

        $meta = [
            'ip'   => $request->ip(),
            'ua'   => substr((string) $request->userAgent(), 0, 255),
            'path' => $request->path(),
        ];

        $key = $request->header('Idempotency-Key') ?: Str::uuid()->toString();

        // call service directly (it uses the central connection internally)
        $ok = $credits->spend($tenant, $cost, 'api.request', $meta, $key);

        if (! $ok) {
            return response()->json(['error' => 'insufficient_credits'], 402);
        }

        return response()->json(['ok' => true, 'tenant' => (string) $tenant->id]);
    });

    // Campaign tracking webhook
    Route::post('/v1/campaigns/{campaign}/events', CampaignTrackingWebhookController::class)
        ->name('api.campaigns.events');
});
