{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Admin dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Status message --}}
            @if(session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Summary cards --}}
            <div class="grid gap-6 md:grid-cols-3 mb-8">
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <div class="text-sm font-medium text-gray-500">
                        {{ __('Total tenants') }}
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $cards['tenants'] }}
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <div class="text-sm font-medium text-gray-500">
                        {{ __('Subscribed tenants') }}
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $cards['subscribed'] }}
                    </div>
                </div>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
                    <div class="text-sm font-medium text-gray-500">
                        {{ __('Total credits across tenants') }}
                    </div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">
                        {{ number_format((int) $cards['credits_sum']) }}
                    </div>
                </div>
            </div>

            {{-- Recent tenants --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __('Recent tenants') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Latest tenants created, with primary domain, plan, and credit balance.') }}
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.tenants.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        {{ __('View all tenants →') }}
                    </a>
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
                                        {{ __('Primary domain') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Created') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Plan') }}
                                    </th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">
                                        {{ __('Credits') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($recent as $t)
                                    @php
                                        $domain = optional($t->domains->first())->domain;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $t->id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($domain)
                                                <a
                                                    href="http://{{ $domain }}"
                                                    target="_blank"
                                                    class="text-indigo-600 hover:text-indigo-500"
                                                >
                                                    {{ $domain }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-500">
                                            {{ $t->created_at }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $t->plan ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-900">
                                            {{ number_format((int) $t->credit_balance) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                            {{ __('No tenants yet.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Latest credit transactions --}}
            <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Latest credit transactions') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Recent changes to tenant credit balances.') }}
                    </p>
                </div>

                <div class="px-6 py-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Tenant') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Type') }}
                                    </th>
                                    <th class="px-4 py-2 text-right font-medium text-gray-500">
                                        {{ __('Amount') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('Reason') }}
                                    </th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-500">
                                        {{ __('At') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($ledger as $row)
                                    @php
                                        $delta  = (int) $row->delta;
                                        $amount = abs($delta);
                                        $sign   = $delta >= 0 ? '+' : '-';
                                        $type   = $delta >= 0 ? __('Credit') : __('Debit');
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $row->tenant_id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium
                                                @if ($delta >= 0)
                                                    border-green-200 bg-green-50 text-green-700
                                                @else
                                                    border-red-200 bg-red-50 text-red-700
                                                @endif
                                            ">
                                                {{ $type }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-semibold
                                            @if ($delta >= 0) text-green-700 @else text-red-700 @endif
                                        ">
                                            {{ $sign }}{{ number_format($amount) }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-700">
                                            {{ $row->reason ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500">
                                            {{ $row->created_at }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                            {{ __('No transactions found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
