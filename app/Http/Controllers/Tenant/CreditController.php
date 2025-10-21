<?php

// app/Http/Controllers/Tenant/CreditController.php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\CreditBalance;
use App\Models\CreditLedger;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index()
    {
        $tenantId = tenant('id');
        return view('tenant.credits.index', [
            'balance' => CreditBalance::firstOrCreate(['tenant_id'=>$tenantId]),
            'ledger'  => CreditLedger::where('tenant_id',$tenantId)->latest()->paginate(20),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'low_threshold' => 'required|integer|min:0',
            'auto_topup_enabled' => 'sometimes|boolean',
            'topup_amount' => 'nullable|integer|min:0',
            'stripe_price_id' => 'nullable|string',
        ]);
        CreditBalance::updateOrCreate(
            ['tenant_id'=>tenant('id')],
            $data + []
        );
        return back()->with('status','Updated.');
    }
}
