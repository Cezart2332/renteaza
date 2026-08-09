<template>
    <div
        class="tw-p-5 tw-rounded-xl tw-shadow-md tw-bg-white tw-border hover:tw-shadow-lg tw-transition"
    >
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
            <h3 class="tw-text-xl tw-font-bold tw-text-gray-800">{{ car.car_model }}</h3>
        </div>
        <span class="tw-text-sm tw-text-white tw-bg-blue-500 tw-px-2 tw-py-1 tw-rounded-full">
            {{ car.reviews_count }} recenzii
        </span>
        <div class="tw-flex tw-items-center tw-space-x-2">
            <div class="tw-flex tw-text-yellow-400">
                <template v-for="i in 5">
                    <svg
                        v-if="i <= Math.floor(car.average_rating)"
                        :key="i"
                        class="tw-h-5 tw-w-5 tw-fill-current"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 15l-5.878 3.09L5.7 12.18.82 8.91l6.03-.879L10 2.5l3.15 5.531 6.03.88-4.88 3.268 1.578 5.91z"
                        />
                    </svg>

                    <svg
                        v-else-if="i - car.average_rating < 1"
                        :key="'half-' + i"
                        class="tw-h-5 tw-w-5 tw-text-yellow-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <defs>
                            <linearGradient
                                id="halfGrad"
                                x1="0"
                                x2="100%"
                                y1="0"
                                y2="0"
                            >
                                <stop offset="50%" stop-color="currentColor" />
                                <stop offset="50%" stop-color="transparent" />
                            </linearGradient>
                        </defs>
                        <path
                            fill="url(#halfGrad)"
                            d="M10 15l-5.878 3.09L5.7 12.18.82 8.91l6.03-.879L10 2.5l3.15 5.531 6.03.88-4.88 3.268 1.578 5.91z"
                        />
                    </svg>

                    <svg
                        v-else
                        :key="'empty-' + i"
                        class="tw-h-5 tw-w-5 tw-text-gray-300"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M10 15l-5.878 3.09L5.7 12.18.82 8.91l6.03-.879L10 2.5l3.15 5.531 6.03.88-4.88 3.268 1.578 5.91z"
                        />
                    </svg>
                </template>
            </div>
            <span class="tw-text-gray-600 tw-font-semibold tw-text-lg">
                {{ car.average_rating }}/5
            </span>
        </div>
    </div>
</template>

<script setup>
defineProps({
    car: Object,
});
</script>
