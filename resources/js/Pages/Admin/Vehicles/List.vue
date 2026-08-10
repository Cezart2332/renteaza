<script setup>
/**
 * Lista globala de masini pentru admin.
 *
 * Pana acum nu exista: ca sa aprobi o masina trebuia sa stii dinainte cine e
 * proprietarul si sa treci prin Utilizatori -> editare -> vehicule ->
 * documentele vehiculului. Aici sunt toate masinile, filtrabile dupa status,
 * cu aprobare/respingere direct din tabel.
 */
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import WebPagination from "@/Components/WebPagination.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { computed, reactive, watch } from "vue";
import debounce from "lodash/fp/debounce";

const props = defineProps({
    vehicles: { type: Object, required: true },
    prevFilters: { type: Object, default: () => ({}) },
    statusOptions: { type: Array, default: () => [] },
});

const filters = reactive({
    search: props.prevFilters.search ?? "",
    status: props.prevFilters.status ?? "",
});

const applyFilters = debounce(400, () => {
    router.get(route("admin.vehicles.index"), { filters }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
});

watch(filters, applyFilters);

const pendingCount = computed(
    () => props.vehicles.data.filter((v) => v.status === "pending").length
);

function setStatus(vehicle, status) {
    router.put(
        route("admin.users.vehicle-status.update", vehicle.id),
        { status },
        { preserveScroll: true }
    );
}

const badge = {
    active: "tw-bg-emerald-50 tw-text-emerald-700 tw-ring-emerald-600/20",
    pending: "tw-bg-amber-50 tw-text-amber-800 tw-ring-amber-600/20",
    inactive: "tw-bg-rose-50 tw-text-rose-700 tw-ring-rose-600/20",
};

const label = {
    active: "Aprobată",
    pending: "În așteptare",
    inactive: "Respinsă",
};
</script>

<template>
    <Head title="Mașini" />

    <AdminDashboardLayout>
        <div class="tw-rounded-lg tw-bg-white tw-px-4 tw-py-6 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-mb-8 tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-4">
                <div>
                    <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">
                        Mașini ({{ vehicles.total }})
                    </h1>
                    <p v-if="pendingCount" class="tw-mt-1 tw-text-sm tw-text-amber-700">
                        {{ pendingCount }} pe pagina curentă așteaptă aprobare.
                    </p>
                </div>

                <div class="tw-flex tw-flex-wrap tw-gap-3">
                    <input
                        v-model="filters.search"
                        type="search"
                        placeholder="Marcă, model sau număr"
                        class="tw-w-64 tw-rounded-lg tw-border-gray-300 tw-text-sm focus:tw-border-[var(--theme2)] focus:tw-ring-[var(--theme2)]"
                    />
                    <select
                        v-model="filters.status"
                        class="tw-rounded-lg tw-border-gray-300 tw-text-sm focus:tw-border-[var(--theme2)] focus:tw-ring-[var(--theme2)]"
                    >
                        <option value="">Toate statusurile</option>
                        <option v-for="s in statusOptions" :key="s.value" :value="s.value">
                            {{ label[s.value] ?? s.label }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="tw-overflow-x-auto">
                <table class="tw-min-w-full tw-divide-y tw-divide-gray-200">
                    <thead>
                        <tr class="tw-text-left tw-text-xs tw-font-semibold tw-uppercase tw-tracking-wide tw-text-gray-500">
                            <th class="tw-py-3 tw-pr-4">Mașina</th>
                            <th class="tw-py-3 tw-pr-4">Proprietar</th>
                            <th class="tw-py-3 tw-pr-4">Preț/zi</th>
                            <th class="tw-py-3 tw-pr-4">Status</th>
                            <th class="tw-py-3 tw-pr-4 tw-text-right">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody class="tw-divide-y tw-divide-gray-100 tw-text-sm">
                        <tr v-for="v in vehicles.data" :key="v.id">
                            <td class="tw-py-4 tw-pr-4">
                                <div class="tw-font-semibold tw-text-gray-900">
                                    {{ v.brand }} {{ v.model }}
                                </div>
                                <div class="tw-text-xs tw-text-gray-500">
                                    {{ v.year }} · {{ v.license_plate }}
                                </div>
                            </td>
                            <td class="tw-py-4 tw-pr-4">
                                <div class="tw-text-gray-900">{{ v.owner?.name }}</div>
                                <div class="tw-text-xs tw-text-gray-500">{{ v.owner?.email }}</div>
                            </td>
                            <td class="tw-py-4 tw-pr-4 tw-text-gray-900">
                                {{ v.price_per_day }}
                            </td>
                            <td class="tw-py-4 tw-pr-4">
                                <span
                                    :class="[
                                        'tw-inline-flex tw-rounded-full tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold tw-ring-1 tw-ring-inset',
                                        badge[v.status] ?? badge.pending,
                                    ]"
                                >
                                    {{ label[v.status] ?? v.status }}
                                </span>
                            </td>
                            <td class="tw-py-4 tw-pr-4">
                                <div class="tw-flex tw-flex-wrap tw-justify-end tw-gap-2">
                                    <Link
                                        :href="route('admin.users.vehicles-documents.show', {
                                            userId: v.owner_id,
                                            vehicleId: v.id,
                                        })"
                                        class="tw-rounded-lg tw-border tw-border-gray-300 tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-gray-700 hover:tw-bg-gray-50"
                                    >
                                        Documente
                                    </Link>
                                    <button
                                        v-if="v.status !== 'active'"
                                        type="button"
                                        class="tw-rounded-lg tw-bg-emerald-600 tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-white hover:tw-bg-emerald-700"
                                        @click="setStatus(v, 'active')"
                                    >
                                        Aprobă
                                    </button>
                                    <button
                                        v-if="v.status !== 'inactive'"
                                        type="button"
                                        class="tw-rounded-lg tw-bg-[var(--theme)] tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-white hover:tw-brightness-95"
                                        @click="setStatus(v, 'inactive')"
                                    >
                                        Respinge
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="!vehicles.data.length">
                            <td colspan="5" class="tw-py-12 tw-text-center tw-text-gray-500">
                                Nicio mașină care să corespundă filtrelor.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <WebPagination :links="vehicles.links" class="tw-mt-6" />
        </div>
    </AdminDashboardLayout>
</template>
