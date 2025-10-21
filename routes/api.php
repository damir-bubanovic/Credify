<?php

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Services\CreditService;

// Tenant API routes (domain-based). No sessions, no CSRF.
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    // add auth layer you use for APIs; examples:
    // 'auth:sanctum',
    // or a custom 'tenant.api' middleware that validates X-API-Key
    'subscribed', // optional if you want paid-only API
])->group(function () {
    Route::post('/v1/run', function (Request $request, CreditService $credits) {
        $cost = (int) config('credits.costs.api.request', 1);

        $meta = [
            'ip'   => $request->ip(),
            'ua'   => substr((string) $request->userAgent(), 0, 255),
            'path' => $request->path(),
        ];

        $key = $request->header('Idempotency-Key') ?: Str::uuid()->toString();

        $ok = tenancy()->central(function () use ($credits, $cost, $meta, $key) {
            return $credits->spend(tenant(), $cost, 'api.request', $meta, $key);
        });

        if (! $ok) {
            return response()->json(['error' => 'insufficient_credits'], 402);
        }

        // do actual work here
        return response()->json(['ok' => true, 'tenant' => tenant('id')]);
    });
});
