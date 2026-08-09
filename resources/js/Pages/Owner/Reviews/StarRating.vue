<template>
    <div class="tw-flex tw-items-center tw-text-yellow-400">
        <template v-for="i in 5">
            <svg
                v-if="i <= Math.floor(rating)"
                :key="i"
                class="tw-h-5 tw-w-5 tw-fill-current"
                viewBox="0 0 20 20"
            >
                <path
                    d="M10 15l-5.878 3.09L5.7 12.18.82 8.91l6.03-.879L10 2.5l3.15 5.531 6.03.88-4.88 3.268 1.578 5.91z"
                />
            </svg>
            <svg
                v-else-if="i - rating < 1"
                :key="'half-' + i"
                class="tw-h-5 tw-w-5 tw-text-yellow-400"
                viewBox="0 0 20 20"
            >
                <defs>
                    <linearGradient
                        id="halfStar"
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
                    fill="url(#halfStar)"
                    d="M10 15l-5.878 3.09L5.7 12.18.82 8.91l6.03-.879L10 2.5l3.15 5.531 6.03.88-4.88 3.268 1.578 5.91z"
                />
            </svg>
            <svg
                v-else
                :key="'empty-' + i"
                class="tw-h-5 tw-w-5 tw-text-gray-300"
                viewBox="0 0 20 20"
            >
                <path
                    d="M10 15l-5.878 3.09L5.7 12.18.82 8.91l6.03-.879L10 2.5l3.15 5.531 6.03.88-4.88 3.268 1.578 5.91z"
                />
            </svg>
        </template>
    </div>
</template>

<script setup>
defineProps({
    rating: Number,
});
</script>
