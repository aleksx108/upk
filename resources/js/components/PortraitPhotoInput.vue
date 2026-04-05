<template>
    <div class="mt-2 flex items-center gap-4">
        <div class="relative shrink-0">
            <img
                v-if="readonly"
                :src="previewSrc"
                :alt="textAlt"
                class="h-20 w-20 rounded-full object-cover ring-1 ring-gray-200"
            />

            <template v-else>
                <label :for="fileInputId" class="group relative block cursor-pointer">
                    <img
                        :src="previewSrc"
                        :alt="textAlt"
                        class="h-20 w-20 rounded-full object-cover ring-1 ring-gray-200"
                    />

                    <div class="absolute inset-0 rounded-full bg-black/40 opacity-0 transition-opacity group-hover:opacity-100"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 transition-opacity group-hover:opacity-100">
                        <span class="rounded-md bg-white/90 px-2 py-1 text-xs font-semibold uppercase tracking-widest text-gray-900">
                            {{ textUpload }}
                        </span>
                    </div>
                </label>

                <input
                    :id="fileInputId"
                    ref="fileInput"
                    :name="fileName"
                    type="file"
                    accept="image/*"
                    class="sr-only"
                    @change="onFileChange"
                />

                <input
                    v-if="hasOriginal"
                    :id="removeId"
                    type="checkbox"
                    :name="removeName"
                    value="1"
                    class="sr-only"
                    :checked="remove"
                    @change="remove = $event.target.checked"
                />

                <button
                    v-if="showX"
                    type="button"
                    class="absolute -top-1 -right-1 z-10 cursor-pointer rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                    :aria-label="xLabel"
                    @click="onXClick"
                >
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white shadow-sm hover:bg-red-500">
                        &times;
                    </span>
                </button>
            </template>
        </div>

        <div class="min-w-0">
            <template v-if="readonly">
                <span class="text-sm text-gray-500">{{ hasOriginal ? textCurrentPhoto : textNoPhoto }}</span>
            </template>

            <template v-else>
                <span class="text-sm text-gray-500">{{ hasOriginal ? textReplace : textHoverUpload }}</span>
            </template>
        </div>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount, ref, watch } from 'vue';

const props = defineProps({
    readonly: { type: Boolean, default: false },
    originalSrc: { type: String, default: '' },
    placeholderSrc: { type: String, required: true },
    initialRemove: { type: Boolean, default: false },

    fileInputId: { type: String, default: 'portrait_photo' },
    fileName: { type: String, default: 'portrait_photo' },

    removeId: { type: String, default: 'remove_portrait_photo' },
    removeName: { type: String, default: 'remove_portrait_photo' },

    textAlt: { type: String, default: 'Portrait photo' },
    textUpload: { type: String, default: 'Upload' },
    textCurrentPhoto: { type: String, default: 'Current photo' },
    textNoPhoto: { type: String, default: 'No photo' },
    textReplace: { type: String, default: 'Uploading a new file will replace the current photo.' },
    textHoverUpload: { type: String, default: 'Hover to upload; click to choose a file.' },
});

const fileInput = ref(null);

const remove = ref(Boolean(props.initialRemove));
const file = ref(null);
const fileUrl = ref('');

const hasOriginal = computed(() => Boolean(props.originalSrc));
const hasSelectedFile = computed(() => Boolean(file.value));

const showX = computed(() => hasOriginal.value || hasSelectedFile.value);

const xLabel = computed(() => {
    if (hasSelectedFile.value) return 'Clear selected image';
    if (hasOriginal.value && remove.value) return 'Undo remove';
    if (hasOriginal.value) return 'Remove photo';
    return 'Clear';
});

const previewSrc = computed(() => {
    if (remove.value) return props.placeholderSrc;
    if (fileUrl.value) return fileUrl.value;
    if (props.originalSrc) return props.originalSrc;
    return props.placeholderSrc;
});

watch(file, (newFile) => {
    if (fileUrl.value) {
        URL.revokeObjectURL(fileUrl.value);
        fileUrl.value = '';
    }

    if (!newFile) return;

    fileUrl.value = URL.createObjectURL(newFile);
});

function onFileChange(event) {
    const selected = event.target.files && event.target.files[0];
    if (!selected) return;

    file.value = selected;
    remove.value = false;
}

function clearSelectedFile() {
    file.value = null;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}

function onXClick() {
    if (hasSelectedFile.value) {
        clearSelectedFile();
        return;
    }

    if (hasOriginal.value) {
        remove.value = !remove.value;
    }
}

onBeforeUnmount(() => {
    if (fileUrl.value) {
        URL.revokeObjectURL(fileUrl.value);
    }
});
</script>