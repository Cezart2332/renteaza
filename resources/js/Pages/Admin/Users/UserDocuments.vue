<template>
    <AdminDashboardLayout>
        <div class="tw-space-y-6">
            <!-- Alert lipsă documente -->
            <div
                v-if="props.missingTypes.length > 0"
                class="tw-flex tw-items-start tw-gap-3 tw-rounded-xl tw-border tw-border-red-200 tw-bg-red-50 tw-p-4"
            >
                <svg
                    class="tw-h-5 tw-w-5 tw-text-red-500 tw-mt-0.5"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.681-1.36 3.446 0l6.518 11.591c.75 1.334-.213 2.91-1.723 2.91H3.462c-1.51 0-2.472-1.576-1.722-2.91L8.257 3.1zM11 13a1 1 0 10-2 0 1 1 0 002 0zm-1-8a1 1 0 00-.993.883L9 6v4a1 1 0 001.993.117L11 10V6a1 1 0 00-1-1z"
                        clip-rule="evenodd"
                    />
                </svg>
                <p class="tw-text-sm tw-text-red-800">
                    Lipsesc documentele:
                    <span class="tw-font-semibold">{{
                        props.missingTypes.join(", ")
                    }}</span>
                </p>
            </div>

            <!-- Header -->
            <div class="tw-flex tw-items-end tw-justify-between">
                <div>
                    <h2 class="tw-text-2xl tw-font-semibold tw-text-gray-900">
                        Documente utilizator
                    </h2>
                    <p class="tw-text-sm tw-text-gray-500">
                        Verifică pozele încărcate și adaugă comentarii unde e
                        nevoie.
                    </p>
                </div>
                <div class="tw-flex tw-justify-start tw-space-x-6">
                    <template v-if="user.status === 'pending'">
                        <button
                            type="button"
                            @click="updateStatus('accepted')"
                            class="tw-bg-green-500 hover:tw-bg-green-600 tw-text-white tw-px-8 tw-py-3 tw-rounded-lg tw-text-sm tw-font-semibold tw-shadow"
                        >
                            Acceptă
                        </button>
                        <button
                            type="button"
                            @click="updateStatus('declined')"
                            class="tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-px-8 tw-py-3 tw-rounded-lg tw-text-sm tw-font-semibold tw-shadow"
                        >
                            Respinge
                        </button>
                    </template>

                    <template v-else-if="user.status === 'accepted'">
                        <button
                            type="button"
                            @click="updateStatus('declined')"
                            class="tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-px-8 tw-py-3 tw-rounded-lg tw-text-sm tw-font-semibold tw-shadow"
                        >
                            Respinge
                        </button>
                    </template>

                    <template v-else-if="user.status === 'declined'">
                        <button
                            @click="updateStatus('accepted')"
                            class="tw-bg-green-500 hover:tw-bg-green-600 tw-text-white tw-px-8 tw-py-3 tw-rounded-lg tw-text-sm tw-font-semibold tw-shadow"
                        >
                            Acceptă
                        </button>
                    </template>
                </div>
            </div>

            <!-- Grid documente -->
            <div
                class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-3 xl:tw-grid-cols-4 tw-gap-5"
            >
                <div
                    v-for="type in props.allowedTypes"
                    :key="type"
                    class="tw-group tw-flex tw-flex-col tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-shadow-sm hover:tw-shadow-md tw-transition-shadow"
                >
                    <!-- Card header -->
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-px-4 tw-pt-4"
                    >
                        <label
                            class="tw-text-sm tw-font-medium tw-text-gray-800 tw-capitalize"
                        >
                            {{ formatLabel(type) }}
                        </label>

                        <span
                            v-if="documentData[type]?.url"
                            class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-border tw-border-emerald-200 tw-bg-emerald-50 tw-px-2 tw-py-0.5 tw-text-[11px] tw-font-medium tw-text-emerald-700"
                        >
                            <span
                                class="tw-inline-block tw-h-1.5 tw-w-1.5 tw-rounded-full tw-bg-emerald-500"
                            ></span>
                            {{
                                documentData[type]?.verified_at
                                    ? "Verificat"
                                    : "Încărcat"
                            }}
                        </span>
                        <span
                            v-else
                            class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-border tw-border-gray-200 tw-bg-gray-50 tw-px-2 tw-py-0.5 tw-text-[11px] tw-font-medium tw-text-gray-600"
                        >
                            <span
                                class="tw-inline-block tw-h-1.5 tw-w-1.5 tw-rounded-full tw-bg-gray-400"
                            ></span>
                            Lipsă
                        </span>
                    </div>

                    <!-- Imagine -->
                    <div
                        class="tw-relative tw-mx-4 tw-mt-3 tw-aspect-[4/3] tw-overflow-hidden tw-rounded-lg tw-bg-gray-50"
                    >
                        <template v-if="documentData[type]?.url">
                            <img
                                :src="documentData[type].url"
                                :alt="`${type} preview`"
                                class="tw-h-full tw-w-full tw-object-contain tw-transition-transform group-hover:tw-scale-[1.02] tw-cursor-zoom-in"
                                @click="openImageModal(documentData[type].url)"
                            />
                            <!-- Overlay butoane -->
                            <div
                                class="tw-absolute tw-top-2 tw-right-2 tw-flex tw-gap-2 tw-opacity-0 group-hover:tw-opacity-100 tw-transition-opacity"
                            >
                                <button
                                    type="button"
                                    @click.stop="
                                        openImageModal(documentData[type].url)
                                    "
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-h-8 tw-w-8 tw-rounded-full tw-bg-white tw-text-gray-700 tw-shadow hover:tw-bg-gray-100"
                                    title="Vezi imaginea"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="tw-h-5 tw-w-5"
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
                                <a
                                    :href="documentData[type].url"
                                    download
                                    target="_blank"
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-h-8 tw-w-8 tw-rounded-full tw-bg-white tw-text-gray-700 tw-shadow hover:tw-bg-gray-100"
                                    title="Descarcă"
                                    >⬇️</a
                                >
                            </div>
                        </template>
                        <div
                            v-else
                            class="tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-text-xs tw-text-gray-500"
                        >
                            Nu există poză
                        </div>
                    </div>

                    <!-- Comentariu / status -->
                    <div class="tw-px-4 tw-pb-4 tw-pt-3 tw-space-y-2">
                        <template v-if="documentData[type]?.admin_comment">
                            <p
                                class="tw-text-xs tw-font-medium tw-text-gray-700"
                            >
                                Comentariu:
                            </p>
                            <div
                                class="tw-text-sm tw-text-gray-800 tw-rounded tw-border tw-border-gray-200 tw-bg-gray-50 tw-p-2"
                            >
                                {{ documentData[type].admin_comment }}
                            </div>
                            <p
                                v-if="documentData[type]?.verified_at"
                                class="tw-text-[11px] tw-text-emerald-700"
                            >
                                ✅ Verificat pe
                                {{ documentData[type].verified_at }}
                            </p>
                        </template>
                        <template v-else>
                            <textarea
                                v-model="comments[type]"
                                placeholder="Adaugă comentariu pentru acest document..."
                                rows="3"
                                class="tw-w-full tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-p-2 tw-text-sm placeholder:tw-text-gray-400 focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-border-indigo-500"
                            ></textarea>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Submit feedback -->
            <div
                v-if="hasPendingComments"
                class="tw-sticky tw-bottom-4 tw-flex tw-justify-end"
            >
                <button
                    @click="submitFeedback"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-bg-indigo-600 tw-px-5 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700 tw-shadow-md"
                >
                    Trimite feedback
                </button>
            </div>
        </div>

        <!-- Modal imagine fullscreen -->
        <div
            v-if="showModal"
            class="tw-fixed tw-inset-0 tw-z-50 tw-bg-black/70 tw-flex tw-items-center tw-justify-center tw-p-4"
        >
            <div
                class="tw-relative tw-w-full tw-max-w-6xl tw-rounded-2xl tw-bg-white tw-shadow-2xl"
            >
                <div
                    class="tw-h-[78vh] tw-w-full tw-overflow-auto tw-rounded-2xl"
                >
                    <img
                        :src="modalImageSrc"
                        alt="Document preview"
                        class="tw-block tw-h-full tw-w-full tw-object-contain"
                    />
                </div>
                <button
                    @click="closeImageModal"
                    class="tw-absolute tw-top-2 tw-right-2 tw-h-9 tw-w-9 tw-rounded-full tw-bg-white tw-text-gray-800 tw-shadow hover:tw-bg-gray-100"
                    title="Închide"
                >
                    ✕
                </button>
            </div>
        </div>
    </AdminDashboardLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";

