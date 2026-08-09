<template>
    <div class="tw-flex tw-flex-col">
        <div
            class="tw-bg-white tw-rounded-lg tw-shadow tw-p-4 tw-flex tw-flex-col md:tw-flex-row md:tw-items-center tw-gap-6"
        >
            <!-- Informații client -->
            <div class="tw-flex tw-items-center tw-space-x-4">
                <!-- Poza clientului -->
                <img
                    :src="imagePath('account.jpg')"
                    alt="Fotografie client"
                    class="tw-h-16 tw-w-16 tw-rounded-full tw-object-cover"
                />
                <!-- Date client -->
                <div>
                    <h3 class="tw-text-lg tw-font-semibold tw-text-gray-800">
                        {{ booking.client.name }}
                    </h3>
                    <p class="tw-text-sm tw-text-gray-500">
                        {{ booking.client.location }}
                    </p>
                    <p class="tw-text-xs tw-text-gray-400">
                        Membru din {{ booking.client.created_at }}
                    </p>
                    <div class="tw-mt-1 tw-flex tw-items-center tw-space-x-2">
                        <span class="tw-text-xs tw-text-gray-500"
                            >{{
                                booking.client.reservations_count
                            }}
                            rezervări</span
                        >
                        <span class="tw-flex tw-items-center">
                            <svg
                                v-for="n in 5"
                                :key="n"
                                class="tw-w-4 tw-h-4"
                                fill="currentColor"
                                :class="
                                    n <= booking.client.rating
                                        ? 'tw-text-yellow-400'
                                        : 'tw-text-gray-200'
                                "
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M9.049 2.927C9.324 2.21 10.676 2.21 10.951 2.927l1.286 3.68a1 1 0 00.95.69h3.862c.969 0 1.371 1.24.588 1.81l-3.124 2.27a1 1 0 00-.364 1.118l1.286 3.68c.275.718-.574 1.326-1.162.87l-3.124-2.27a1 1 0 00-1.176 0l-3.124 2.27c-.588.456-1.437-.152-1.162-.87l1.286-3.68a1 1 0 00-.364-1.118L2.338 8.107c-.783-.57-.38-1.81.588-1.81h3.862a1 1 0 00.95-.69l1.286-3.68z"
                                />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informații rezervare + acțiuni -->
            <div
                class="tw-flex-1 tw-flex tw-flex-col md:tw-flex-row md:tw-items-center md:tw-justify-between tw-gap-4"
            >
                <!-- Mașină + perioadă -->
                <div class="tw-space-y-2">
                    <!-- Mașina -->
                    <div class="tw-text-sm tw-text-gray-700">
                        <span class="tw-font-medium tw-text-gray-900"
                            >🚗 Mașină:</span
                        >
                        {{ booking.car.brand }} {{ booking.car.model }}
                        <span class="tw-text-gray-500"
                            >({{ booking.car.year }})</span
                        >
                    </div>

                    <!-- Perioada -->
                    <div class="tw-text-sm tw-text-gray-700">
                        <span class="tw-font-medium tw-text-gray-900"
                            >📅 Perioadă:</span
                        >
                        <span class="tw-ml-1 tw-font-semibold"
                            >{{ booking.days }} zile</span
                        >
                        <div class="tw-text-gray-600 tw-text-xs tw-mt-1">
                            {{ booking.start_date }} → {{ booking.end_date }}
                        </div>
                    </div>
                </div>

                <button
                    @click="toggle()"
                    class="tw-px-2 tw-py-1 tw-rounded-lg bg-slate-50 tw-cursor-pointer tw-flex tw-justify-end"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="tw-size-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                        />
                    </svg>
                </button>
            </div>
        </div>
        <transition name="fade">
            <div v-if="expanded" class="tw-mt-2">
                <!-- Detalii dinamice din backend -->
                <div
                    v-for="detail in booking.details"
                    :key="detail.label"
                    class="tw-flex tw-justify-between tw-bg-gray-50 tw-px-4 tw-py-2 tw-rounded-md"
                >
                    <span class="tw-font-medium tw-text-gray-700">
                        {{ detail.label }}
                    </span>
                    <span v-if="!detail.is_sensitive">
                        {{ detail.value }}
                    </span>
                    <span v-else>
                        {{ "★".repeat(5) }}
                    </span>
                </div>

                <!-- Butoane Aprobare / Respingere -->
                <div class="tw-flex tw-justify-end tw-space-x-2 tw-mt-2">
                    <button
                        @click="$emit('approve', booking.id)"
                        class="tw-px-4 tw-py-2 tw-bg-green-500 hover:tw-bg-green-600 tw-text-white tw-rounded-lg tw-transition"
                    >
                        Aprobare
                    </button>
                    <button
                        @click="$emit('reject', booking.id)"
                        class="tw-px-4 tw-py-2 tw-bg-red-500 hover:tw-bg-red-600 tw-text-white tw-rounded-lg tw-transition"
                    >
                        Respingere
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    booking: {
        type: Object,
        required: true,
    },
});
const expanded = ref(false);

const emit = defineEmits(["approve", "reject"]);

const toggle = () => {
    expanded.value = !expanded.value;
};
</script>
<style>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
