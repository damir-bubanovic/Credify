import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';
import OnboardingApp from './components/OnboardingApp.vue';

// Alpine (existing)
window.Alpine = Alpine;
Alpine.start();

// Vue (new)
const onboardingEl = document.getElementById('tenant-onboarding-app');
if (onboardingEl) {
    const app = createApp(OnboardingApp);
    app.mount(onboardingEl);
}
