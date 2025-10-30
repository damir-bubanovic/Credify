<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class BillingController extends Controller
{

    public function show()
    {
        $tenantId = tenant('id');

        $state = tenancy()->central(function () use ($tenantId) {
            $subscribed = \DB::table('subscriptions')
                ->where('user_id', (string) $tenantId)
                ->where('name', 'default')
                ->whereIn('stripe_status', ['active', 'trialing', 'past_due'])
                ->whereNull('ends_at')
                ->exists();

            $plan = \DB::table('subscriptions')
                ->where('user_id', (string) $tenantId)
                ->where('name', 'default')
                ->orderByDesc('created_at')
                ->value('stripe_price');

            return [
                'subscribed' => $subscribed,
                'plan'       => $plan,
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
        $successUrl = route('tenant.billing.success') . '?session_id={CHECKOUT_SESSION_ID}';
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
        $tenantId   = tenant('id');
        $sessionId  = request('session_id');

        if (! $sessionId) {
            return redirect()->route('tenant.billing.show')
                ->with('status', 'Missing session id. If you already paid, refresh.');
        }

        tenancy()->central(function () use ($tenantId, $sessionId) {
            \Stripe\Stripe::setApiKey(config('cashier.secret'));

            $session     = \Stripe\Checkout\Session::retrieve($sessionId);
            $subscriptionId = $session->subscription ?? null;
            if (! $subscriptionId) {
                return;
            }

            $stripeSub = \Stripe\Subscription::retrieve($subscriptionId);
            $item      = $stripeSub->items->data[0] ?? null;
            $priceId   = $item?->price?->id;
            $qty       = $item?->quantity ?? 1;
            $status    = $stripeSub->status; // e.g. active, trialing, past_due...
            $trialEnd  = $stripeSub->trial_end ? Carbon::createFromTimestamp($stripeSub->trial_end) : null;

            /** @var \App\Models\Tenant $t */
            $t = \App\Models\Tenant::findOrFail($tenantId);

            // Subscriptions table uses user_id (string) for the billable key.
            // Upsert on stripe_id; set name='default' to match your gate.
            \DB::table('subscriptions')->updateOrInsert(
                ['user_id' => (string) $t->getKey(), 'name' => 'default'], // conflict key
                [
                    'stripe_id'     => $subscriptionId,
                    'stripe_status' => $status,
                    'stripe_price'  => $priceId,
                    'quantity'      => $qty,
                    'trial_ends_at' => $trialEnd,
                    'ends_at'       => null,
                    'updated_at'    => now(),
                    'created_at'    => now(),
                ]
            );
        });

        // now the tenant should pass the gate
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
