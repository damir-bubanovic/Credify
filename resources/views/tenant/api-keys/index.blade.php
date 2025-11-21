{{-- resources/views/tenant/api-keys/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('API keys') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">

            {{-- Status message --}}
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Existing keys --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Existing API keys') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Use these keys to authenticate requests to your tenant APIs. Keep them secret and rotate regularly.') }}
                    </p>
                </div>

                <div class="px-6 py-6">
                    @if ($keys->isEmpty())
                        <div class="rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">
                            {{ __('No API keys yet. Generate one below.') }}
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Name') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Key') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Last used') }}
                                        </th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500">
                                            {{ __('Created') }}
                                        </th>
                                        <th class="px-4 py-2 text-right font-medium text-gray-500">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($keys as $key)
                                        @php
                                            $fullKey = $key->key;
                                            $masked = $fullKey
                                                ? str_repeat('•', max(strlen($fullKey) - 4, 0)) . substr($fullKey, -4)
                                                : '—';
                                        @endphp

                                        <tr>
                                            <td class="px-4 py-3 text-gray-900">
                                                {{ $key->name ?: __('(no name)') }}
                                            </td>

                                            <td class="px-4 py-3 font-mono text-xs text-gray-800">
                                                {{ $masked }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-500">
                                                @if ($key->last_used_at)
                                                    {{ $key->last_used_at->diffForHumans() }}
                                                @else
                                                    {{ __('Never') }}
                                                @endif
                                            </td>

                                            <td class="px-4 py-3 text-gray-500">
                                                {{ $key->created_at->format('Y-m-d H:i') }}
                                            </td>

                                            <td class="px-4 py-3 text-right">
                                                <form
                                                    method="POST"
                                                    action="{{ route('tenant.api-keys.destroy', $key) }}"
                                                    onsubmit="return confirm('{{ __('Delete this API key? Requests using it will stop working.') }}');"
                                                    class="inline-block"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50"
                                                    >
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Create new key --}}
            <div class="mt-8 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Create new API key') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Optionally give the key a name (for example, the application or environment where it will be used).') }}
                    </p>
                </div>

                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('tenant.api-keys.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label
                                for="name"
                                class="block text-sm font-medium text-gray-700"
                            >
                                {{ __('Name (optional)') }}
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ __('e.g. Backend server, Staging app') }}"
                            >
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                {{ __('The generated key will be shown once in the success message. Store it securely.') }}
                            </p>

                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                {{ __('Generate key') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Back link --}}
            <div class="mt-8">
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
