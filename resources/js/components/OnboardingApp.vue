<script setup>
const steps = [
  'Confirm your business details',
  'Connect your domain',
  'Configure credits & alerts',
  'Invite your team',
];
</script>

<template>
  <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">
      Tenant onboarding (Vue test)
    </h2>
    <p class="text-gray-600 mb-4">
      This box is rendered by Vue via Vite. If you see it, Vue integration works.
    </p>

    <ol class="list-decimal list-inside space-y-1 text-gray-700">
      <li v-for="step in steps" :key="step">
        {{ step }}
      </li>
    </ol>
  </div>
</template>