const props = defineProps({
    user: Object,
    documents: Array,
    missingTypes: Array,
    allowedTypes: Array,
});

const documentData = ref({});
const comments = ref({});

props.allowedTypes.forEach((type) => {
    const doc = props.documents.find((d) => d.type === type);
    documentData.value[type] = {
        url: doc?.url || null,
        admin_comment: doc?.admin_comment || "",
        verified_at: doc?.verified_at || null,
    };
    comments.value[type] = "";
});

const formatLabel = (type) =>
    type.replace(/_/g, " ").replace(/\b\w/g, (l) => l.toUpperCase());

const hasPendingComments = computed(() =>
    props.allowedTypes.some(
        (type) =>
            !documentData.value[type].admin_comment && comments.value[type]
    )
);

function submitFeedback() {
    const data = { comments: {} };
    props.allowedTypes.forEach((type) => {
        if (!documentData.value[type].admin_comment && comments.value[type]) {
            data.comments[type] = comments.value[type];
        }
    });

    router.post(
        route("admin.users.documents.comment", { user: props.user }),
        data,
        {
            preserveScroll: true,
            onSuccess: () => {
                props.allowedTypes.forEach((type) => {
                    comments.value[type] = "";
                });
            },
        }
    );
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

// actualizează statusul
function updateStatus(status) {
    router.put(
        route("admin.users.status.update", props.user.id),
        {
            status: status,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                window.location.reload();
            },

            onError: (errors) => {
                console.error("Failed to update", errors);
            },
        }
    );
}
</script>
