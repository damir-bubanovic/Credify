<?php

namespace App\Http\Middleware;

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
        // Allow onboarding routes themselves to pass through always
        if ($request->routeIs(
            'tenant.onboarding',
            'tenant.onboarding.business',
            'tenant.onboarding.complete',
        )) {
            return $next($request);
        }

        $tenant = tenant();

        if (! $tenant) {
            return $next($request);
        }

        // Check dedicated onboarding_completed_at column
        if (empty($tenant->onboarding_completed_at)) {
            return redirect()->route('tenant.onboarding');
        }

        return $next($request);
    }
}
