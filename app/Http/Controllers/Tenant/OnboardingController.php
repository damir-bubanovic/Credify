<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function show()
    {
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

        $tenant = tenant();

        if (! $tenant) {
            abort(500, 'Tenant not found.');
        }

        // Load existing data as array (handles both JSON string and casted array)
        $payload = $tenant->data ?? [];

        if (is_string($payload)) {
            $decoded = json_decode($payload, true);
            $payload = is_array($decoded) ? $decoded : [];
        } elseif (! is_array($payload)) {
            $payload = [];
        }

        // Merge new fields
        $payload['business_name'] = $data['business_name'];
        $payload['website_url']   = $data['website_url'] ?? null;
        $payload['industry']      = $data['industry'] ?? null;
        $payload['contact_name']  = $data['contact_name'] ?? null;
        $payload['contact_email'] = $data['contact_email'] ?? null;

        // Save back into data JSON
        $tenant->data = $payload;
        $tenant->save();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Business details saved.',
        ]);
    }

    public function complete()
    {
        $tenant = tenant();

        if (! $tenant) {
            abort(500, 'Tenant not found.');
        }

        // Use dedicated column for onboarding completion
        $tenant->onboarding_completed_at = now();
        $tenant->save();

        return response()->json([
            'status'   => 'ok',
            'redirect' => route('tenant.dashboard'),
        ]);
    }
}
