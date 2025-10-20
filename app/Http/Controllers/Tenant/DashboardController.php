<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Tenant;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totals = [
            'campaigns' => Campaign::count(),
            'active'    => Campaign::where('status', 'active')->count(),
        ];

        // capture tenant id before switching to central context
        $tenantId = tenant('id');

        // central credit balance
        $credit = tenancy()->central(function () use ($tenantId) {
            return Tenant::find($tenantId)?->credit_balance ?? 0;
        });

        // last 30 days campaign creations
        $daily = Campaign::selectRaw('DATE(created_at) d, COUNT(*) c')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('d')->orderBy('d')->pluck('c', 'd');

        return view('tenant.dashboard', compact('totals', 'daily', 'credit'));
    }
}
