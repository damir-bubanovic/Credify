{{-- resources/views/tenant/credits/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Credits') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Balance + settings card --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __('Credit balance & settings') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ __('Configure low-balance alerts and auto top-up behaviour.') }}
                        </p>
                    </div>
                </div>

                <div class="px-6 py-6 grid gap-6 lg:grid-cols-3">
                    {{-- Balance --}}
                    <div class="lg:col-span-1">
                        <div class="rounded-lg bg-gray-50 px-4 py-5 text-center">
                            <div class="text-sm font-medium text-gray-500">
                                {{ __('Current balance') }}
                            </div>
                            <div class="mt-2 text-4xl font-bold text-gray-900">
                                {{ number_format($balance->balance, 0) }}
                            </div>
                        </div>
                    </div>

                    {{-- Settings form --}}
                    <div class="lg:col-span-2">
                        <form
                            method="POST"
                            action="{{ route('tenant.credits.settings') }}"
                            class="space-y-4"
                        >
                            @csrf

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label
                                        for="low_threshold"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ __('Low threshold') }}
                                    </label>
                                    <input
                                        id="low_threshold"
                                        name="low_threshold"
                                        type="number"
                                        min="0"
                                        value="{{ old('low_threshold', $balance->low_threshold) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>

                                <div>
                                    <label
                                        for="topup_amount"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ __('Top-up amount') }}
                                    </label>
                                    <input
                                        id="topup_amount"
                                        name="topup_amount"
                                        type="number"
                                        min="0"
                                        value="{{ old('topup_amount', $balance->topup_amount) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center">
                                    <input
                                        id="auto_topup_enabled"
                                        name="auto_topup_enabled"
                                        type="checkbox"
                                        value="1"
                                        @checked(old('auto_topup_enabled', $balance->auto_topup_enabled))
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <label
                                        for="auto_topup_enabled"
                                        class="ml-2 text-sm text-gray-700"
                                    >
                                        {{ __('Enable automatic top-up') }}
                                    </label>
                                </div>

                                <div class="sm:text-right">
                                    <label
                                        for="stripe_price_id"
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        {{ __('Stripe price ID for top-up') }}
                                    </label>
                                    <input
                                        id="stripe_price_id"
                                        name="stripe_price_id"
                                        type="text"
                                        value="{{ old('stripe_price_id', $balance->stripe_price_id) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="price_xxx"
                                    >
                                </div>
                            </div>

                            <div class="pt-2">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    {{ __('Save settings') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Ledger --}}
            <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h4 class="text-md font-semibold text-gray-900">
                        {{ __('Credit ledger') }}
                    </h4>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('All credit movements over time.') }}
                    </p>
                </div>

                <div class="px-6 py-4">
                    @if ($ledger->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">
                            {{ __('No ledger entries yet.') }}
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Date') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Reason') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Δ') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Balance after') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($ledger as $row)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-500">
                                                {{ $row->created_at }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $row->reason }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="font-semibold
                                                    @if ($row->delta >= 0) text-green-600 @else text-red-600 @endif
                                                ">
                                                    {{ $row->delta }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-700">
                                                {{ $row->balance_after }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $ledger->links() }}
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                    <a
                        href="{{ route('dashboard') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-700"
                    >
                        ← {{ __('Back to dashboard') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
