<template>
    <div>
        <div class="flex items-center justify-between gap-4 border-b border-gray-200 pb-2">
            <h2 class="text-base font-semibold text-orange-500">{{ resolvedTexts.title }}</h2>

            <button
                type="button"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition ease-in-out duration-150 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
                @click="addRow"
            >
                {{ resolvedTexts.addWorkplace }}
            </button>
        </div>

        <p v-if="rows.length === 0" class="mt-4 text-sm text-gray-500">
            {{ resolvedTexts.empty }}
        </p>

        <div v-else class="mt-4 space-y-4">
            <div
                v-for="row in rows"
                :key="row.formIndex"
                class="rounded-lg border border-gray-200 p-4"
                :data-workplace-index="row.formIndex"
            >
                <input type="hidden" :name="fieldName(row, 'id')" :value="row.id" />

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label :for="fieldId(row, 'company_id')" class="block text-sm font-medium text-gray-700">
                            {{ resolvedTexts.company }}
                            <span class="ms-1 text-red-600">*</span>
                        </label>

                        <SearchableSelect
                            :id="fieldId(row, 'company_id')"
                            :name="fieldName(row, 'company_id')"
                            :selected="row.company_id"
                            :placeholder="resolvedTexts.select"
                            :options="resolvedCompanyOptions"
                        />

                        <ul v-if="fieldErrors(row, 'company_id').length" class="mt-2 space-y-1 text-sm text-red-600">
                            <li v-for="message in fieldErrors(row, 'company_id')" :key="message">{{ message }}</li>
                        </ul>
                    </div>

                    <div>
                        <label :for="fieldId(row, 'occupation_id')" class="block text-sm font-medium text-gray-700">
                            {{ resolvedTexts.occupation }}
                            <span class="ms-1 text-red-600">*</span>
                        </label>

                        <SearchableSelect
                            :id="fieldId(row, 'occupation_id')"
                            :name="fieldName(row, 'occupation_id')"
                            :selected="row.occupation_id"
                            :placeholder="resolvedTexts.select"
                            :options="resolvedOccupationOptions"
                        />

                        <ul v-if="fieldErrors(row, 'occupation_id').length" class="mt-2 space-y-1 text-sm text-red-600">
                            <li v-for="message in fieldErrors(row, 'occupation_id')" :key="message">{{ message }}</li>
                        </ul>
                    </div>

                    <div>
                        <label :for="fieldId(row, 'from_date')" class="block text-sm font-medium text-gray-700">
                            {{ resolvedTexts.fromDate }}
                        </label>

                        <input
                            :id="fieldId(row, 'from_date')"
                            v-model="row.from_date"
                            :name="fieldName(row, 'from_date')"
                            type="date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 disabled:cursor-not-allowed disabled:border-gray-200 disabled:bg-gray-100 disabled:text-gray-500"
                        />

                        <ul v-if="fieldErrors(row, 'from_date').length" class="mt-2 space-y-1 text-sm text-red-600">
                            <li v-for="message in fieldErrors(row, 'from_date')" :key="message">{{ message }}</li>
                        </ul>
                    </div>

                    <div>
                        <label :for="fieldId(row, 'to_date')" class="block text-sm font-medium text-gray-700">
                            {{ resolvedTexts.toDate }}
                        </label>

                        <input
                            :id="fieldId(row, 'to_date')"
                            v-model="row.to_date"
                            :name="fieldName(row, 'to_date')"
                            type="date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 disabled:cursor-not-allowed disabled:border-gray-200 disabled:bg-gray-100 disabled:text-gray-500"
                        />

                        <ul v-if="fieldErrors(row, 'to_date').length" class="mt-2 space-y-1 text-sm text-red-600">
                            <li v-for="message in fieldErrors(row, 'to_date')" :key="message">{{ message }}</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <button
                        type="button"
                        class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition ease-in-out duration-150 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                        @click="removeRow(row.formIndex)"
                    >
                        {{ resolvedTexts.remove }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import SearchableSelect from './SearchableSelect.vue';

const props = defineProps({
    initialRows: { type: Array, default: () => [] },
    companyOptions: { type: Array, default: () => [] },
    occupationOptions: { type: Array, default: () => [] },
    errors: { type: Object, default: () => ({}) },
    texts: { type: Object, default: () => ({}) },
});

const resolvedTexts = computed(() => ({
    title: 'Workplaces',
    addWorkplace: 'Add workplace',
    empty: 'No workplaces yet. Click "Add workplace".',
    company: 'Company',
    occupation: 'Occupation',
    fromDate: 'From date',
    toDate: 'To date',
    remove: 'Remove',
    select: 'Select',
    ...(props.texts || {}),
}));

const resolvedCompanyOptions = computed(() => (Array.isArray(props.companyOptions) ? props.companyOptions : []));
const resolvedOccupationOptions = computed(() => (Array.isArray(props.occupationOptions) ? props.occupationOptions : []));

function normalizeRow(row, fallbackIndex) {
    const parsedIndex = Number.parseInt(String(row?._form_index ?? fallbackIndex), 10);
    const formIndex = Number.isFinite(parsedIndex) ? parsedIndex : fallbackIndex;

    return {
        formIndex,
        id: row?.id ?? '',
        company_id: row?.company_id ?? '',
        occupation_id: row?.occupation_id ?? '',
        from_date: row?.from_date ?? '',
        to_date: row?.to_date ?? '',
    };
}

const rows = ref((props.initialRows || []).map((row, index) => normalizeRow(row, index)));
const nextIndex = ref(rows.value.reduce((max, row) => Math.max(max, row.formIndex), -1) + 1);

function addRow() {
    rows.value.push(normalizeRow({}, nextIndex.value));
    nextIndex.value += 1;
}

function removeRow(formIndex) {
    rows.value = rows.value.filter((row) => row.formIndex !== formIndex);
}

function fieldId(row, field) {
    return `workplaces_${row.formIndex}_${field}`;
}

function fieldName(row, field) {
    return `workplaces[${row.formIndex}][${field}]`;
}

function fieldErrors(row, field) {
    const messages = props.errors?.[`workplaces.${row.formIndex}.${field}`];
    return Array.isArray(messages) ? messages : [];
}
</script>
