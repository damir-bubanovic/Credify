<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $cards = [
            'tenants'     => Tenant::count(),
            'subscribed'  => Tenant::all()->filter->subscribed('default')->count(),
            'mrr_eur'     => 0, // optional: fill from Stripe later
            'credits_sum' => Tenant::sum('credit_balance'),
        ];

        $recent = Tenant::latest()->take(10)->get(['id','created_at','credit_balance','plan']);

        $ledger = DB::table('credit_transactions')
            ->select('tenant_id','type','amount','reason','created_at')
            ->orderByDesc('created_at')->limit(20)->get();

        return view('admin.dashboard', compact('cards','recent','ledger'));
    }
}