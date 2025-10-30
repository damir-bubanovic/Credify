<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnsureTenantIsSubscribed
{
    public function handle(Request $request, Closure $next, string $name = 'default')
    {
        $tenantId = tenant('id');
        if (! $tenantId) {
            return redirect()->to('/');
        }

        $ok = tenancy()->central(function () use ($tenantId, $name) {
            return DB::table('subscriptions')
                ->where('user_id', (string) $tenantId)
                ->where('name', $name)
                ->whereIn('stripe_status', ['active', 'trialing', 'past_due'])
                ->whereNull('ends_at')
                ->exists();
        });

        if (! $ok) {
            return redirect()->route('tenant.billing.show');
        }

        return $next($request);
    }
}
