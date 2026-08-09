<template>
    <AdminDashboardLayout>
        <div class="tw-space-y-8 tw-pb-8">
            <!-- Header -->
            <div
                class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-end tw-justify-between tw-gap-4"
            >
                <div>
                    <h1 class="tw-text-2xl tw-font-semibold tw-text-gray-900">
                        Panou administrare
                    </h1>
                    <p class="tw-text-sm tw-text-gray-500">
                        Privire de ansamblu asupra utilizatorilor, mașinilor și
                        documentelor.
                    </p>
                </div>

                <!-- Quick actions -->
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <inertia-link
                        :href="route('admin.users.index')"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-lg tw-bg-indigo-600 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700"
                    >
                        👥 Gestionează utilizatori
                    </inertia-link>

                    <!-- dacă nu ai rutele astea încă, lasă # sau comentează -->
                    <inertia-link
                        v-if="hasRoute('admin.vehicles.index')"
                        :href="route('admin.vehicles.index')"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-3 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50"
                    >
                        🚗 Toate mașinile
                    </inertia-link>
                </div>
            </div>

            <!-- Stat cards -->
            <div
                class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 xl:tw-grid-cols-4 tw-gap-5"
            >
                <div
                    class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-5 tw-shadow-sm"
                >
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p
                                class="tw-text-xs tw-font-medium tw-text-gray-500"
                            >
                                Utilizatori
                            </p>
                            <p
                                class="tw-mt-1 tw-text-2xl tw-font-semibold tw-text-gray-900"
                            >
                                {{ totalUsers }}
                            </p>
                        </div>
                        <div
                            class="tw-h-10 tw-w-10 tw-rounded-xl tw-bg-indigo-50 tw-flex tw-items-center tw-justify-center"
                        >
                            👤
                        </div>
                    </div>
                    <p class="tw-mt-3 tw-text-xs tw-text-gray-500">
                        {{ usersWithCars }} au cel puțin o mașină
                    </p>
                </div>

                <div
                    class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-5 tw-shadow-sm"
                >
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p
                                class="tw-text-xs tw-font-medium tw-text-gray-500"
                            >
                                Mașini în platformă
                            </p>
                            <p
                                class="tw-mt-1 tw-text-2xl tw-font-semibold tw-text-gray-900"
                            >
                                {{ totalVehicles }}
                            </p>
                        </div>
                        <div
                            class="tw-h-10 tw-w-10 tw-rounded-xl tw-bg-emerald-50 tw-flex tw-items-center tw-justify-center"
                        >
                            🚗
                        </div>
                    </div>
                    <p class="tw-mt-3 tw-text-xs tw-text-gray-500">
                        Medie: {{ avgVehiclesPerUser }} / utilizator
                    </p>
                </div>

                <div
                    class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-5 tw-shadow-sm"
                >
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p
                                class="tw-text-xs tw-font-medium tw-text-gray-500"
                            >
                                Documente încărcate
                            </p>
                            <p
                                class="tw-mt-1 tw-text-2xl tw-font-semibold tw-text-gray-900"
                            >
                                {{ totalDocs }}
                            </p>
                        </div>
                        <div
                            class="tw-h-10 tw-w-10 tw-rounded-xl tw-bg-amber-50 tw-flex tw-items-center tw-justify-center"
                        >
                            📄
                        </div>
                    </div>
                    <p class="tw-mt-3 tw-text-xs tw-text-gray-500">
                        {{ verifiedDocs }} verificate
                    </p>
                </div>

                <div
                    class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-5 tw-shadow-sm"
                >
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div>
                            <p
                                class="tw-text-xs tw-font-medium tw-text-gray-500"
                            >
                                Rată verificare
                            </p>
                            <p
                                class="tw-mt-1 tw-text-2xl tw-font-semibold tw-text-gray-900"
                            >
                                {{ verificationRate }}%
                            </p>
                        </div>
                        <div
                            class="tw-h-10 tw-w-10 tw-rounded-xl tw-bg-blue-50 tw-flex tw-items-center tw-justify-center"
                        >
                            ✅
                        </div>
                    </div>
                    <div
                        class="tw-mt-3 tw-h-2 tw-w-full tw-rounded-full tw-bg-gray-100"
                    >
                        <div
                            class="tw-h-2 tw-rounded-full tw-bg-blue-600"
                            :style="{ width: verificationRate + '%' }"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- 2 coloane: Top Owners + Utilizatori recenți -->
            <div class="tw-grid tw-grid-cols-1 xl:tw-grid-cols-2 tw-gap-6">
                <!-- Top proprietari -->
                <div
                    class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-5 tw-shadow-sm"
                >
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-mb-4"
                    >
                        <h3
                            class="tw-text-base tw-font-semibold tw-text-gray-900"
                        >
                            Top proprietari (după nr. mașini)
                        </h3>
                        <span class="tw-text-xs tw-text-gray-500"
                            >Top {{ topOwners.length }}</span
                        >
                    </div>

                    <div class="tw-space-y-4">
                        <div
                            v-for="(u, idx) in topOwners"
                            :key="u.id || idx"
                            class="tw-flex tw-items-center tw-justify-between tw-gap-4"
                        >
                            <div
                                class="tw-flex tw-items-center tw-gap-3 tw-min-w-0"
                            >
                                <div
                                    class="tw-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-full tw-bg-gray-100 tw-text-sm tw-font-semibold tw-text-gray-700"
                                >
                                    {{ initials(u.name || u.email) }}
                                </div>
                                <div class="tw-min-w-0">
                                    <p
                                        class="tw-truncate tw-text-sm tw-font-medium tw-text-gray-900"
                                    >
                                        {{ u.name || u.email }}
                                    </p>
                                    <p class="tw-text-xs tw-text-gray-500">
                                        {{ vehiclesOf(u).length }} mașini
                                    </p>
                                </div>
                            </div>

                            <div class="tw-flex-1 tw-hidden sm:tw-block">
                                <div
                                    class="tw-h-2 tw-w-full tw-rounded-full tw-bg-gray-100"
                                >
                                    <div
                                        class="tw-h-2 tw-rounded-full tw-bg-emerald-500"
                                        :style="{
                                            width: ownerBarWidth(u) + '%',
                                        }"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="!topOwners.length"
                            class="tw-text-sm tw-text-gray-500"
                        >
                            Nu există date.
                        </div>
                    </div>
                </div>

                <!-- Utilizatori recenți -->
                <div
                    class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-5 tw-shadow-sm"
                >
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-mb-4"
                    >
                        <h3
                            class="tw-text-base tw-font-semibold tw-text-gray-900"
                        >
                            Utilizatori recenți
                        </h3>
                        <inertia-link
                            :href="route('admin.users.index')"
                            class="tw-text-xs tw-font-medium tw-text-indigo-600 hover:tw-text-indigo-700"
                            >Vezi toți</inertia-link
                        >
                    </div>

                    <div class="tw-divide-y tw-divide-gray-100">
                        <div
                            v-for="(u, idx) in recentUsers"
                            :key="u.id || idx"
                            class="tw-flex tw-items-center tw-justify-between tw-py-3"
                        >
                            <div
                                class="tw-flex tw-items-center tw-gap-3 tw-min-w-0"
                            >
                                <div
                                    class="tw-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-full tw-bg-gray-100 tw-text-sm tw-font-semibold tw-text-gray-700"
                                >
                                    {{ initials(u.name || u.email) }}
                                </div>
                                <div class="tw-min-w-0">
                                    <p
                                        class="tw-truncate tw-text-sm tw-font-medium tw-text-gray-900"
                                    >
                                        {{ u.name || "—" }}
                                    </p>
                                    <p
                                        class="tw-truncate tw-text-xs tw-text-gray-500"
                                    >
                                        {{ u.email || "—" }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="tw-flex tw-items-center tw-gap-4 tw-text-xs"
                            >
                                <span class="tw-text-gray-600"
                                    >🚗 {{ vehiclesOf(u).length }}</span
                                >
                                <span :class="badgeClass(u)">
                                    {{ verifiedOf(u) }}/{{
                                        documentsOf(u).length
                                    }}
                                    verificate
                                </span>
                            </div>
                        </div>

                        <div
                            v-if="!recentUsers.length"
                            class="tw-text-sm tw-text-gray-500 tw-py-4"
                        >
                            Nu există utilizatori.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listă utilizatori + căutare -->
            <div
                class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-shadow-sm"
            >
                <div
                    class="tw-flex tw-items-center tw-justify-between tw-gap-3 tw-p-4"
                >
                    <h3 class="tw-text-base tw-font-semibold tw-text-gray-900">
                        Utilizatori
                    </h3>
                    <div class="tw-relative">
                        <input
                            v-model="q"
                            type="search"
                            placeholder="Caută după nume sau email..."
                            class="tw-w-72 tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-bg-white tw-py-2 tw-pl-3 tw-pr-8 tw-text-sm focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                        />
                    </div>
                </div>

                <div class="tw-overflow-x-auto">
                    <table class="tw-min-w-full tw-text-left">
                        <thead
                            class="tw-bg-gray-50 tw-text-xs tw-uppercase tw-text-gray-500"
                        >
                            <tr>
                                <th class="tw-px-4 tw-py-2">Utilizator</th>
                                <th class="tw-px-4 tw-py-2">Email</th>
                                <th class="tw-px-4 tw-py-2">Mașini</th>
                                <th class="tw-px-4 tw-py-2">Documente</th>
                                <th class="tw-px-4 tw-py-2">
                                    Status documente
                                </th>
                                <th class="tw-px-4 tw-py-2 tw-text-right">
                                    Acțiuni
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="tw-divide-y tw-divide-gray-100 tw-text-sm"
                        >
                            <tr v-for="u in filteredUsers" :key="u.id">
                                <td
                                    class="tw-px-4 tw-py-2 tw-font-medium tw-text-gray-900"
                                >
                                    {{ u.name || "—" }}
                                </td>
                                <td class="tw-px-4 tw-py-2 tw-text-gray-600">
                                    {{ u.email || "—" }}
                                </td>
                                <td class="tw-px-4 tw-py-2">
                                    🚗 {{ vehiclesOf(u).length }}
                                </td>
                                <td class="tw-px-4 tw-py-2">
                                    📄 {{ documentsOf(u).length }}
                                </td>
                                <td class="tw-px-4 tw-py-2">
                                    <span :class="badgeClass(u)"
                                        >{{ verifiedOf(u) }}/{{
                                            documentsOf(u).length
                                        }}
                                        verificate</span
                                    >
                                </td>
                                <td class="tw-px-4 tw-py-2 tw-text-right">
                                    <inertia-link
                                        :href="route('admin.users.index')"
                                        class="tw-rounded-md tw-border tw-border-gray-300 tw-bg-white tw-px-3 tw-py-1.5 tw-text-xs tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50"
                                    >
                                        Detalii
                                    </inertia-link>
                                </td>
                            </tr>

                            <tr v-if="!filteredUsers.length">
                                <td
                                    colspan="6"
                                    class="tw-px-4 tw-py-6 tw-text-center tw-text-sm tw-text-gray-500"
                                >
                                    Niciun utilizator pentru filtrul curent.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminDashboardLayout>
</template>

<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { computed, ref } from "vue";

const props = defineProps({
    users: { type: Array, default: () => [] }, // fiecare user are .vehicles și .documents (array-uri)
});

/* ---------- helpers pentru date ---------- */
const vehiclesOf = (u) => (Array.isArray(u?.vehicles) ? u.vehicles : []);
const documentsOf = (u) => (Array.isArray(u?.documents) ? u.documents : []);
const verifiedOf = (u) => documentsOf(u).filter((d) => !!d?.verified_at).length;
const initials = (name = "") =>
    (name || "")
        .split(" ")
        .map((p) => p[0])
        .join("")
        .slice(0, 2)
        .toUpperCase() || "U";

/* ---------- statistici globale ---------- */
const totalUsers = computed(() => props.users.length);
const totalVehicles = computed(() =>
    props.users.reduce((sum, u) => sum + vehiclesOf(u).length, 0)
);
const usersWithCars = computed(
    () => props.users.filter((u) => vehiclesOf(u).length > 0).length
);

const totalDocs = computed(() =>
    props.users.reduce((sum, u) => sum + documentsOf(u).length, 0)
);
const verifiedDocs = computed(() =>
    props.users.reduce((sum, u) => sum + verifiedOf(u), 0)
);
const verificationRate = computed(() => {
    if (!totalDocs.value) return 0;
    return Math.round((verifiedDocs.value / totalDocs.value) * 100);
});
const avgVehiclesPerUser = computed(() => {
    if (!totalUsers.value) return 0;
    return (totalVehicles.value / totalUsers.value).toFixed(2);
});

/* ---------- top owners ---------- */
const topOwners = computed(() => {
    const sorted = [...props.users].sort(
        (a, b) => vehiclesOf(b).length - vehiclesOf(a).length
    );
    return sorted.slice(0, 5);
});
const maxVehiclesInTop = computed(() =>
    Math.max(1, ...topOwners.value.map((u) => vehiclesOf(u).length))
);
const ownerBarWidth = (u) =>
    Math.round((vehiclesOf(u).length / maxVehiclesInTop.value) * 100);

/* ---------- utilizatori recenți ---------- */
const recentUsers = computed(() => {
    // sortăm după created_at desc (dacă există), altfel lăsăm ordinea curentă
    const withDate = [...props.users].map((u) => ({
        ...u,
        _ts: Date.parse(u?.created_at || "") || 0,
    }));
    withDate.sort((a, b) => b._ts - a._ts);
    return withDate.slice(0, 6);
});

/* ---------- listă + search ---------- */
const q = ref("");
const filteredUsers = computed(() => {
    const term = q.value.trim().toLowerCase();
    if (!term) return props.users;
    return props.users.filter(
        (u) =>
            (u?.name || "").toLowerCase().includes(term) ||
            (u?.email || "").toLowerCase().includes(term)
    );
});

/* ---------- badge class ---------- */
const badgeClass = (u) => {
    const total = documentsOf(u).length;
    const ok = verifiedOf(u);
    const done = total > 0 && ok === total;
    return [
        "tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-px-2 tw-py-0.5 tw-text-[11px] tw-font-medium",
        done
            ? "tw-bg-emerald-50 tw-text-emerald-700 tw-border tw-border-emerald-200"
            : "tw-bg-amber-50 tw-text-amber-700 tw-border tw-border-amber-200",
    ].join(" ");
};

/* ---------- safe route check (evită erori dacă nu există) ---------- */
const hasRoute = (name) => {
    try {
        return !!route(name);
    } catch {
        return false;
    }
};
</script>
