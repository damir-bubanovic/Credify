import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';

import OnboardingApp from './components/OnboardingApp.vue';
import OnboardingWizard from './components/OnboardingWizard.vue';

// Alpine initialization
window.Alpine = Alpine;
Alpine.start();

// Shared mount element
const mountEl = document.getElementById('tenant-onboarding-app');

if (mountEl) {
    const type = mountEl.dataset.type;

    // Vue mount for test page (/vue-test)
    if (type === 'test') {
        createApp(OnboardingApp).mount(mountEl);
    }

    // Vue mount for onboarding wizard (/onboarding)
    if (type === 'wizard') {
        createApp(OnboardingWizard).mount(mountEl);
    }
}
