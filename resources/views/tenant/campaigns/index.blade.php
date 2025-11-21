{{-- resources/views/tenant/campaigns/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @php
                $campaignItems = $campaigns->map(function ($c) {
                    return [
                        'id'          => $c->id,
                        'name'        => $c->name,
                        'status'      => $c->status,
                        'created_at'  => optional($c->created_at)->format('Y-m-d'),
                        'show_url'    => route('tenant.campaigns.show', $c),
                        'edit_url'    => route('tenant.campaigns.edit', $c),
                        'destroy_url' => route('tenant.campaigns.destroy', $c),
                    ];
                });
            @endphp

            <div
                id="campaigns-app"
                class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100"
                data-campaigns='@json($campaignItems)'
                data-create-url="{{ route('tenant.campaigns.create') }}"
            >
                {{-- Vue will render here --}}
                <div class="p-6 text-sm text-gray-500">
                    Loading campaigns…
                </div>
            </div>

            <div class="mt-4">
                {{ $campaigns->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
