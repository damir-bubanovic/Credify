{{-- resources/views/tenant/billing/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Billing') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                {{-- Header --}}
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Subscription') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Manage your plan and access the Stripe billing portal.') }}
                    </p>
                </div>

                @if($state['subscribed'])
                    {{-- Active subscription --}}
                    @php
                        // $state['plan'] is the Stripe price ID.
                        $planId   = $state['plan'] ?? null;
                        $planName = $planId;

                        if ($planId === ($priceBasic ?? null)) {
                            $planName = __('Basic');
                        } elseif ($planId === ($pricePro ?? null)) {
                            $planName = __('Pro');
                        } elseif ($planId === null) {
                            $planName = __('Active subscription');
                        }
                    @endphp

                    <div class="px-6 py-6">
                        <div class="rounded-lg bg-gray-50 px-4 py-5">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">
                                        {{ __('Current plan') }}
                                    </p>
                                    <p class="mt-1 text-xl font-semibold text-gray-900">
                                        {{ $planName }}
                                    </p>
                                    @if(!empty($state['renews_at']))
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ __('Renews at:') }} {{ $state['renews_at'] }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <a
                                        href="{{ route('tenant.billing.portal') }}"
                                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        {{ __('Manage subscription') }}
                                    </a>

                                    <a
                                        href="{{ route('tenant.dashboard') }}"
                                        class="inline-flex items-center rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        {{ __('Back to dashboard') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- No active subscription: show plans --}}
                    <div class="px-6 py-6">
                        <div class="mb-6 rounded-lg bg-yellow-50 px-4 py-3 text-sm text-yellow-800 border border-yellow-200">
                            {{ __('You do not have an active subscription. Choose a plan to get started.') }}
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            {{-- Basic plan --}}
                            <div class="flex flex-col justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-5">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        {{ __('Basic') }}
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('Good for trying out the platform or smaller workloads.') }}
                                    </p>
                                </div>

                                <form
                                    method="POST"
                                    action="{{ route('tenant.billing.checkout', $priceBasic) }}"
                                    class="mt-4"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        {{ __('Subscribe Basic') }}
                                    </button>
                                </form>
                            </div>

                            {{-- Pro plan --}}
                            <div class="flex flex-col justify-between rounded-lg border border-gray-200 bg-gray-50 px-4 py-5">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        {{ __('Pro') }}
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ __('For growing usage and production workloads.') }}
                                    </p>
                                </div>

                                <form
                                    method="POST"
                                    action="{{ route('tenant.billing.checkout', $pricePro) }}"
                                    class="mt-4"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        {{ __('Subscribe Pro') }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a
                                href="{{ route('tenant.dashboard') }}"
                                class="text-sm font-medium text-gray-500 hover:text-gray-700"
                            >
                                ← {{ __('Back to dashboard') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
