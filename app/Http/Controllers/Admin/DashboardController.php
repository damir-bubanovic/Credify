<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $cards = [
            'tenants'     => Tenant::count(),
            // Cashier uses "stripe_status"
            'subscribed'  => DB::table('subscriptions')
                ->where('name', 'default')
                ->whereIn('stripe_status', ['active', 'trialing'])
                ->distinct('user_id')
                ->count('user_id'),
            'mrr_eur'     => 0, // fill from Stripe later if needed
            'credits_sum' => (int) Tenant::sum('credit_balance'),
        ];

        $recent = Tenant::with('domains:id,tenant_id,domain')
            ->latest()
            ->take(10)
            ->get(['id', 'created_at', 'credit_balance', 'plan']);

        // Use credit_ledgers, which CreditService writes to
        $ledger = DB::table('credit_ledgers')
            ->select('tenant_id', 'delta', 'balance_after', 'reason', 'created_at')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('admin.dashboard', compact('cards', 'recent', 'ledger'));
    }
}
