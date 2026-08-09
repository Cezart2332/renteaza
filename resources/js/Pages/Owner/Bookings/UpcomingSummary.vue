<template>
    <div
        class="tw-bg-blue-50 tw-border-l-4 tw-border-blue-400 tw-p-6 tw-rounded-lg tw-flex tw-items-center tw-justify-between"
    >
        <div>
            <h2 class="tw-text-lg tw-font-semibold tw-text-blue-800">
                Rezervări viitoare
            </h2>
            <p class="tw-mt-1 tw-text-sm tw-text-blue-700">
                Ai
                <span class="tw-font-medium">{{ count }}</span> rezervare<span
                    v-if="count !== 1"
                    >i</span
                >
                viitoare.
            </p>
        </div>
        <inertia-link
            :href="route('user.bookings.upcomingBooking')"
            class="tw-inline-flex tw-items-center tw-bg-blue-500 hover:tw-bg-blue-600 tw-text-white tw-font-semibold tw-px-4 tw-py-2 tw-rounded-lg tw-shadow"
        >
            Vezi detalii
            <svg
                class="tw-w-4 tw-h-4 tw-ml-2"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M10.293 15.707a1 1 0 010-1.414L13.586 11H4a1 1 0 110-2h9.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
                    clip-rule="evenodd"
                />
            </svg>
        </inertia-link>
    </div>
</template>

<script setup>
const props = defineProps({
    count: { type: Number, required: true },
});
</script>
