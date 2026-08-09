<template>
    <OwnerDashboardLayout>
        <div
            class="tw-max-w-3xl tw-mx-auto tw-pr-4 tw-pl-6 tw-rounded-xl "
        >
            <div class="tw-mb-4 tw-flex tw-justify-end">
                <inertia-link
                    :href="
                        route('user.calendar.show', {
                            vehicleSlug: booking.vehicle.slug,
                        })
                    "
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-bg-gray-900 tw-text-white tw-px-4 tw-py-2 tw-text-sm tw-shadow hover:tw-shadow-md active:tw-scale-[0.98] tw-transition"
                    preserve-scroll
                >
                    <!-- săgeată înapoi -->
                    <span
                        class="tw-inline-flex tw-items-center tw-justify-center tw-w-5 tw-h-5 tw-rounded-full tw-bg-white/15"
                        >←</span
                    >
                    Înapoi la calendar
                </inertia-link>
            </div>
            <h1 class="tw-text-2xl tw-font-bold tw-mb-4">Detalii rezervare</h1>
            <div
                class="tw-inline-block tw-bg-gray-1 00 tw-text-gray-800 tw-rounded-lg tw-px-4 tw-py-2 tw-text-lg tw-font-medium tw-mb-6"
            >
                {{ booking.vehicle.brand }} {{ booking.vehicle.model }} –
                {{ booking.vehicle.year }}
            </div>

            <!-- Status -->
            <div
                class="tw-inline-block tw-ml-4 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-semibold tw-mb-6"
                :class="statusClass"
            >
                {{ booking.status }}
            </div>

            <!-- Detalii principale -->
            <div class="tw-space-y-4">
                <!-- Secțiune Client -->
                <div class="tw-space-y-2">
                    <h2 class="tw-text-lg tw-font-semibold tw-text-gray-800">
                        Datele clientului
                    </h2>

                    <div>
                        <div class="tw-text-gray-500">Nume</div>
                        <div class="tw-font-medium">
                            {{ booking.client.name }}
                        </div>
                    </div>

                    <div>
                        <div class="tw-text-gray-500">Email</div>
                        <div class="tw-font-medium">
                            {{ booking.client.email }}
                        </div>
                    </div>

                    <div>
                        <div class="tw-text-gray-500">Telefon</div>
                        <div class="tw-font-medium">
                            <a
                                :href="`tel:${booking.client.phone}`"
                                class="tw-text-blue-600 hover:tw-underline"
                            >
                                {{ booking.client.phone }}
                            </a>
                        </div>
                    </div>
                </div>
                <h2 class="tw-text-lg tw-font-semibold tw-text-gray-800">
                    Perioada rezervării
                </h2>
                <div class="tw-grid tw-grid-cols-2 tw-gap-6">
                    <div>
                        <div class="tw-text-gray-500">Check-in</div>
                        <div class="tw-font-medium">
                            {{ formatDate(booking.start_date) }}
                        </div>
                    </div>
                    <div>
                        <div class="tw-text-gray-500">Check-out</div>
                        <div class="tw-font-medium">
                            {{ formatDate(booking.end_date) }}
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="tw-text-lg tw-font-semibold tw-text-gray-800">
                        Tip închiriere
                    </h2>
                    <div class="tw-font-medium">{{ rentalTypeLabel }}</div>
                </div>

                <div>
                    <h2 class="tw-text-lg tw-font-semibold tw-text-gray-800">
                        Locație preluare
                    </h2>
                    <div class="tw-font-medium">
                        {{ booking.pickup_location.name }}
                    </div>
                </div>

                <!-- Prețuri -->
                <h2 class="tw-text-lg tw-font-semibold tw-text-gray-800">
                    Costuri și plăți
                </h2>

                <div class="tw-grid tw-grid-cols-2 tw-gap-6">
                    <div>
                        <div class="tw-text-gray-500">Preț pe zi</div>
                        <div class="tw-font-medium">
                            {{ booking.price_per_day }} RON
                        </div>
                    </div>
                    <div>
                        <div class="tw-text-gray-500">Total</div>
                        <div class="tw-font-medium">
                            {{ booking.total_price }} RON
                            <span class="tw-text-gray-500 tw-ml-2">
                                ({{ days }} {{ days === 1 ? "zi" : "zile" }})
                            </span>
                        </div>
                    </div>
                    <!-- 
                    <div>
                        <div class="tw-text-gray-500">Depozit garanție</div>
                        <div class="tw-font-medium">
                            {{ booking.security_deposit }} RON
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { computed } from "vue";

const props = defineProps({
    booking: Object,
    days: Number,
});

function formatDate(iso) {
    const d = new Date(iso);
    return d.toLocaleDateString("ro-RO", {
        weekday: "short",
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

const rentalTypeLabel = computed(() => {
    if (!props.booking?.rental_type?.name) return "";

    return props.booking.rental_type.name
        .split("_") // sparge după underscore dacă există
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1)) // capitalize
        .join(" "); // unește cu spațiu
});

const statusClass = computed(() => {
    switch (props.booking.status) {
        case "accepted":
            return "tw-bg-green-100 tw-text-green-800";
        case "pending":
            return "tw-bg-yellow-100 tw-text-yellow-800";
        case "rejected":
            return "tw-bg-red-100 tw-text-red-800";
        default:
            return "tw-bg-gray-100 tw-text-gray-800";
    }
});
</script>
