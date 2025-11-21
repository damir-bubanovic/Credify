{{-- resources/views/admin/tenants/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tenants') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Filters --}}
            <div class="mb-4 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Filter tenants') }}
                    </h3>
                </div>

                <div class="px-6 py-4">
                    <form class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4" method="GET">
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-500 mb-1">
                                {{ __('Status') }}
                            </label>
                            <select
                                id="status"
                                name="status"
                                class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">{{ __('All') }}</option>
                                <option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option>
                                <option value="suspended" @selected(request('status') === 'suspended')>{{ __('Suspended') }}</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                id="only_trashed"
                                name="only_trashed"
                                value="1"
                                @checked(request('only_trashed'))
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <label for="only_trashed" class="text-sm text-gray-700">
                                {{ __('Deleted') }}
                            </label>
                        </div>

                        <div class="sm:ml-auto">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                {{ __('Apply filters') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tenants table --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Tenants') }}
                    </h3>
                </div>

                <div class="px-6 py-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('ID') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Domain') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Status') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Created') }}
                                    </th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($tenants as $t)
                                    @php
                                        $domain = optional($t->domains->first())->domain;
                                        $status = $t->status ?? 'active';
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900">
                                            <a
                                                href="{{ route('admin.tenants.show', $t) }}"
                                                class="font-medium text-indigo-600 hover:text-indigo-500"
                                            >
                                                {{ $t->id }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $domain ?: '—' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                                @if ($status === 'active')
                                                    bg-green-100 text-green-800
                                                @elseif ($status === 'suspended')
                                                    bg-yellow-100 text-yellow-800
                                                @else
                                                    bg-gray-100 text-gray-800
                                                @endif
                                            ">
                                                {{ ucfirst($status) }}
                                            </span>
                                            @if(method_exists($t, 'trashed') && $t->trashed())
                                                <span class="ml-2 inline-flex rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700">
                                                    {{ __('Deleted') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-500">
                                            {{ $t->created_at }}
                                        </td>
                                        <td class="px-4 py-3 text-right whitespace-nowrap">
                                            <div class="flex justify-end gap-2">

                                                <a
                                                    href="{{ route('admin.tenants.show', $t) }}"
                                                    class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                                >
                                                    {{ __('View') }}
                                                </a>

                                                @if(method_exists($t, 'trashed') && $t->trashed())
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.tenants.restore', $t->id) }}"
                                                        class="inline-block"
                                                    >
                                                        @csrf
                                                        @method('PATCH')
                                                        <button
                                                            type="submit"
                                                            class="rounded-md border border-green-200 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50"
                                                        >
                                                            {{ __('Restore') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    @if($status === 'active')
                                                        <form
                                                            method="POST"
                                                            action="{{ route('admin.tenants.suspend', $t) }}"
                                                            class="inline-block"
                                                        >
                                                            @csrf
                                                            @method('PATCH')
                                                            <button
                                                                type="submit"
                                                                class="rounded-md border border-yellow-200 px-3 py-1.5 text-xs font-medium text-yellow-700 hover:bg-yellow-50"
                                                            >
                                                                {{ __('Suspend') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form
                                                            method="POST"
                                                            action="{{ route('admin.tenants.activate', $t) }}"
                                                            class="inline-block"
                                                        >
                                                            @csrf
                                                            @method('PATCH')
                                                            <button
                                                                type="submit"
                                                                class="rounded-md border border-green-200 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-50"
                                                            >
                                                                {{ __('Activate') }}
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.tenants.destroy', $t) }}"
                                                        class="inline-block"
                                                        onsubmit="return confirm('{{ __('Delete tenant?') }}');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50"
                                                        >
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                            {{ __('No tenants found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $tenants->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
