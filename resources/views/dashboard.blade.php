{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

            {{-- Logged-in card --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="px-6 py-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __("You're logged in!") }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('This is the central dashboard page. If you are using the Vue onboarding tool, it mounts below.') }}
                    </p>
                </div>
            </div>

            {{-- Vue mount point --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Tenant Onboarding (Vue App)') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('The Vue application will load below and handle tenant creation or configuration.') }}
                    </p>
                </div>

                <div class="px-6 py-6">
                    <div
                        id="tenant-onboarding-app"
                        class="min-h-[150px] rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4"
                    ></div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
