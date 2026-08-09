<template>
    <OwnerDashboardLayout>
        <div class="tw-px-6 tw-py-4">
            <h1 class="tw-text-3xl tw-font-bold tw-text-gray-800 tw-mb-2">
                Recenzii primite
            </h1>
            <p class="tw-text-gray-600 tw-text-sm">
                Aici poți vedea ratingul tău general, recenziile clienților și
                evaluările pe fiecare mașină închiriată.
            </p>
        </div>
        <RatingSummary
            :averageRating="averageRating"
            :totalReviews="nr_reviews"
            :ratingBreakdown="ratingBreakdown"
        />

        <div class="tw-mt-10">
            <h2
                class="tw-text-2xl tw-font-bold tw-text-center tw-text-gray-800 tw-mb-6"
            >
                Ratinguri pe mașini
            </h2>

            <div
                v-if="carRatings.length > 0"
                class="tw-grid md:tw-grid-cols-2 tw-gap-6 tw-mb-8"
            >
                <CarRatingCard
                    v-for="car in carRatings"
                    :key="car.car_id"
                    :car="car"
                />
            </div>

            <div
                v-else
                class="tw-flex tw-items-center tw-gap-2 tw-bg-yellow-50 tw-border tw-border-yellow-200 tw-text-yellow-800 tw-text-sm tw-font-medium tw-px-4 tw-py-3 tw-rounded-lg tw-mt-4"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="tw-h-5 tw-w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"
                    />
                </svg>
                Nu există încă ratinguri pentru nicio mașină.
            </div>
        </div>

        <ReusableTableStatus
            class="tw-px-2"
            title="Recenzii"
            description="Aici poți vizualiza toate review-urile tale. Poti cauta dupa client sau masina folosita!"
            :columns="[
                { key: 'client_name', label: 'Client' },
                { key: 'vehicle_name', label: 'Vehicul' },
                { key: 'rating', label: 'Rating', sort: true, rating: true },
                { key: 'reviewed_at', label: 'Dată', sort: true },
            ]"
            @sort-changed="sortTable"
            @search-changed="searchTable"
            :rows="reviews"
            :prevSearch="prevSearch"
            :searchable="true"
            :expandable="true"
            :pagination="true"
        />
    </OwnerDashboardLayout>
</template>
<script setup>
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import RatingSummary from "./RatingSummary.vue";
import CarRatingCard from "./CarRatingCard.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import ReusableTableStatus from "@/Components/ReusableTableStatus.vue";
const props = defineProps({
    averageRating: Number,
    carRatings: Array,
    nr_reviews: Number,
    reviews: Array,
    ratingBreakdown: Array,
    prevSearch: String,
});

const sort = ref({ key: "reviewed_at", order: "desc" });
const search = ref("");

function sortTable({ key, order }) {
    sort.value = { key, order };
    fetch();
}

function searchTable(newSearch) {
    search.value = newSearch;
    fetch();
}

function fetch() {
    router.get(
        route("user.reviews.index"),
        {
            key: sort.value.key,
            order: sort.value.order,
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}
</script>
