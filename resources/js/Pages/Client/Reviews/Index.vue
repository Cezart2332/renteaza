<template>
    <OwnerDashboardLayout>
        <div v-if="unreviewedVehicles?.length" class="tw-mb-6">
            <div class="tw-flex tw-flex-col  tw-justify-between tw-mb-3 tw-px-2">
                <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900">Lasă o recenzie</h2>
                <span class="tw-text-sm tw-text-gray-500">
                    Mașini din rezervările tale fără recenzie
                </span>
            </div>

            <div class="tw-flex tw-gap-4 tw-overflow-x-auto tw-px-2 tw-py-2">
                <div v-for="v in unreviewedVehicles" :key="v.id"
                    class="tw-flex tw-w-64 tw-shrink-0 tw-flex-col tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-shadow-sm hover:tw-shadow-md tw-transition">
                    <div class="tw-aspect-[16/9] tw-w-full tw-overflow-hidden tw-rounded-t-xl tw-bg-gray-100">
                        <img :src="imageFromAwsPublic(v.cover)" :alt="`${v.brand} ${v.model}`"
                            class="tw-h-full tw-w-full tw-object-cover" />
                    </div>
                    <div class="tw-p-3 tw-flex tw-flex-col tw-gap-1">
                        <div class="tw-font-semibold tw-text-gray-900">
                            {{ v.brand }} {{ v.model }}
                        </div>
                        <div class="tw-text-xs tw-text-gray-500">
                            Proprietar: {{ v.owner || '-' }}
                        </div>
                        <inertia-link :href="route('user.client_reviews.create', { vehicleSlug: v.slug })"
                            class="tw-mt-2 tw-inline-flex tw-w-full tw-items-center tw-justify-center tw-rounded-lg tw-bg-indigo-600 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700 tw-transition">
                            Scrie recenzie
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>

        <ReusableTableStatus class="tw-px-2" title="Recenziile mele"
            description="Aici poți vizualiza toate review-urile tale. Poti cauta dupa proprietar sau masina folosita!"
            :columns="[
                { key: 'owner_name', label: 'Proprietar' },
                { key: 'car_name', label: 'Mașină' },
                { key: 'rating', label: 'Rating', sort: true, rating: true },
                { key: 'reviewed_at', label: 'Dată', sort: true },
            ]" @sort-changed="sortTable" @search-changed="searchTable" :rows="reviews" :prevSearch="prevSearch"
            :searchable="true" :expandable="true" :pagination="true" />

    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import ReusableTableStatus from "@/Components/ReusableTableStatus.vue";
const props = defineProps({
    reviews: Array,
    prevSearch: String,
    unreviewedVehicles: Array,
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
        route("user.client_reviews.index"),
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
