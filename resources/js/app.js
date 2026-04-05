import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue';
import BirthdayCalendar from './components/BirthdayCalendar.vue';

const birthdayCalendarEl = document.getElementById('birthday-calendar');
if (birthdayCalendarEl) {
    const apiUrl = birthdayCalendarEl.dataset.url;
    const props = { apiUrl };

    if (birthdayCalendarEl.dataset.days !== undefined) {
        const days = Number(birthdayCalendarEl.dataset.days);
        if (Number.isFinite(days)) props.days = days;
    }

    createApp(BirthdayCalendar, props).mount(birthdayCalendarEl);
}

