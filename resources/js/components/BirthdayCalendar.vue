<template>
    <div class="w-full rounded-lg border border-gray-200 bg-white p-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <div class="text-base font-semibold text-gray-900">
                    {{ monthLabel }}
                </div>
                <div v-if="rangeLabel" class="mt-1 text-xs text-gray-500">
                    {{ rangeLabel }}
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                    @click="prevMonth"
                >
                    Prev
                </button>
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                    @click="goToday"
                >
                    Today
                </button>
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                    @click="nextMonth"
                >
                    Next
                </button>
            </div>
        </div>

        <div v-if="loading" class="mt-4 text-sm text-gray-500">Loading birthdays...</div>
        <div v-else-if="error" class="mt-4 text-sm text-red-700">{{ error }}</div>

        <div v-else class="mt-4">
            <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-start">
                <div class="lg:col-span-9">
                    <div class="overflow-hidden rounded-lg border border-gray-200">
                        <div class="grid grid-cols-7 bg-gray-50">
                            <div
                                v-for="label in weekdayLabels"
                                :key="label"
                                class="px-2 py-2 text-center text-xs font-semibold text-gray-600"
                            >
                                {{ label }}
                            </div>
                        </div>

                        <div class="grid grid-cols-7 gap-px bg-gray-200">
                            <div
                                v-for="day in calendarDays"
                                :key="day.key"
                                class="group relative min-h-[6rem] cursor-pointer select-none bg-white px-2 py-2 text-left transition-colors hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-inset"
                                :class="dayCellClass(day)"
                                @click="selectDate(day.iso)"
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <span
                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-semibold"
                                        :class="dayNumberClass(day)"
                                    >
                                        {{ day.dayOfMonth }}
                                    </span>

                                    <span
                                        v-if="day.count"
                                        class="rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-semibold text-orange-700"
                                    >
                                        {{ day.count }}
                                    </span>
                                </div>

                                <div class="mt-2 space-y-1">
                                    <div
                                        v-for="person in day.items.slice(0, 2)"
                                        :key="person.id"
                                        class="flex items-center gap-1 truncate text-[11px]"
                                        :class="day.isCurrentMonth ? 'text-gray-700' : 'text-gray-400'"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>
                                        <span class="truncate">{{ person.first_name }} {{ person.last_name }}</span>
                                    </div>

                                    <div
                                        v-if="day.count > 2"
                                        class="text-[10px]"
                                        :class="day.isCurrentMonth ? 'text-gray-500' : 'text-gray-400'"
                                    >
                                        +{{ day.count - 2 }} more
                                    </div>
                                </div>

                                <div v-if="day.isToday" class="pointer-events-none absolute inset-x-2 bottom-2">
                                    <div class="h-0.5 w-8 rounded-full bg-orange-300/70"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3 flex flex-col gap-4">
                    <div class="rounded-lg bg-gray-50 p-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="text-sm font-semibold text-gray-900">Selected day</div>
                            <div class="text-xs text-gray-500">{{ selectedLabel }}</div>
                        </div>

                        <div v-if="selectedItems.length" class="mt-2 space-y-1">
                            <div v-for="person in selectedItems" :key="person.id" class="flex items-center justify-between gap-3 text-sm">
                                <a :href="person.personnel_url" class="min-w-0 truncate text-orange-700 hover:text-orange-600 hover:underline">
                                    {{ person.name }}
                                </a>
                                <span class="shrink-0 text-xs text-gray-500">turns {{ person.turning_age }}</span>
                            </div>
                        </div>
                        <div v-else class="mt-2 text-sm text-gray-500">No birthdays on this day.</div>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="text-sm font-semibold text-gray-900">Upcoming</div>
                            <div class="text-xs text-gray-500">{{ daysCaption }}</div>
                        </div>

                        <div v-if="upcomingItems.length" class="mt-2 space-y-1">
                            <div v-for="person in upcomingItems" :key="person.id" class="flex items-center justify-between gap-3 text-sm">
                                <a :href="person.personnel_url" class="min-w-0 truncate text-orange-700 hover:text-orange-600 hover:underline">
                                    {{ person.name }}
                                </a>
                                <span class="shrink-0 text-xs text-gray-500">{{ formatIsoDate(person.next_birthday) }}</span>
                            </div>
                        </div>
                        <div v-else class="mt-2 text-sm text-gray-500">No upcoming birthdays in this range.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    apiUrl: { type: String, required: true },
    days: { type: Number, default: null },
});

function clampDays(value) {
    if (value === null || value === undefined || value === '') return null;

    const numeric = Number(value);
    if (!Number.isFinite(numeric)) return null;

    return Math.max(0, Math.min(366, Math.trunc(numeric)));
}

function daysUntilEndOfYear(fromDate = new Date()) {
    const start = startOfDay(fromDate);
    const end = startOfDay(new Date(start.getFullYear(), 11, 31));
    const diffDays = Math.round((end.getTime() - start.getTime()) / 86400000);
    return Math.max(0, diffDays);
}

