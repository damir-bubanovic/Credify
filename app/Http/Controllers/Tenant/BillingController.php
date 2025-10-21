<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;

class BillingController extends Controller
{
    public function show()
    {
        $tenantId = tenant('id');

        $state = tenancy()->central(function () use ($tenantId) {
            $t = \App\Models\Tenant::find($tenantId);
            return [
                'subscribed' => $t?->subscribed('default') ?? false,
                'plan' => $t?->plan,
            ];
        });

        return view('tenant.billing.show', [
            'state'      => $state,
            'priceBasic' => env('PRICE_BASIC'),
            'pricePro'   => env('PRICE_PRO'),
        ]);
    }

    public function checkout(string $price)
    {
        $tenantId   = tenant('id');
        // Build absolute tenant URLs *before* switching to central
        $successUrl = route('tenant.billing.success');
        $cancelUrl  = route('tenant.billing.show');

        $url = tenancy()->central(function () use ($tenantId, $price, $successUrl, $cancelUrl) {
            $t = \App\Models\Tenant::findOrFail($tenantId);
            if (! $t->hasStripeId()) $t->createOrGetStripeCustomer();

            return $t->newSubscription('default', [$price])
                ->allowPromotionCodes()
                ->checkout([
                    'success_url' => $successUrl,
                    'cancel_url'  => $cancelUrl,
                ])->url;
        });

        return redirect()->away($url);
    }

    public function success()
    {
        // Correct route name is 'dashboard'
        return redirect()->route('dashboard')->with('status', 'Subscription active.');
    }

    public function portal()
    {
        $tenantId  = tenant('id');
        $returnUrl = route('tenant.billing.show'); // build in tenant context

        $url = tenancy()->central(function () use ($tenantId, $returnUrl) {
            $t = \App\Models\Tenant::findOrFail($tenantId);
            if (! $t->hasStripeId()) $t->createOrGetStripeCustomer();
            return $t->redirectToBillingPortal($returnUrl)->getTargetUrl();
        });

        return redirect()->away($url);
    }
}
