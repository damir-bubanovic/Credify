{{-- resources/views/tenant/campaigns/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Campaigns') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $campaign->name }}
                        </h3>

                        <div class="mt-1 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @class([
                                    'bg-gray-100 text-gray-800' => $campaign->status === 'draft',
                                    'bg-green-100 text-green-800' => $campaign->status === 'active',
                                    'bg-yellow-100 text-yellow-800' => $campaign->status === 'paused',
                                ])
                            ">
                                {{ ucfirst($campaign->status) }}
                            </span>

                            @if($campaign->created_at)
                                <span>
                                    {{ __('Created') }}: {{ $campaign->created_at->format('Y-m-d H:i') }}
                                </span>
                            @endif

                            @if($campaign->updated_at)
                                <span>
                                    {{ __('Last updated') }}: {{ $campaign->updated_at->format('Y-m-d H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a
                            href="{{ route('tenant.campaigns.edit', $campaign) }}"
                            class="inline-flex items-center rounded-md border border-gray-200 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50"
                        >
                            {{ __('Edit') }}
                        </a>

                        <form
                            method="POST"
                            action="{{ route('tenant.campaigns.destroy', $campaign) }}"
                            onsubmit="return confirm('{{ __('Delete this campaign? This cannot be undone.') }}');"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md border border-red-200 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-50"
                            >
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <dl class="grid gap-6 sm:grid-cols-2">
                        <div class="rounded-lg bg-gray-50 px-4 py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ __('Status') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ ucfirst($campaign->status) }}
                            </dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 px-4 py-3">
                            <dt class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ __('Spend') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $campaign->spend }}
                            </dd>
                        </div>

                        {{-- Add more fields here as your Campaign model grows, e.g. budget, audience, etc. --}}
                    </dl>
                </div>

                <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 flex items-center justify-between">
                    <a
                        href="{{ route('tenant.campaigns.index') }}"
                        class="text-sm font-medium text-gray-500 hover:text-gray-700"
                    >
                        ← {{ __('Back to campaigns') }}
                    </a>

                    <a
                        href="{{ route('tenant.campaigns.edit', $campaign) }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        {{ __('Edit campaign') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
