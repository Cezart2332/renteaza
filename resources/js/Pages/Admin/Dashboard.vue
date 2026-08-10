<script setup>
/**
 * Panoul de administrare.
 *
 * Varianta anterioara primea toti utilizatorii cu toate relatiile si calcula
 * statistici in frontend, dar nu oferea nicio actiune: nu se vedea ce asteapta
 * aprobare si nu exista drum catre masini. Acum primeste doar agregate
 * (AdminController::dashboard) si duce direct in listele filtrate.
 */
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            usersTotal: 0,
            usersPending: 0,
            vehiclesTotal: 0,
            vehiclesPending: 0,
        }),
    },
});

const needsAttention = computed(
    () => props.stats.vehiclesPending + props.stats.usersPending
);

const cards = computed(() => [
    {
        key: "vehiclesPending",
        label: "Mașini în așteptare",
        value: props.stats.vehiclesPending,
        href: route("admin.vehicles.index", { filters: { status: "pending" } }),
        cta: "Aprobă mașini",
        urgent: props.stats.vehiclesPending > 0,
    },
    {
        key: "usersPending",
        label: "Utilizatori în așteptare",
        value: props.stats.usersPending,
        href: route("admin.users.index", { filters: { status: "pending" } }),
        cta: "Vezi utilizatorii",
        urgent: props.stats.usersPending > 0,
    },
    {
        key: "vehiclesTotal",
        label: "Total mașini",
        value: props.stats.vehiclesTotal,
        href: route("admin.vehicles.index"),
        cta: "Toate mașinile",
        urgent: false,
    },
    {
        key: "usersTotal",
        label: "Total utilizatori",
        value: props.stats.usersTotal,
        href: route("admin.users.index"),
        cta: "Toți utilizatorii",
        urgent: false,
    },
]);
</script>

<template>
    <Head title="Panou administrare" />

    <AdminDashboardLayout>
        <div class="tw-space-y-8 tw-pb-8">
            <div>
                <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">
                    Panou administrare
                </h1>
                <p class="tw-mt-1 tw-text-[15px] tw-text-gray-500">
                    <template v-if="needsAttention">
                        {{ needsAttention }}
                        {{ needsAttention === 1 ? "element așteaptă" : "elemente așteaptă" }}
                        aprobarea ta.
                    </template>
                    <template v-else>
                        Nimic nu așteaptă aprobare.
                    </template>
                </p>
            </div>

            <div class="tw-grid tw-grid-cols-1 tw-gap-5 sm:tw-grid-cols-2 xl:tw-grid-cols-4">
                <div
                    v-for="card in cards"
                    :key="card.key"
                    :class="[
                        'tw-flex tw-flex-col tw-justify-between tw-rounded-2xl tw-border tw-bg-white tw-p-6',
                        card.urgent
                            ? 'tw-border-amber-300 tw-bg-amber-50/40'
                            : 'tw-border-gray-200',
                    ]"
                >
                    <div>
                        <p class="tw-text-sm tw-font-medium tw-text-gray-500">
                            {{ card.label }}
                        </p>
                        <p
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

            <div class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-6">
                <h2 class="tw-text-[17px] tw-font-semibold tw-text-gray-900">
                    Cum funcționează aprobarea
                </h2>
                <ul class="tw-mt-3 tw-space-y-2 tw-text-[14px] tw-text-gray-600">
                    <li>
                        O mașină adăugată de un proprietar pornește cu statusul
                        <strong>în așteptare</strong> și
                        <strong>nu apare pe site</strong> până nu o aprobi.
                    </li>
                    <li>
                        Aprobarea o faci din lista de mașini, direct din tabel sau
                        după ce îi verifici documentele.
                    </li>
                    <li>
                        O mașină respinsă dispare din listările publice, dar rămâne
                        în contul proprietarului.
                    </li>
                </ul>
            </div>
        </div>
    </AdminDashboardLayout>
</template>
