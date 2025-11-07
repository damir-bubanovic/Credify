<x-app-layout>
    <h1 class="mb-2">Tenants</h1>

    @if(session('status'))
        <div style="padding:.5rem 1rem;border:1px solid #cbd5e1;background:#f1f5f9;margin-bottom:1rem;">
            {{ session('status') }}
        </div>
    @endif

    <form class="mb-4 flex gap-2" method="GET">
        <select name="status" class="border p-2">
            <option value="">All</option>
            <option value="active" @selected(request('status')==='active')>Active</option>
            <option value="suspended" @selected(request('status')==='suspended')>Suspended</option>
        </select>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="only_trashed" value="1" @checked(request('only_trashed'))> Deleted
        </label>
        <button type="submit" class="border px-3 py-2 rounded">Filter</button>
    </form>

    <div style="overflow:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">ID</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Domain</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Status</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Created</th>
                    <th style="padding:.5rem;border-bottom:1px solid #e5e7eb;text-align:left;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($tenants as $t)
                @php
                    $domain = optional($t->domains->first())->domain;
                    $status = $t->status ?? 'active';
                @endphp
                <tr>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                        <a href="{{ route('admin.tenants.show', $t) }}" style="color:#2563eb;text-decoration:none;">
                            {{ $t->id }}
                        </a>
                    </td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                        {{ $domain ?: '—' }}
                    </td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                        {{ $status }}
                    </td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;">
                        {{ $t->created_at }}
                    </td>
                    <td style="padding:.5rem;border-bottom:1px solid #f1f5f9;white-space:nowrap;">
                        <a href="{{ route('admin.tenants.show', $t) }}" style="margin-right:.5rem;color:#2563eb;">View</a>

                        @if(method_exists($t, 'trashed') && $t->trashed())
                            <form method="POST" action="{{ route('admin.tenants.restore', $t->id) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="margin-right:.5rem;color:#16a34a;">Restore</button>
                            </form>
                        @else
                            @if($status === 'active')
                                <form method="POST" action="{{ route('admin.tenants.suspend', $t) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="margin-right:.5rem;color:#ca8a04;">Suspend</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.tenants.activate', $t) }}" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="margin-right:.5rem;color:#16a34a;">Activate</button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.tenants.destroy', $t) }}" style="display:inline;" onsubmit="return confirm('Delete tenant?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color:#b91c1c;">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:.75rem;text-align:center;color:#6b7280;">
                        No tenants found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">
        {{ $tenants->links() }}
    </div>
</x-app-layout>
