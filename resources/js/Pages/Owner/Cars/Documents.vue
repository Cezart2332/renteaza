<template>
    <OwnerDashboardLayout>
        <form
            @submit.prevent="submitDocuments"
            class="tw-bg-gray-100 tw-p-6 tw-rounded-lg"
        >
            <!-- Alert pentru lipsă documente -->
            <div
                v-if="props.missingTypes.length > 0"
                class="tw-bg-red-100 tw-border-l-4 tw-border-red-400 tw-p-4 tw-rounded tw-mb-6 tw-flex tw-items-start tw-space-x-3"
            >
                <svg
                    class="tw-w-6 tw-h-6 tw-text-red-400 tw-flex-shrink-0"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.681-1.36 3.446 0l6.518 11.591c.75 1.334-.213 2.91-1.723 2.91H3.462c-1.51 0-2.472-1.576-1.722-2.91L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                <p class="tw-text-red-700 tw-text-sm">
                    Lipsesc următoarele documente:
                    <span class="tw-font-semibold">{{
                        props.missingTypes.join(", ")
                    }}</span>
                </p>
            </div>

            <h2 class="tw-text-xl tw-font-semibold tw-text-gray-800 tw-mb-4">
                Documente
            </h2>

            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-6">
                <div
                    v-for="type in props.allowedTypes"
                    :key="type"
                    class="tw-border tw-bg-white tw-rounded tw-p-4 tw-relative"
                >
                    <label
                        class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2 tw-capitalize"
                    >
                        {{ formatLabel(type) }}
                    </label>

                    <div
                        v-if="documentUrls[type]"
                        class="tw-flex tw-flex-col tw-items-center"
                    >
                        <div class="tw-relative">
                            <!-- Imaginea -->
                            <img
                                :src="documentUrls[type]"
                                :alt="`${type} preview`"
                                class="tw-h-72 tw-w-full tw-object-cover tw-rounded-md tw-border"
                            />

                            <!-- Buton ochi -->
                            <button
                                type="button"
                                @click="openImageModal(documentUrls[type])"
                                class="tw-absolute tw-top-2 tw-right-2 tw-bg-white tw-rounded-full tw-shadow-md tw-p-1 hover:tw-bg-gray-100"
                                title="Vezi imaginea"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="tw-h-5 tw-w-5 tw-text-gray-700"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div
                        v-else
                        class="tw-text-center tw-text-sm tw-text-gray-500 tw-italic tw-mb-2"
                    >
                        Nu există poză
                    </div>

                    <div v-if="editing[type]" class="tw-mt-2">
                        <input
                            type="file"
                            accept="image/*"
                            @change="handleFileChange($event, type)"
                            class="tw-block tw-w-full tw-text-sm tw-text-gray-500 file:tw-mr-4 file:tw-py-2 file:tw-px-4 file:tw-rounded-full file:tw-border-0 file:tw-text-sm file:tw-font-semibold file:tw-bg-orange-50 file:tw-text-orange-700 hover:file:tw-bg-orange-100"
                        />
                    </div>

                    <div v-else class="tw-mt-2">
                        <input
                            type="file"
                            accept="image/*"
                            @change="handleFileChange($event, type)"
                            class="tw-block tw-w-full tw-text-sm tw-text-gray-500 file:tw-mr-4 file:tw-py-2 file:tw-px-4 file:tw-rounded-full file:tw-border-0 file:tw-text-sm file:tw-font-semibold file:tw-bg-orange-50 file:tw-text-orange-700 hover:file:tw-bg-orange-100"
                        />
                    </div>
                </div>
            </div>

            <div v-if="documentsChanged" class="tw-mt-6 tw-flex tw-justify-end">
                <button
                    type="submit"
                    class="tw-px-6 tw-py-3 tw-bg-green-600 hover:tw-bg-green-700 tw-text-white tw-font-bold tw-rounded-full tw-shadow tw-transition tw-duration-300"
                >
                    Upload Documents
                </button>
            </div>
        </form>
        <!-- Modal imagine fullscreen -->

        <div
            v-if="showModal"
            class="tw-fixed tw-inset-0 tw-z-50 tw-bg-black/70 tw-flex tw-items-center tw-justify-center tw-p-4"
            @click.self="closeImageModal"
        >
            <div
                class="tw-relative tw-bg-white tw-rounded-lg tw-shadow-lg tw-p-2"
            >
                <!-- imaginea NU e absolută; nu o întindem la 100% -->
                <img
                    :src="modalImageSrc"
                    alt="Document preview"
                    class="tw-block tw-rounded tw-object-contain tw-select-none"
                    style="
                        max-width: 90vw;
                        max-height: 90vh;
                        width: auto;
                        height: auto;
                    "
                    draggable="false"
                />

                <!-- Buton de închidere -->
                <button
                    @click="closeImageModal"
                    class="tw-absolute tw-top-2 tw-right-2 tw-bg-white tw-text-black tw-rounded-full tw-p-2 tw-shadow-md hover:tw-bg-gray-200"
                    title="Închide"
                >
                    ✕
                </button>
            </div>
        </div>
    </OwnerDashboardLayout>
</template>
<script setup>
import { ref, computed } from "vue";
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    documents: Array,
    missingTypes: Array,
    allowedTypes: Array,
    vehicle: Object,
});

// Dinamic URL mapping
const documentUrls = ref({});
const editing = ref({});
const uploadedFiles = ref({});
const documentsChanged = ref(false);

// Setăm URL-urile inițiale
props.allowedTypes.forEach((type) => {
    const doc = props.documents.find((d) => d.type === type);
    documentUrls.value[type] = doc?.url || null;
    editing.value[type] = false;
    uploadedFiles.value[type] = null;
});

const formatLabel = (type) => {
    return type.replace(/_/g, " ").replace(/\b\w/g, (l) => l.toUpperCase());
};

function handleFileChange(event, type) {
    const file = event.target.files[0];
    if (file) {
        uploadedFiles.value[type] = file;
        documentsChanged.value = true;

        const reader = new FileReader();
        reader.onload = (e) => {
            documentUrls.value[type] = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
function submitDocuments() {
    const data = new FormData();

    // Adăugăm fișierele selectate
    props.allowedTypes.forEach((type) => {
        if (uploadedFiles.value[type]) {
            data.append(type, uploadedFiles.value[type]);
        }
    });

    props.allowedTypes.forEach((type, index) => {
        data.append(`allowedTypes[${index}]`, type);
    });

    data.append("vehicle_id", props.vehicle.id);

    router.post(route("user.cars.upload-documents"), data, {
        forceFormData: true,
        onSuccess: () => {
            documentsChanged.value = false;
            props.allowedTypes.forEach((type) => {
                editing.value[type] = false;
            });
        },
    });
}

const showModal = ref(false);
const modalImageSrc = ref("");

function openImageModal(src) {
    modalImageSrc.value = src;
    showModal.value = true;
}

function closeImageModal() {
    showModal.value = false;
    modalImageSrc.value = "";
}
</script>
