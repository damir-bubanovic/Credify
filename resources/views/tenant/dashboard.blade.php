<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tenant Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Overview of your campaigns and credit usage.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a
                    href="{{ route('tenant.campaigns.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    New Campaign
                </a>

                <a
                    href="{{ route('tenant.credits.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Manage Credits
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Top stats cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Credits card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">
                            Available credits
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">
                            {{ number_format($credit) }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Credits are consumed when you send impressions via the API.
                        </p>
                    </div>
                </div>

                {{-- Total campaigns --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">
                            Total campaigns
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">
                            {{ $totals['campaigns'] ?? 0 }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            All campaigns created for this tenant.
                        </p>
                    </div>
                </div>

                {{-- Active campaigns --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">
                            Active campaigns
                        </p>
                        <p class="mt-2 text-3xl font-semibold text-gray-900">
                            {{ $totals['active'] ?? 0 }}
                        </p>
                        <p class="mt-2 text-xs text-gray-500">
                            Campaigns currently running (status = active).
                        </p>
                    </div>
                </div>
            </div>

            {{-- Activity / last 30 days --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Campaigns created (last 30 days)
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Daily count of new campaigns.
                            </p>
                        </div>
                    </div>

                    @if(!empty($daily))
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 pr-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="text-left py-2 pr-4 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            New campaigns
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($daily as $date => $count)
                                        <tr>
                                            <td class="py-2 pr-4 text-gray-900">
                                                {{ $date }}
                                            </td>
                                            <td class="py-2 pr-4 text-gray-900">
                                                {{ $count }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">
                            No campaign activity recorded in the last 30 days.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
