<script setup>
/**
 * Rezumatul contului de proprietar / client.
 *
 * Inainte pagina randa doar <OwnerDashboardLayout /> fara nimic inauntru, deci
 * /user/dashboard era un ecran gol.
 */
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    selectedRole: { type: String, default: "Client" },
    stats: {
        type: Object,
        default: () => ({
            vehiclesTotal: 0,
            vehiclesPending: 0,
            bookingsAsOwner: 0,
            bookingsAsClient: 0,
        }),
    },
});

// Layout-ul nu emite update:selectedRole, dar comutatorul salveaza rolul in
// sesiune (user.dashboard.setRole), iar HandleInertiaRequests il partajeaza
// inapoi ca 'selectedRole'. Citim de acolo, ca sa ramana sincronizat.
const page = usePage();
const isOwner = computed(
    () => (page.props.selectedRole ?? props.selectedRole) === "Proprietar"
);

const cards = computed(() =>
    isOwner.value
        ? [
              {
                  label: "Mașinile mele",
                  value: props.stats.vehiclesTotal,
                  href: route("user.cars.index"),
                  cta: "Vezi mașinile",
              },
              {
                  label: "În așteptarea aprobării",
                  value: props.stats.vehiclesPending,
                  href: route("user.cars.index"),
                  cta: "Verifică statusul",
                  urgent: props.stats.vehiclesPending > 0,
              },
              {
                  label: "Rezervări primite",
                  value: props.stats.bookingsAsOwner,
                  href: route("user.bookings.index"),
                  cta: "Vezi rezervările",
              },
          ]
        : [
              {
                  label: "Rezervările mele",
                  value: props.stats.bookingsAsClient,
                  href: route("user.client_bookings.index"),
                  cta: "Vezi rezervările",
              },
              {
                  label: "Caută o mașină",
                  value: null,
                  href: route("car.index"),
                  cta: "Deschide catalogul",
              },
          ]
);
</script>

<template>
    <Head title="Panoul meu" />

    <OwnerDashboardLayout :selected-role="selectedRole">
        <div class="tw-space-y-6 tw-p-6 sm:tw-p-8">
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">
                    {{ isOwner ? "Panoul de proprietar" : "Panoul meu" }}
                </h1>
                <p class="tw-mt-1 tw-text-[15px] tw-text-gray-500">
                    {{
                        isOwner
                            ? "Mașinile listate și rezervările primite."
                            : "Rezervările tale și căutarea de mașini."
                    }}
                </p>
            </div>

            <div
                v-if="isOwner && stats.vehiclesPending"
                class="tw-rounded-xl tw-border tw-border-amber-300 tw-bg-amber-50 tw-px-5 tw-py-4"
            >
                <p class="tw-text-sm tw-font-semibold tw-text-amber-900">
                    {{ stats.vehiclesPending }}
                    {{
                        stats.vehiclesPending === 1
                            ? "mașină așteaptă"
                            : "mașini așteaptă"
                    }}
                    aprobarea administratorului.
                </p>
                <p class="tw-mt-1 tw-text-sm tw-text-amber-800">
                    Până atunci nu apar în căutările clienților.
                </p>
            </div>

            <div class="tw-grid tw-gap-4 sm:tw-grid-cols-2 lg:tw-grid-cols-3">
                <div
                    v-for="card in cards"
                    :key="card.label"
                    :class="[
                        'tw-flex tw-flex-col tw-justify-between tw-rounded-2xl tw-border tw-bg-white tw-p-6',
                        card.urgent ? 'tw-border-amber-300' : 'tw-border-gray-200',
                    ]"
                >
                    <div>
                        <p class="tw-text-sm tw-font-medium tw-text-gray-500">
                            {{ card.label }}
                        </p>
                        <p
                            v-if="card.value !== null"
                            :class="[
                                'tw-mt-2 tw-text-3xl tw-font-bold',
                                card.urgent ? 'tw-text-amber-700' : 'tw-text-gray-900',
                            ]"
                        >
                            {{ card.value }}
                        </p>
                    </div>
                    <Link
                        :href="card.href"
                        class="tw-mt-5 tw-inline-flex tw-items-center tw-gap-1.5 tw-text-sm tw-font-semibold tw-text-[var(--theme2)] hover:tw-underline"
                    >
                        {{ card.cta }} →
                    </Link>
                </div>
            </div>
        </div>
    </OwnerDashboardLayout>
</template>
