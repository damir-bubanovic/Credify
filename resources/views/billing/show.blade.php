{{-- resources/views/billing/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Billing') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">

            {{-- Optional success/cancel message from Stripe redirect --}}
            @if (request('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ __('Subscription created successfully.') }}
                </div>
            @endif

            @if (request('canceled'))
                <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
                    {{ __('Subscription flow was canceled.') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                {{-- Header --}}
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Choose your plan') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Select a subscription plan to get started. You can manage your subscription later in the customer portal.') }}
                    </p>
                </div>

                {{-- Plans --}}
                <div class="px-6 py-6">
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
                                action="{{ route('admin.billing.checkout', 'basic') }}"
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
                                action="{{ route('admin.billing.checkout', 'pro') }}"
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

                    {{-- Portal link --}}
                    <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
                        <p class="text-xs text-gray-500">
                            {{ __('Already subscribed? You can update payment details or cancel anytime in the billing portal.') }}
                        </p>

                        <a
                            href="{{ route('admin.billing.portal') }}"
                            class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                        >
                            {{ __('Manage billing') }} →
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
