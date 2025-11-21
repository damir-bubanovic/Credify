{{-- resources/views/tenant/onboarding.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Onboarding') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">

            {{-- Intro card --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100 mb-8">
                <div class="px-6 py-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Welcome to Credify') }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                        {{ __('Before you start using the platform, please complete a few required setup steps. This helps us configure your tenant workspace and ensure billing and API access work correctly.') }}
                    </p>
                </div>
            </div>

            {{-- Steps --}}
            <div class="space-y-6">

                {{-- Step 1: Profile (links to dashboard for now) --}}
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 p-6">
                    <h4 class="text-md font-semibold text-gray-900">
                        1. {{ __('Review your account details') }}
                    </h4>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Check that your account information and email address are correct.') }}
                    </p>

                    <a
                        href="{{ route('dashboard') }}"
                        class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    >
                        {{ __('Go to dashboard') }}
                    </a>
                </div>

                {{-- Step 2: Billing --}}
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 p-6">
                    <h4 class="text-md font-semibold text-gray-900">
                        2. {{ __('Set up your subscription') }}
                    </h4>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Choose a plan to activate your workspace and unlock the ability to launch campaigns.') }}
                    </p>

                    <a
                        href="{{ route('tenant.billing.show') }}"
                        class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    >
                        {{ __('Choose a plan') }}
                    </a>
                </div>

                {{-- Step 3: API Keys --}}
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 p-6">
                    <h4 class="text-md font-semibold text-gray-900">
                        3. {{ __('Generate your API keys') }}
                    </h4>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('You will need API keys to connect your applications or import data.') }}
                    </p>

                    <a
                        href="{{ route('tenant.api-keys.index') }}"
                        class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                    >
                        {{ __('Manage API keys') }}
                    </a>
                </div>

                {{-- Step 4: Finish --}}
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 p-6">
                    <h4 class="text-md font-semibold text-gray-900">
                        4. {{ __('Finish onboarding') }}
                    </h4>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Once you complete the steps above, you can start creating campaigns and exploring Credify features.') }}
                    </p>

                    <form method="POST" action="{{ route('tenant.onboarding.complete') }}" class="mt-4">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500"
                        >
                            {{ __('Mark onboarding as complete') }}
                        </button>
                    </form>
                </div>

            </div>

            {{-- Back --}}
            <div class="mt-8 text-center">
                <a
                    href="{{ route('dashboard') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700"
                >
                    ← {{ __('Back to dashboard') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
