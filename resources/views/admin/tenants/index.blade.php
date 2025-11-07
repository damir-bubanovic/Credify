<x-layout>
    <h1 class="text-2xl font-semibold mb-4">Tenants</h1>

    <form class="mb-4 flex gap-2">
        <select name="status" class="border p-2">
            <option value="">All</option>
            <option value="active" @selected(request('status')==='active')>Active</option>
            <option value="suspended" @selected(request('status')==='suspended')>Suspended</option>
        </select>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="only_trashed" value="1" @checked(request('only_trashed'))> Deleted
        </label>
        <button class="border px-3 py-2 rounded">Filter</button>
    </form>

    <table class="w-full border text-sm">
        <thead>
            <tr>
                <th class="p-2 border">ID</th>
                <th class="p-2 border">Domain</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Created</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($tenants as $t)
            <tr>
                <td class="p-2 border">{{ $t->id }}</td>
                <td class="p-2 border">{{ optional($t->domains->first())->domain }}</td>
                <td class="p-2 border">{{ $t->status ?? 'active' }}</td>
                <td class="p-2 border">{{ $t->created_at }}</td>
                <td class="p-2 border space-x-2">
                    <a href="{{ route('admin.tenants.show',$t) }}" class="text-blue-600">View</a>

                    @if($t->trashed())
                        <form method="POST" action="{{ route('admin.tenants.restore',$t->id) }}" class="inline">@csrf @method('PATCH')
                            <button class="text-green-700">Restore</button>
                        </form>
                    @else
                        @if(($t->status ?? 'active')==='active')
                            <form method="POST" action="{{ route('admin.tenants.suspend',$t) }}" class="inline">@csrf @method('PATCH')
                                <button class="text-yellow-700">Suspend</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.tenants.activate',$t) }}" class="inline">@csrf @method('PATCH')
                                <button class="text-green-700">Activate</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.tenants.destroy',$t) }}" class="inline" onsubmit="return confirm('Delete tenant?')">@csrf @method('DELETE')
                            <button class="text-red-700">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $tenants->links() }}</div>
</x-layout>
