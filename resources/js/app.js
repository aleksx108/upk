import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue';
import BirthdayCalendar from './components/BirthdayCalendar.vue';

const birthdayCalendarEl = document.getElementById('birthday-calendar');
if (birthdayCalendarEl) {
    const apiUrl = birthdayCalendarEl.dataset.url;
    const days = Number(birthdayCalendarEl.dataset.days || 60);
    createApp(BirthdayCalendar, { apiUrl, days }).mount(birthdayCalendarEl);
}

