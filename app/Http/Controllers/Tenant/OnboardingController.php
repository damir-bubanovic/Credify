<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function show()
    {
        // Just render the Vue onboarding view
        return view('tenant.onboarding');
    }

    public function saveBusiness(Request $request)
    {
        $data = $request->validate([
            'business_name'  => ['required', 'string', 'max:255'],
            'website_url'    => ['nullable', 'string', 'max:255'],
            'industry'       => ['nullable', 'string', 'max:255'],
            'contact_name'   => ['nullable', 'string', 'max:255'],
            'contact_email'  => ['nullable', 'email', 'max:255'],
        ]);

        $tenantId = (string) tenant('id');

        tenancy()->central(function () use ($tenantId, $data) {
            $tenant = Tenant::findOrFail($tenantId);

            $payload = $tenant->data ?? [];

            $payload['business_name'] = $data['business_name'];
            $payload['website_url']   = $data['website_url'] ?? null;
            $payload['industry']      = $data['industry'] ?? null;
            $payload['contact_name']  = $data['contact_name'] ?? null;
            $payload['contact_email'] = $data['contact_email'] ?? null;

            $tenant->data = $payload;
            $tenant->save();
        });

        return response()->json([
            'status'  => 'ok',
            'message' => 'Business details saved.',
        ]);
    }

    public function complete()
    {
        $tenantId = (string) tenant('id');

        tenancy()->central(function () use ($tenantId) {
            $tenant = Tenant::findOrFail($tenantId);

            $payload = $tenant->data ?? [];
            $payload['onboarding_completed_at'] = now();

            $tenant->data = $payload;
            $tenant->save();
        });

        return response()->json([
            'status'   => 'ok',
            'redirect' => route('tenant.dashboard'),
        ]);
    }
}
