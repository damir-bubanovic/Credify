<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function show(Request $request)
    {
        $tenant = Tenant::first(); // later: resolve current admin's tenant
        return view('billing.show', compact('tenant'));
    }

    public function checkout(Request $request, string $price)
    {
        $tenant = Tenant::first(); // resolve your active tenant selection
        $priceId = match ($price) {
            'basic' => config('services.stripe.price_basic', env('PRICE_BASIC')),
            'pro'   => config('services.stripe.price_pro', env('PRICE_PRO')),
            default => abort(404),
        };

        return $tenant->newSubscription('default', $priceId)->checkout([
            'success_url' => route('billing.show') . '?success=1',
            'cancel_url'  => route('billing.show') . '?canceled=1',
        ]);
    }

    public function portal(Request $request)
    {
        $tenant = Tenant::first();
        return $tenant->redirectToBillingPortal(route('billing.show'));
    }
}
