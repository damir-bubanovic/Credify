<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\CreditBalance;
use App\Models\CreditLedger;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        $tenantId = (string) tenant('id');

        $state = tenancy()->central(function () use ($tenantId) {
            $balance = CreditBalance::firstOrCreate(
                ['tenant_id' => $tenantId],
                [
                    'balance'       => (int) config('credits.starting_balance', 100),
                    'low_threshold' => (int) config('credits.low_threshold', 10),
                ]
            );

            $ledger = CreditLedger::where('tenant_id', $tenantId)
                ->latest()
                ->paginate(20);

            return compact('balance', 'ledger');
        });

        return view('tenant.credits.index', $state);
    }

    public function updateSettings(Request $request)
    {
        $tenantId = (string) tenant('id');

        $data = $request->validate([
            'low_threshold'       => 'required|integer|min:0',
            'auto_topup_enabled'  => 'sometimes|boolean',
            'topup_amount'        => 'nullable|integer|min:0',
            'stripe_price_id'     => 'nullable|string',
        ]);

        // Normalize unchecked checkbox
        $data['auto_topup_enabled'] = (bool) ($data['auto_topup_enabled'] ?? false);

        tenancy()->central(function () use ($tenantId, $data) {
            CreditBalance::updateOrCreate(
                ['tenant_id' => $tenantId],
                [
                    'low_threshold'      => $data['low_threshold'],
                    'auto_topup_enabled' => $data['auto_topup_enabled'],
                    'topup_amount'       => $data['topup_amount'] ?? null,
                    'stripe_price_id'    => $data['stripe_price_id'] ?? null,
                ]
            );
        });

        return back()->with('status', 'Updated');
    }
}
