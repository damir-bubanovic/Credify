<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tenant::class);

        $tenants = Tenant::query()
            ->with('domains:id,tenant_id,domain')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed())
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        $tenant->load('domains:id,tenant_id,domain');
        $subscriptions = $tenant->subscriptions()->get();

        return view('admin.tenants.show', compact('tenant','subscriptions'));
    }

    public function suspend(Tenant $tenant)
    {
        $this->authorize('suspend', $tenant);

        $tenant->suspend();

        return back()->with('status', 'Tenant suspended');
    }

    public function activate(Tenant $tenant)
    {
        $this->authorize('suspend', $tenant);

        $tenant->activate();

        return back()->with('status', 'Tenant activated');
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorize('delete', $tenant);

        $tenant->delete(); // soft delete

        return redirect()->route('admin.tenants.index')->with('status', 'Tenant deleted');
    }

    public function restore(string $id)
    {
        $tenant = Tenant::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $tenant);

        $tenant->restore();

        return back()->with('status', 'Tenant restored');
    }
}
