<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiKeyController extends Controller
{
    public function index()
    {
        $tenantId = tenant('id');

        $keys = TenantApiKey::where('tenant_id', $tenantId)
            ->orderByDesc('created_at')
            ->get();

        return view('tenant.api-keys.index', compact('keys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $tenantId = tenant('id');

        TenantApiKey::create([
            'tenant_id' => $tenantId,
            'name'      => $request->input('name'),
            'key'       => Str::random(40),
        ]);

        return redirect()
            ->route('tenant.api-keys.index')
            ->with('status', 'API key created.');
    }

    public function destroy(TenantApiKey $apiKey)
    {
        $tenantId = tenant('id');

        if ($apiKey->tenant_id !== $tenantId) {
            abort(403);
        }

        $apiKey->delete();

        return redirect()
            ->route('tenant.api-keys.index')
            ->with('status', 'API key deleted.');
    }
}
