<template>
    <div
        v-if="documents.length < totalDocuments"
        class="tw-mt-12 tw-bg-yellow-50 tw-border-l-4 tw-border-yellow-400"
    >
        <!-- Alert Upload with button next to text -->
        <div
            class="tw-p-4 tw-rounded-lg tw-flex tw-items-center tw-justify-between"
        >
            <div class="tw-flex tw-items-center tw-space-x-3">
                <!-- Icon -->
                <svg
                    class="tw-w-6 tw-h-6 tw-text-yellow-400 tw-flex-shrink-0"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-4.5a.75.75 0 11-1.5 0v-1.5a.75.75 0 011.5 0v1.5zm0-4a.75.75 0 11-1.5 0V6a.75.75 0 011.5 0v3.5z"
                        clip-rule="evenodd"
                    />
                </svg>
                <!-- Message -->
                <p
                    v-if="documents.length < totalDocuments"
                    class="tw-text-yellow-700 tw-text-sm md:tw-text-base"
                >
                    Te rugăm să încarci documentele necesare înainte de a
                    continua.
                </p>
                <p v-else class="tw-text-yellow-700 tw-text-sm md:tw-text-base">
                    Incarca noi documente daca consideri ca necesita updatare!
                </p>
            </div>
            <!-- Button next to text -->
            <inertia-link
                :href="route('user.profile.edit-documents')"
                class="tw-inline-flex tw-items-center tw-bg-yellow-400 hover:tw-bg-yellow-500 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-yellow-300 tw-text-black tw-font-semibold tw-text-sm tw-px-4 tw-py-2 tw-rounded-lg tw-shadow tw-transition"
            >
                <div v-if="documents.length < totalDocuments">
                    Încarcă documente
                </div>
                <div v-else>Editeaza documente</div>
            </inertia-link>
        </div>

        <!-- Status Documente: afișat DOAR pe md+ (ascuns pe mobile) -->
        <div class="tw-hidden md:tw-flex tw-justify-between tw-items-start">
            <template v-for="type in allowedTypes" :key="type">
                <div
                    class="tw-flex-1 tw-mb-2 tw-flex tw-flex-col tw-items-center tw-mx-2"
                >
                    <!-- Label -->
                    <span class="tw-font-medium tw-text-gray-700">{{
                        labels[type]
                    }}</span>
                    <!-- Icons below -->
                    <span class="tw-mt-2">
                        <svg
                            v-if="documents.some((doc) => doc.type === type)"
                            class="tw-w-6 tw-h-6 tw-text-green-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 011.414-1.414L8.414 12.172l7.879-7.879a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <svg
                            v-else
                            class="tw-w-6 tw-h-6 tw-text-red-500"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </span>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    documents: {
        type: Array,
        required: true,
    },
    allowedTypes: {
        type: Array,
        required: true,
    },
    totalDocuments: {
        type: Number,
        required: true,
    },
    labels: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["upload"]);
</script>
