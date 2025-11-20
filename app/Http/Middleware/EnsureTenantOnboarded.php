<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantOnboarded
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow onboarding routes themselves to pass through
        if ($request->routeIs('tenant.onboarding', 'tenant.onboarding.business', 'tenant.onboarding.complete')) {
            return $next($request);
        }

        $tenantId = tenant('id');

        if (! $tenantId) {
            // If somehow we don't have a tenant, just continue (or you could abort/redirect)
            return $next($request);
        }

        $isCompleted = false;

        tenancy()->central(function () use ($tenantId, &$isCompleted) {
            /** @var Tenant|null $tenant */
            $tenant = Tenant::find($tenantId);

            if (! $tenant) {
                return;
            }

            $data = $tenant->data ?? [];

            $isCompleted = ! empty($data['onboarding_completed_at']);
        });

        if (! $isCompleted) {
            return redirect()->route('tenant.onboarding');
        }

        return $next($request);
    }
}
