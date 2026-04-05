<template>
    <div ref="root" class="relative">
        <input
            :id="id"
            type="text"
            readonly
            :value="displayValue"
            class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-700 shadow-sm hover:bg-gray-50 focus:border-orange-500 focus:ring-orange-500"
            role="combobox"
            :aria-expanded="open ? 'true' : 'false'"
            aria-haspopup="listbox"
            @click="toggle"
            @keydown.down.prevent="openDropdown"
            @keydown.enter.prevent="toggle"
            @keydown.escape.prevent="close"
        />

        <input type="hidden" :name="name" :value="selectedValue" />

        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.94l3.71-3.71a.75.75 0 1 1 1.06 1.06l-4.24 4.24a.75.75 0 0 1-1.06 0L5.21 8.29a.75.75 0 0 1 .02-1.08Z" clip-rule="evenodd" />
            </svg>
        </span>

        <div
            v-show="open"
            class="absolute z-50 mt-1 w-full rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5"
            @keydown.escape.prevent="close"
        >
            <div class="p-2">
                <input
                    ref="searchInput"
                    v-model="search"
                    type="text"
                    class="block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                    :placeholder="searchPlaceholder"
                    @keydown.escape.prevent="close"
                />
            </div>

            <div class="max-h-60 overflow-auto py-1" role="listbox">
                <button
                    type="button"
                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                    @click="select(null)"
                >
                    {{ placeholder }}
                </button>

                <button
                    v-for="opt in filteredOptions"
                    :key="String(opt.value)"
                    type="button"
                    class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                    :class="String(opt.value) === String(selected ?? '') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-700'"
                    @click="select(opt.value)"
                >
                    <span class="block truncate">{{ opt.label }}</span>
                </button>

                <div v-if="filteredOptions.length === 0" class="px-4 py-2 text-sm text-gray-500">
                    No results.
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const props = defineProps({
    id: { type: String, required: true },
    name: { type: String, required: true },
    options: { type: Array, default: () => [] },
    selected: { type: [String, Number, null], default: null },
    placeholder: { type: String, default: 'All' },
    searchPlaceholder: { type: String, default: 'Search...' },
});

const root = ref(null);
const searchInput = ref(null);

const open = ref(false);
const search = ref('');
const selected = ref(props.selected === '' ? null : props.selected);

watch(
    () => props.selected,
    (value) => {
        selected.value = value === '' ? null : value;
    },
);

const selectedOption = computed(() => {
    return (props.options || []).find((opt) => String(opt?.value) === String(selected.value));
});

const displayValue = computed(() => {
    return selectedOption.value?.label || props.placeholder;
});

const selectedValue = computed(() => {
    return selected.value === null || selected.value === undefined ? '' : String(selected.value);
});

const filteredOptions = computed(() => {
    const needle = (search.value || '').toLowerCase().trim();
    if (!needle) return props.options || [];

    return (props.options || []).filter((opt) => String(opt?.label || '').toLowerCase().includes(needle));
});

function openDropdown() {
    if (open.value) return;
    open.value = true;
    nextTick(() => {
        searchInput.value?.focus();
    });
}

function close() {
    open.value = false;
    search.value = '';
}

function toggle() {
    if (open.value) {
        close();
        return;
    }

    openDropdown();
}

function select(value) {
    selected.value = value === '' ? null : value;
    close();
}

function onDocumentClick(event) {
    if (!open.value) return;
    const el = root.value;
    if (!el) return;

    if (el.contains(event.target)) return;
    close();
}

onMounted(() => {
    document.addEventListener('click', onDocumentClick);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', onDocumentClick);
});
</script>