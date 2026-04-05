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

        <div v-if="calendarError" class="mt-4 text-sm text-red-700">{{ calendarError }}</div>

        <div class="mt-4">
            <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-start">
                <div class="lg:col-span-9">
                    <div class="relative overflow-hidden rounded-lg border border-gray-200">
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

                        <div v-if="calendarLoading" class="pointer-events-none absolute inset-0 bg-white/60">
                            <div class="absolute right-3 top-3 text-xs font-semibold text-gray-500">Loading...</div>
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
                            <div class="text-xs text-gray-500">Next {{ upcomingDays }} days</div>
                        </div>

                        <div v-if="upcomingError" class="mt-2 text-sm text-red-700">{{ upcomingError }}</div>
                        <div v-else-if="upcomingLoading" class="mt-2 text-sm text-gray-500">Loading upcoming birthdays...</div>
                        <div v-else-if="upcomingItems.length" class="mt-2 space-y-1">
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
import { computed, onMounted, ref, watch } from 'vue';

const props = defineProps({
    apiUrl: { type: String, required: true },
    // Upcoming list window (in days).
    days: { type: Number, default: 60 },
});

function clampDays(value) {
    if (value === null || value === undefined || value === '') return null;

    const numeric = Number(value);
    if (!Number.isFinite(numeric)) return null;

    return Math.max(1, Math.min(366, Math.trunc(numeric)));
}

const upcomingDays = computed(() => clampDays(props.days) ?? 60);

const calendarLoading = ref(true);
const calendarError = ref('');
const calendarItems = ref([]);

const upcomingLoading = ref(true);
const upcomingError = ref('');
const upcomingAllItems = ref([]);

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

function calendarGridStart(monthDate) {
    const first = startOfMonth(monthDate);
    // Monday-first offset
    const offset = (first.getDay() + 6) % 7;
    return addDays(first, -offset);
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

const calendarRange = computed(() => {
    const gridStart = calendarGridStart(viewMonth.value);
    const gridEnd = addDays(gridStart, 41);

    return {
        start: toIsoDate(gridStart),
        end: toIsoDate(gridEnd),
    };
});

const itemsByDate = computed(() => {
    const map = new Map();

    for (const item of calendarItems.value) {
        const iso = item.next_birthday;
        if (!map.has(iso)) map.set(iso, []);
        map.get(iso).push(item);
    }

    return map;
});

const calendarDays = computed(() => {
    const first = startOfMonth(viewMonth.value);
    const gridStart = calendarGridStart(first);

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
    if (!calendarItems.value.length) return '';

    const start = calendarItems.value[0]?.next_birthday;
    const end = calendarItems.value[calendarItems.value.length - 1]?.next_birthday;
    if (!start || !end) return '';

    return `Birthdays: ${formatIsoDate(start)} - ${formatIsoDate(end)}`;
});

const upcomingItems = computed(() => upcomingAllItems.value.slice(0, 10));

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

let calendarAbortController = null;
let upcomingAbortController = null;

async function fetchJson(url, { signal } = {}) {
    const res = await fetch(url.toString(), {
        headers: { Accept: 'application/json' },
        credentials: 'same-origin',
        signal,
    });

    const raw = await res.text();

    if (!res.ok) {
        const snippet = raw ? raw.replace(/\s+/g, ' ').trim().slice(0, 200) : '';
        throw new Error(`Request failed (${res.status})${snippet ? `: ${snippet}` : ''}`);
    }

    try {
        return raw ? JSON.parse(raw) : {};
    } catch {
        const snippet = raw ? raw.replace(/\s+/g, ' ').trim().slice(0, 200) : '';
        throw new Error(`Invalid JSON response${snippet ? `: ${snippet}` : ''}`);
    }
}

async function loadCalendar() {
    if (calendarAbortController) {
        calendarAbortController.abort();
    }

    const controller = new AbortController();
    calendarAbortController = controller;

    calendarLoading.value = true;
    calendarError.value = '';

    try {
        const url = new URL(props.apiUrl, window.location.origin);
        url.searchParams.set('start', calendarRange.value.start);
        url.searchParams.set('end', calendarRange.value.end);

        const data = await fetchJson(url, { signal: controller.signal });
        if (calendarAbortController !== controller) return;

        calendarItems.value = Array.isArray(data.items) ? data.items : [];
    } catch (e) {
        if (e?.name === 'AbortError') return;
        if (calendarAbortController !== controller) return;

        calendarError.value = e?.message || 'Failed to load birthdays.';
        calendarItems.value = [];
    } finally {
        if (calendarAbortController === controller) {
            calendarLoading.value = false;
        }
    }
}

async function loadUpcoming() {
    if (upcomingAbortController) {
        upcomingAbortController.abort();
    }

    const controller = new AbortController();
    upcomingAbortController = controller;

    upcomingLoading.value = true;
    upcomingError.value = '';

    try {
        const url = new URL(props.apiUrl, window.location.origin);
        url.searchParams.set('days', String(upcomingDays.value));

        const data = await fetchJson(url, { signal: controller.signal });
        if (upcomingAbortController !== controller) return;

        upcomingAllItems.value = Array.isArray(data.items) ? data.items : [];
    } catch (e) {
        if (e?.name === 'AbortError') return;
        if (upcomingAbortController !== controller) return;

        upcomingError.value = e?.message || 'Failed to load upcoming birthdays.';
        upcomingAllItems.value = [];
    } finally {
        if (upcomingAbortController === controller) {
            upcomingLoading.value = false;
        }
    }
}
watch(viewMonth, () => {
    loadCalendar();
}, { immediate: true });

onMounted(() => {
    loadUpcoming();
});
</script>
