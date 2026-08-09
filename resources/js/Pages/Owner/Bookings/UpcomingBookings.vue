<template>
    <OwnerDashboardLayout>
        <h1
            class="tw-text-2xl tw-font-semibold tw-text-gray-800 tw-mb-12 tw-mt-4 tw-flex tw-items-center tw-space-x-3"
        >
            <svg
                class="tw-w-6 tw-h-6 tw-text-blue-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-2 4H6a2 2 0 01-2-2V7a2 2 0 012-2h1m10 16h1a2 2 0 002-2v-5"
                />
            </svg>
            <span>Rezervări în așteptare</span>
        </h1>
        <div class="tw-space-y-6">
            <CardBooking
                v-for="b in list"
                :key="b.id"
                :booking="b"
                @approve="approve(b.id)"
                @reject="reject(b.id)"
            />
            <p v-if="!list.length" class="tw-text-center tw-text-gray-500">
                Nu mai există rezervări viitoare.
            </p>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import CardBooking from "./CardBooking.vue";

const props = defineProps({
    upcomingList: Array,
});

const list = ref([...props.upcomingList]);

function approve(id) {
    router.post(
        route("user.bookings.approve", id),
        { status: "accepted" },
        {
            onSuccess: () => {
                list.value = list.value.filter((b) => b.id !== id);
            },
        }
    );
}

function reject(id) {
    router.post(
        route("user.bookings.reject", id),
        { status: "cancelled" },
        {
            onSuccess: () => {
                list.value = list.value.filter((b) => b.id !== id);
            },
        }
    );
}
</script>
