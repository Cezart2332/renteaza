<template>
    <div
        aria-live="assertive"
        class="tw-pointer-events-none tw-fixed tw-inset-24 tw-flex tw-items-end tw-px-4 tw-py-6 sm:tw-items-start sm:tw-p-6 tw-mt-10"
    >
        <div class="tw-flex tw-w-full tw-flex-col tw-items-center tw-space-y-4">
            <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
            <transition
                enter-active-class="tw-transform tw-ease-out tw-duration-300 tw-transition"
                enter-from-class="tw-translate-y-2 tw-opacity-0 sm:tw-translate-y-0 sm:tw-translate-x-2"
                enter-to-class="tw-translate-y-0 tw-opacity-100 sm:tw-translate-x-0"
                leave-active-class="tw-transition tw-ease-in tw-duration-100"
                leave-from-class="tw-opacity-100"
                leave-to-class="tw-opacity-0"
            >
                <div
                    v-if="show"
                    class="tw-pointer-events-auto tw-w-full tw-max-w-sm tw-overflow-hidden tw-rounded-lg tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5"
                >
                    <div class="tw-p-4">
                        <div class="tw-flex tw-items-start">
                            <template v-if="$page.props.message">
                                <div class="tw-flex-shrink-0">
                                    <CheckCircleIcon
                                        class="tw-h-6 tw-w-6 tw-text-green-400"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="tw-ml-3 tw-w-0 tw-flex-1 tw-pt-0.5">
                                    <p
                                        class="tw-text-sm tw-font-medium tw-text-gray-900"
                                    >
                                        {{ $page.props.message }}
                                    </p>
                                </div>
                            </template>
                            <template v-if="$page.props.errorMessage">
                                <div class="tw-flex-shrink-0">
                                    <XCircleIcon
                                        class="tw-h-6 tw-w-6 tw-text-red-400"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="tw-ml-3 tw-w-0 tw-flex-1 tw-pt-0.5">
                                    <p
                                        class="tw-text-sm tw-font-medium tw-text-gray-900"
                                    >
                                        {{ errorMessage }}
                                    </p>
                                </div>
                            </template>
                            <div class="tw-ml-4 tw-flex tw-flex-shrink-0">
                                <button
                                    type="button"
                                    @click="show = false"
                                    class="tw-inline-flex tw-rounded-md tw-bg-white tw-text-gray-400 hover:tw-text-gray-500 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-ring-offset-2"
                                >
                                    <span class="tw-sr-only">Close</span>
                                    <XMarkIcon
                                        class="tw-h-5 tw-w-5"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import {
    CheckCircleIcon,
    XCircleIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

const page = usePage();
const errorMessage = computed(() => page.props.errorMessage);
const message = computed(() => page.props.message);
const show = ref(false);
const content = ref("");

onMounted(() => {
    // Router event fires on successful response from the server
    router.on("success", () => {
        if (message.value) {
            content.value = message.value;
            show.value = true;
        } else if (errorMessage.value) {
            content.value = errorMessage.value;
            show.value = true;
        }

        setTimeout(() => {
            show.value = false;
            content.value = "";
        }, 3500);
    });
});
</script>

<style scoped>
@tailwind base;
@tailwind components;
@tailwind utilities;
</style>
