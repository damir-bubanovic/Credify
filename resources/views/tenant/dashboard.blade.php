{{-- resources/views/tenant/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Welcome / tenant info --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100 mb-8">
                <div class="px-6 py-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Welcome') }}, {{ auth()->user()->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Here is an overview of your campaigns, credits, and billing status.') }}
                    </p>
                </div>
            </div>

            {{-- Stats grid --}}
            <div class="grid gap-6 md:grid-cols-3">

                {{-- Campaigns --}}
                <a href="{{ route('tenant.campaigns.index') }}"
                   class="block rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 hover:bg-gray-50 transition">
                    <h4 class="text-sm font-medium text-gray-500">
                        {{ __('Active Campaigns') }}
                    </h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $campaignsCount ?? 0 }}
                    </p>
                    <p class="mt-1 text-sm text-indigo-600">
                        {{ __('View campaigns →') }}
                    </p>
                </a>

                {{-- Credits --}}
                <a href="{{ route('tenant.credits.index') }}"
                   class="block rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 hover:bg-gray-50 transition">
                    <h4 class="text-sm font-medium text-gray-500">
                        {{ __('Credits Balance') }}
                    </h4>
                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $creditsBalance ?? 0 }}
                    </p>
                    <p class="mt-1 text-sm text-indigo-600">
                        {{ __('Manage credits →') }}
                    </p>
                </a>

                {{-- Subscription --}}
                <a href="{{ route('tenant.billing.show') }}"
                   class="block rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-100 hover:bg-gray-50 transition">
                    <h4 class="text-sm font-medium text-gray-500">
                        {{ __('Subscription') }}
                    </h4>

                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        @php
                            $plan = $subscription ?? null;
                        @endphp

                        @if ($plan === 'pro')
                            {{ __('Pro') }}
                        @elseif ($plan === 'basic')
                            {{ __('Basic') }}
                        @elseif ($plan)
                            {{ $plan }}
                        @else
                            {{ __('None') }}
                        @endif
                    </p>

                    <p class="mt-1 text-sm text-indigo-600">
                        {{ __('Manage billing →') }}
                    </p>
                </a>
            </div>

            {{-- Quick actions --}}
            <div class="mt-10">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ __('Quick actions') }}
                </h3>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    <a href="{{ route('tenant.campaigns.create') }}"
                       class="block rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-800 hover:bg-gray-50 shadow-sm">
                        {{ __('Create new campaign') }}
                    </a>

                    <a href="{{ route('tenant.api-keys.index') }}"
                       class="block rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-800 hover:bg-gray-50 shadow-sm">
                        {{ __('Manage API keys') }}
                    </a>

                    <a href="{{ route('tenant.billing.show') }}"
                       class="block rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-800 hover:bg-gray-50 shadow-sm">
                        {{ __('View billing settings') }}
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
