{{-- resources/views/tenant/campaigns/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('New campaign') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Create a new campaign and choose its initial status.') }}
                    </p>
                </div>

                <div class="px-6 py-6">
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold">{{ __('There were some problems with your input:') }}</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('tenant.campaigns.store') }}"
                        class="space-y-6"
                    >
                        @csrf

                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ __('Name') }}
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                required
                                value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ __('e.g. Black Friday 2025') }}"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label
                                for="status"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ __('Status') }}
                            </label>
                            <select
                                id="status"
                                name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                @php
                                    $currentStatus = old('status', 'draft');
                                @endphp
                                <option value="draft"  @selected($currentStatus === 'draft')>{{ __('Draft') }}</option>
                                <option value="active" @selected($currentStatus === 'active')>{{ __('Active') }}</option>
                                <option value="paused" @selected($currentStatus === 'paused')>{{ __('Paused') }}</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a
                                href="{{ route('tenant.campaigns.index') }}"
                                class="text-sm font-medium text-gray-500 hover:text-gray-700"
                            >
                                {{ __('Back to campaigns') }}
                            </a>

                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                {{ __('Create campaign') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
