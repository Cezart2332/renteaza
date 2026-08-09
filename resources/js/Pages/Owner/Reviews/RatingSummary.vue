<template>
    <div
        class="tw-bg-white tw-rounded-xl tw-shadow-md tw-p-6 tw-mb-6 tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4 tw-items-center"
    >
        <!-- Scor Recenzii -->
        <div class="tw-text-center tw-border-r md:tw-border-r md:tw-pr-4">
            <div class="tw-text-yellow-400 tw-text-3xl tw-font-bold">★★★★★</div>
            <div class="tw-text-4xl tw-font-bold tw-text-gray-800 tw-mt-1">
                {{ averageRating }}/5
            </div>
            <div class="tw-text-sm tw-text-gray-500">Scor recenzii</div>
        </div>

        <!-- Total Recenzii -->
        <div class="tw-text-center tw-border-r md:tw-border-r md:tw-pr-4">
            <div class="tw-text-4xl tw-font-bold tw-text-gray-800">
                {{ totalReviews }}
            </div>
            <div class="tw-text-sm tw-text-gray-500">Total recenzii</div>
        </div>

        <!-- Distribuție stele -->
        <div class="tw-w-full">
            <div
                v-for="n in [5, 4, 3, 2, 1]"
                :key="n"
                class="tw-flex tw-items-center tw-gap-2 tw-mb-1"
            >
                <span v-if="n == 1" class="tw-w-12 tw-text-sm"
                    >{{ n }} stea</span
                >
                <span v-else class="tw-w-12 tw-text-sm">{{ n }} stele</span>
                <div class="tw-w-full tw-bg-gray-200 tw-rounded-full tw-h-2">
                    <div
                        class="tw-h-2 tw-rounded-full tw-bg-yellow-400"
                        :style="{ width: ratingBreakdown[n] + '%' }"
                    ></div>
                </div>
                <span class="tw-text-sm tw-text-gray-500 tw-w-10 tw-text-right">
                    {{ ratingBreakdown[n] }}%
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    averageRating: Number,
    totalReviews: Number,
    ratingBreakdown: Object,
});
</script>
