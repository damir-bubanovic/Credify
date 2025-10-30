<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $name = 'default')
    {
        $tenantId = tenant('id');
        if (! $tenantId) {
            return redirect()->to('/'); // not in tenant context
        }

        $ok = tenancy()->central(function () use ($tenantId, $name) {
            $t = \App\Models\Tenant::find($tenantId);
            return $t?->subscribed($name) === true;
        });

        if (! $ok) {
            return redirect()->route('tenant.billing.show');
        }

        return $next($request);
    }

}
