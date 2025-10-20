<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            'state' => $state,
            'priceBasic' => env('PRICE_BASIC'),
            'pricePro'   => env('PRICE_PRO'),
        ]);
    }

    public function checkout(string $price)
    {
        $tenantId = tenant('id');

        $url = tenancy()->central(function () use ($tenantId, $price) {
            $t = \App\Models\Tenant::findOrFail($tenantId);

            // ensure Stripe customer exists
            if (! $t->hasStripeId()) $t->createOrGetStripeCustomer();

            return $t->newSubscription('default', [$price])
                ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('tenant.billing.success'),
                    'cancel_url'  => route('tenant.billing.show'),
                ])->url;
        });

        return redirect()->away($url);
    }

    public function success()
    {
        return redirect()->route('tenant.dashboard')->with('status', 'Subscription active.');
    }

    public function portal()
    {
        $tenantId = tenant('id');

        $url = tenancy()->central(function () use ($tenantId) {
            $t = \App\Models\Tenant::findOrFail($tenantId);
            if (! $t->hasStripeId()) $t->createOrGetStripeCustomer();
            return $t->redirectToBillingPortal(route('tenant.billing.show'))->getTargetUrl();
        });

        return redirect()->away($url);
    }
}
