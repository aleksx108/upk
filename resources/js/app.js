import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import { createApp } from 'vue';
import BirthdayCalendar from './components/BirthdayCalendar.vue';
import SearchableSelect from './components/SearchableSelect.vue';
import PortraitPhotoInput from './components/PortraitPhotoInput.vue';

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



function mountSearchableSelects() {
    document.querySelectorAll('.vue-searchable-select').forEach((el) => {
        if (el.dataset.mounted === '1') return;

        const id = el.dataset.id;
        const name = el.dataset.name;
        const placeholder = el.dataset.placeholder || 'All';
        const fallbackSelect = el.querySelector('select');
        const selected = (el.dataset.selected ?? (fallbackSelect ? fallbackSelect.value : '')) ?? '';
        const optionsRaw = el.dataset.options || '[]';

        let options = [];
        try {
            options = JSON.parse(optionsRaw);
        } catch {
            options = [];
        }

        if (!id || !name) return;

        el.dataset.mounted = '1';

        const app = createApp(SearchableSelect, {
            id,
            name,
            placeholder,
            selected: selected === '' ? null : selected,
            options: Array.isArray(options) ? options : [],
        });

        app.mount(el);
        el.__searchableSelectApp = app;
    });
}

window.mountSearchableSelects = mountSearchableSelects;

mountSearchableSelects();

function mountPortraitPhotoInputs() {
    document.querySelectorAll('.vue-portrait-photo-input').forEach((el) => {
        if (el.dataset.mounted === '1') return;

        const readonly = el.dataset.readonly === '1';
        const originalSrc = el.dataset.originalSrc || '';
        const placeholderSrc = el.dataset.placeholderSrc || '';
        const initialRemove = el.dataset.initialRemove === '1';

        const fileInputId = el.dataset.fileInputId || 'portrait_photo';
        const fileName = el.dataset.fileName || 'portrait_photo';
        const removeId = el.dataset.removeId || 'remove_portrait_photo';
        const removeName = el.dataset.removeName || 'remove_portrait_photo';

        const textAlt = el.dataset.textAlt || 'Portrait photo';
        const textUpload = el.dataset.textUpload || 'Upload';
        const textCurrentPhoto = el.dataset.textCurrentPhoto || 'Current photo';
        const textNoPhoto = el.dataset.textNoPhoto || 'No photo';
        const textReplace = el.dataset.textReplace || 'Uploading a new file will replace the current photo.';
        const textHoverUpload = el.dataset.textHoverUpload || 'Hover to upload; click to choose a file.';

        if (!placeholderSrc) return;

        el.dataset.mounted = '1';
        const app = createApp(PortraitPhotoInput, {
            readonly,
            originalSrc,
            placeholderSrc,
            initialRemove,
            fileInputId,
            fileName,
            removeId,
            removeName,
            textAlt,
            textUpload,
            textCurrentPhoto,
            textNoPhoto,
            textReplace,
            textHoverUpload,
        });

        app.mount(el);
        el.__portraitPhotoApp = app;
    });
}

mountPortraitPhotoInputs();
