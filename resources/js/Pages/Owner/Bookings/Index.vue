<template>
    <OwnerDashboardLayout>
        <div class="tw-px-8">
            <h1 class="tw-text-2xl tw-font-semibold tw-text-gray-800 tw-mb-8 tw-mt-4">
                Prezentare generală a rezervărilor
            </h1>

            <!-- CTA Stripe (vizibil doar dacă nu e conectat) -->
            <div v-if="!ownerStripeConnected" class="tw-mb-6">
                <inertia-link :href="route('user.payments.connect.start')"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-rounded-xl tw-border tw-bg-white hover:tw-bg-gray-50 tw-text-sm tw-font-medium">
                    Conectează Stripe
                </inertia-link>
            </div>

            <!-- Desktop (grilă cu carduri) -->
            <div class="tw-hidden md:tw-grid md:tw-grid-cols-3 tw-gap-6">
                <StatusBookings :stats="stats" />
            </div>

            <!-- Mobil (componenta compactă) -->
            <MobileStatusBookings class="md:tw-hidden" :stats="stats" />

            <div class="tw-mt-12">
                <UpcomingSummary :count="stats.Pending.current" />
            </div>
            <ReusableTableStatus class="tw-px-2" title="Rezervări"
                description="Aici poți vizualiza toate rezervarile tale. Poti cauta dupa client sau masina folosita!"
                :columns="[
                    { key: 'client', label: 'Client' },
                    { key: 'car', label: 'Mașină' },
                    { key: 'start', label: 'Data începerii', sort: true },
                    { key: 'end', label: 'Data finalizării' },
                    { key: 'status', label: 'Status', status: true },
                ]" :rows="rows" :searchable="true" :pagination="true" :expandable="true" @sort-changed="sortTable"
                @search-changed="searchTable" :prevSearch="prevSearch" />
        </div>
    </OwnerDashboardLayout>
</template>
<script setup>
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import StatusBookings from "./StatusBookings.vue";
import UpcomingSummary from "./UpcomingSummary.vue";
import ReusableTableStatus from "@/Components/ReusableTableStatus.vue";
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import MobileStatusBookings from "@/Pages/Client/Bookings/MobileStatusBookings.vue";

defineProps({
    stats: Array,
    upcomingList: Array,
    rows: Array,
    prevSearch: String,
});

const ownerStripeConnected = computed(() => {
    return !!(usePage().props?.user?.stripe_account_id)
})

const sort = ref({ order: "desc" });
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
        route("user.bookings.index"),
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