const explicitDays = computed(() => clampDays(props.days));
const days = computed(() => explicitDays.value ?? daysUntilEndOfYear(new Date()));

const daysCaption = computed(() => {
    if (explicitDays.value !== null) return `Next ${days.value} days`;
    return 'Until end of year';
});

const loading = ref(true);
const error = ref('');
const items = ref([]);

const locale = document.documentElement.lang || navigator.language || 'en';

function startOfDay(date) {
    const d = new Date(date);
    d.setHours(0, 0, 0, 0);
    return d;
}

function startOfMonth(date) {
    const d = startOfDay(date);
    d.setDate(1);
    return d;
}

function addDays(date, delta) {
    const d = new Date(date);
    d.setDate(d.getDate() + delta);
    return d;
}

function toIsoDate(date) {
    const d = startOfDay(date);
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}

function parseIsoDate(iso) {
    // Force local midnight.
    return startOfDay(new Date(`${iso}T00:00:00`));
}

const viewMonth = ref(startOfMonth(new Date()));
const selectedIso = ref(toIsoDate(new Date()));

const monthLabel = computed(() => {
    return new Intl.DateTimeFormat(locale, { month: 'long', year: 'numeric' }).format(viewMonth.value);
});

const weekdayLabels = computed(() => {
    // Monday-first labels.
    const baseMonday = new Date(2026, 0, 5); // 2026-01-05 is a Monday
    const fmt = new Intl.DateTimeFormat(locale, { weekday: 'short' });
    return Array.from({ length: 7 }, (_, i) => fmt.format(addDays(baseMonday, i)));
});

const itemsByDate = computed(() => {
    const map = new Map();

    for (const item of items.value) {
        const iso = item.next_birthday;
        if (!map.has(iso)) map.set(iso, []);
        map.get(iso).push(item);
    }

    return map;
});

const calendarDays = computed(() => {
    const first = startOfMonth(viewMonth.value);
    // Monday-first offset
    const offset = (first.getDay() + 6) % 7;
    const gridStart = addDays(first, -offset);

    const todayIso = toIsoDate(new Date());

    return Array.from({ length: 42 }, (_, i) => {
        const date = addDays(gridStart, i);
        const iso = toIsoDate(date);
        const list = itemsByDate.value.get(iso) || [];

        return {
            key: iso,
            iso,
            dayOfMonth: date.getDate(),
            isCurrentMonth: date.getMonth() === first.getMonth(),
            isToday: iso === todayIso,
            isSelected: iso === selectedIso.value,
            count: list.length,
            items: list,
        };
    });
});

const selectedItems = computed(() => itemsByDate.value.get(selectedIso.value) || []);

const selectedLabel = computed(() => {
    try {
        return new Intl.DateTimeFormat(locale, { dateStyle: 'full' }).format(parseIsoDate(selectedIso.value));
    } catch {
        return selectedIso.value;
    }
});

const rangeLabel = computed(() => {
    if (!items.value.length) return '';

    const start = items.value[0]?.next_birthday;
    const end = items.value[items.value.length - 1]?.next_birthday;
    if (!start || !end) return '';

    return `Upcoming birthdays: ${formatIsoDate(start)} - ${formatIsoDate(end)}`;
});

const upcomingItems = computed(() => items.value.slice(0, 10));

function formatIsoDate(iso) {
    try {
        return new Intl.DateTimeFormat(locale, { month: 'short', day: '2-digit' }).format(parseIsoDate(iso));
    } catch {
        return iso;
    }
}

function dayCellClass(day) {
    if (day.isSelected) return 'ring-2 ring-orange-500 ring-inset';
    if (!day.isCurrentMonth) return 'bg-gray-50/60';
    return '';
}

function dayNumberClass(day) {
    if (day.isToday) return 'bg-orange-500 text-white';
    if (day.isSelected) return 'text-orange-700';
    if (!day.isCurrentMonth) return 'text-gray-400';
    return 'text-gray-900';
}

function selectDate(iso) {
    selectedIso.value = iso;
}

function prevMonth() {
    const d = new Date(viewMonth.value);
    d.setMonth(d.getMonth() - 1);
    viewMonth.value = startOfMonth(d);
}

function nextMonth() {
    const d = new Date(viewMonth.value);
    d.setMonth(d.getMonth() + 1);
    viewMonth.value = startOfMonth(d);
}

function goToday() {
    viewMonth.value = startOfMonth(new Date());
    selectedIso.value = toIsoDate(new Date());
}

async function loadBirthdays() {
    loading.value = true;
    error.value = '';

    try {
        const url = new URL(props.apiUrl, window.location.origin);
        url.searchParams.set('days', String(days.value));

        const res = await fetch(url.toString(), {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (!res.ok) {
            throw new Error(`Request failed (${res.status})`);
        }

        const data = await res.json();
        items.value = Array.isArray(data.items) ? data.items : [];
    } catch (e) {
        error.value = e?.message || 'Failed to load birthdays.';
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    loadBirthdays();
});
</script>










