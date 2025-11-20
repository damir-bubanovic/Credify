<script setup>
import { reactive, ref } from 'vue';

const step = ref(1);

const form = reactive({
  business_name: '',
  website_url: '',
  industry: '',
  contact_name: '',
  contact_email: '',
});

const saving = ref(false);
const finishing = ref(false);
const message = ref('');
const errors = ref({});

const nextFromStep1 = async () => {
  saving.value = true;
  message.value = '';
  errors.value = {};

  try {
    const response = await window.axios.post('/onboarding/business', form);
    message.value = response.data?.message || 'Saved.';
    step.value = 2;
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errors.value = error.response.data.errors || {};
    } else {
      message.value = 'Unexpected error. Please try again.';
    }
  } finally {
    saving.value = false;
  }
};

const prev = () => {
  if (step.value > 1) step.value--;
};

const goToStep3 = () => {
  step.value = 3;
};

const finish = async () => {
  finishing.value = true;
  message.value = '';

  try {
    const response = await window.axios.post('/onboarding/complete');

    const redirect = response.data?.redirect || '/dashboard';
    window.location.href = redirect;
  } catch (error) {
    message.value = 'Could not complete onboarding. Please try again.';
  } finally {
    finishing.value = false;
  }
};
</script>

<template>
  <div class="bg-white shadow-sm sm:rounded-lg p-6">
    <h1 class="text-xl font-semibold mb-4">Onboarding Wizard</h1>

    <div class="mb-6">
      <p class="text-sm text-gray-500 mb-2">
        Step {{ step }} of 3
      </p>
      <div class="flex items-center gap-2">
        <div
          v-for="s in 3"
          :key="s"
          class="h-2 flex-1 rounded-full"
          :class="s <= step ? 'bg-indigo-600' : 'bg-gray-200'"
        ></div>
      </div>
    </div>

    <!-- Step 1: Business details -->
    <div v-if="step === 1" class="space-y-4">
      <h2 class="text-lg font-semibold mb-2">Step 1: Business Details</h2>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Business name *
        </label>
        <input
          v-model="form.business_name"
          type="text"
          class="w-full rounded-md border-gray-300 shadow-sm"
          :class="errors.business_name ? 'border-red-500' : ''"
        />
        <p v-if="errors.business_name" class="mt-1 text-sm text-red-600">
          {{ errors.business_name[0] }}
        </p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Website URL
        </label>
        <input
          v-model="form.website_url"
          type="text"
          class="w-full rounded-md border-gray-300 shadow-sm"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Industry
        </label>
        <input
          v-model="form.industry"
          type="text"
          class="w-full rounded-md border-gray-300 shadow-sm"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Contact name
          </label>
          <input
            v-model="form.contact_name"
            type="text"
            class="w-full rounded-md border-gray-300 shadow-sm"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Contact email
          </label>
          <input
            v-model="form.contact_email"
            type="email"
            class="w-full rounded-md border-gray-300 shadow-sm"
            :class="errors.contact_email ? 'border-red-500' : ''"
          />
          <p v-if="errors.contact_email" class="mt-1 text-sm text-red-600">
            {{ errors.contact_email[0] }}
          </p>
        </div>
      </div>
    </div>

    <!-- Step 2 placeholder -->
    <div v-else-if="step === 2">
      <h2 class="text-lg font-semibold mb-2">Step 2: Domain Setup</h2>
      <p class="text-gray-700">
        Placeholder for domain configuration. We will build this in the next step.
      </p>
    </div>

    <!-- Step 3 placeholder -->
    <div v-else-if="step === 3">
      <h2 class="text-lg font-semibold mb-2">Step 3: Credits & Alerts</h2>
      <p class="text-gray-700">
        Placeholder for credit configuration and low-balance alerts.
      </p>
    </div>

    <!-- Messages -->
    <p v-if="message" class="mt-4 text-sm text-green-700">
      {{ message }}
    </p>

    <!-- Actions -->
    <div class="mt-6 flex justify-between">
      <button
        v-if="step > 1"
        type="button"
        class="px-4 py-2 bg-gray-200 rounded-md text-gray-800 hover:bg-gray-300"
        @click="prev"
      >
        Back
      </button>

      <div class="ml-auto">
        <button
          v-if="step === 1"
          type="button"
          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
          :disabled="saving"
          @click="nextFromStep1"
        >
          <span v-if="!saving">Save &amp; Continue</span>
          <span v-else>Saving...</span>
        </button>

        <button
          v-else-if="step === 2"
          type="button"
          class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
          @click="goToStep3"
        >
          Next
        </button>

        <button
          v-else
          type="button"
          class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
          :disabled="finishing"
          @click="finish"
        >
          <span v-if="!finishing">Finish</span>
          <span v-else>Finishing...</span>
        </button>
      </div>
    </div>
  </div>
</template>
