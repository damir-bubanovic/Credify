<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    campaigns: {
        type: Array,
        default: () => [],
    },
    createUrl: {
        type: String,
        required: true,
    },
});

const csrfToken =
    document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const search = ref('');
const statusFilter = ref('');

const filteredCampaigns = computed(() => {
    const term = search.value.toLowerCase();

    return props.campaigns.filter((c) => {
        const matchesSearch =
            !term ||
            (c.name && c.name.toLowerCase().includes(term));

        const matchesStatus =
            !statusFilter.value || c.status === statusFilter.value;

        return matchesSearch && matchesStatus;
    });
});

const statusClasses = (status) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'paused':
            return 'bg-yellow-100 text-yellow-800';
        case 'draft':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const confirmDelete = (event) => {
    if (!confirm('Delete this campaign?')) {
        event.preventDefault();
    }
};
</script>

<template>
    <div class="flex flex-col">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    Campaigns
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your campaigns, statuses and actions.
                </p>
            </div>

            <a
                :href="createUrl"
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                + New campaign
            </a>
        </div>

        <div class="px-6 py-4">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 gap-2">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search campaigns…"
                        class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />

                    <select
                        v-model="statusFilter"
                        class="w-40 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All statuses</option>
                        <option value="draft">Draft</option>
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                    </select>
                </div>

                <button
                    type="button"
                    @click="() => { search = ''; statusFilter = ''; }"
                    class="text-xs text-gray-400 hover:text-gray-500"
                >
                    Reset filters
                </button>
            </div>

            <div
                v-if="filteredCampaigns.length === 0"
                class="rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500"
            >
                No campaigns found.
            </div>

            <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left font-medium text-gray-500">Name</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500">Status</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500">Created at</th>
                            <th class="px-4 py-2 text-right font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        <tr v-for="c in filteredCampaigns" :key="c.id">
                            <td class="px-4 py-3 text-gray-900">
                                <a
                                    :href="c.show_url"
                                    class="font-medium text-indigo-600 hover:text-indigo-500"
                                >
                                    {{ c.name }}
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium"
                                    :class="statusClasses(c.status)"
                                >
                                    {{ c.status ? c.status.charAt(0).toUpperCase() + c.status.slice(1) : 'Unknown' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">
                                {{ c.created_at || '—' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a
                                        :href="c.edit_url"
                                        class="rounded-md border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        :action="c.destroy_url"
                                        method="POST"
                                        class="inline-block"
                                        @submit="confirmDelete"
                                    >
                                        <input type="hidden" name="_token" :value="csrfToken" />
                                        <input type="hidden" name="_method" value="DELETE" />

                                        <button
                                            type="submit"
                                            class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50"
                                        >
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination links are rendered below this component in Blade -->
        </div>
    </div>
</template>
