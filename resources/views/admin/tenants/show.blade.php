{{-- resources/views/admin/tenants/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tenant') }} #{{ $tenant->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Tenant summary card --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100 mb-8">
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __('Tenant details') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Overview of tenant status, domains, plan and credits.') }}
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.tenants.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-700"
                    >
                        ← {{ __('Back to tenants') }}
                    </a>
                </div>

                <div class="px-6 py-6">
                    @php
                        $status = $tenant->status ?? 'active';
                        $domains = $tenant->domains->pluck('domain')->join(', ') ?: '—';
                        $credits = number_format((int) $tenant->credit_balance);
                        $plan    = $tenant->plan ?? '—';
                    @endphp

                    <dl class="grid gap-6 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 px-4 py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ __('Status') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
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
                            </dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 px-4 py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ __('Plan') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $plan }}
                            </dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 px-4 py-3 sm:col-span-2">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ __('Domains') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $domains }}
                            </dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 px-4 py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ __('Credits') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $credits }}
                            </dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 px-4 py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ __('Created at') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $tenant->created_at }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Subscriptions table --}}
            @if($subscriptions->count())
                <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __('Subscriptions') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Stripe subscriptions associated with this tenant.') }}
                        </p>
                    </div>

                    <div class="px-6 py-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Name') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Status') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Price') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Quantity') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Created') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($subscriptions as $s)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-900">
                                                {{ $s->name }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $s->stripe_status }}
                                            </td>
                                            <td class="px-4 py-3 font-mono text-xs text-gray-800">
                                                {{ $s->stripe_price }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $s->quantity }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-500">
                                                {{ $s->created_at }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Back link below, in addition to header card --}}
            <div class="mt-8">
                <a
                    href="{{ route('admin.tenants.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700"
                >
                    ← {{ __('Back to tenants') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
