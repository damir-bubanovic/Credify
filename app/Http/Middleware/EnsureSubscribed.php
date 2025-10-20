<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = tenant('id');

        $ok = tenancy()->central(function () use ($tenantId) {
            $t = \App\Models\Tenant::find($tenantId);
            return $t && $t->subscribed('default');
        });

        if (! $ok) {
            return redirect()->route('tenant.billing.show')
                ->with('status', 'Subscription required.');
        }

        return $next($request);
    }
}
