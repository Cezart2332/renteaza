<template>
    <AdminDashboardLayout>
        <!-- Controls -->
        <!-- <div
            class="tw-flex tw-flex-col md:tw-flex-row tw-items-start md:tw-items-center tw-justify-between tw-gap-3 tw-mb-6"
        >
            <div class="tw-flex tw-gap-2 tw-w-full md:tw-w-auto">
                <div class="tw-relative tw-flex-1 md:tw-w-80">
                    <input
                        v-model.trim="search"
                        type="text"
                        placeholder="Caută după brand, model, proprietar, nr. înmatriculare…"
                        class="tw-w-full tw-rounded-xl tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-pl-9 tw-pr-3 focus:tw-ring-2 focus:tw-ring-indigo-500"
                    />
                    <span
                        class="tw-absolute tw-left-3 tw-top-1/2 -tw-translate-y-1/2 tw-text-gray-400"
                        >🔎</span
                    >
                </div>

                <select
                    v-model="status"
                    class="tw-rounded-xl tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-3 focus:tw-ring-2 focus:tw-ring-indigo-500"
                >
                    <option value="">Toate statusurile</option>
                    <option value="approved">Aprobate</option>
                    <option value="pending">În așteptare</option>
                    <option value="disabled">Dezactivate</option>
                </select>

                <select
                    v-model="sortBy"
                    class="tw-rounded-xl tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-3 focus:tw-ring-2 focus:tw-ring-indigo-500"
                >
                    <option value="newest">Cele mai noi</option>
                    <option value="price_asc">Preț crescător</option>
                    <option value="price_desc">Preț descrescător</option>
                    <option value="year_desc">An descrescător</option>
                    <option value="year_asc">An crescător</option>
                </select>
            </div>

            <div class="tw-text-xs tw-text-gray-500">
                {{ filteredVehicles.length }} rezultate
            </div>
        </div> -->

        <!-- Empty state -->
        <div
            v-if="!pagedVehicles.length"
            class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-h-56 tw-rounded-2xl tw-border tw-border-dashed tw-text-center tw-bg-white"
        >
            <div class="tw-text-3xl">🚗</div>
            <div class="tw-mt-2 tw-font-medium tw-text-gray-900">
                Nu s-au găsit mașini
            </div>
            <div class="tw-text-sm tw-text-gray-500">
                Încearcă altă căutare sau filtre.
            </div>
        </div>

        <!-- Grid -->
        <div
            v-else
            class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-3 xl:tw-grid-cols-4 tw-gap-6 tw-mb-8"
        >
            <div
                v-for="v in pagedVehicles"
                :key="v.id"
                class="tw-group tw-flex tw-flex-col tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-shadow-sm hover:tw-shadow-md tw-transition"
            >
                <!-- Media -->
                <div
                    class="tw-relative tw-aspect-[16/9] tw-w-full tw-overflow-hidden tw-rounded-t-2xl tw-bg-gray-100"
                >
                    <img
                        :src="imageFromAwsPublic(v.cover_image)"
                        :alt="
                            `${v.brand || ''} ${v.model || ''}`.trim() ||
                            'Vehicul'
                        "
                        class="tw-h-full tw-w-full tw-object-cover tw-transition group-hover:tw-scale-[1.02]"
                        @error="onImgError"
                    />

                    <!-- status pill -->
                    <div
                        class="tw-absolute tw-top-2 tw-left-2 tw-flex tw-items-center tw-gap-1"
                    >
                        <span
                            class="tw-text-[11px] tw-font-medium tw-rounded-full tw-px-2 tw-py-1"
                            :class="statusClass(v.status)"
                        >
                            {{ statusLabel(v.status) }}
                        </span>
                    </div>

                    <!-- quick actions -->
                    <!-- <div
                        class="tw-absolute tw-inset-0 tw-flex tw-items-end tw-justify-end tw-p-2 tw-opacity-0 group-hover:tw-opacity-100 tw-transition"
                    >
                        <div class="tw-flex tw-gap-1">
                            <button
                                type="button"
                                class="tw-rounded-lg tw-bg-white/90 hover:tw-bg-white tw-px-2.5 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-gray-800 tw-shadow"
                                @click="goShow(v)"
                                title="Vezi detalii"
                            >
                                Vezi
                            </button>
                            <button
                                type="button"
                                class="tw-rounded-lg tw-bg-white/90 hover:tw-bg-white tw-px-2.5 tw-py-1.5 tw-text-xs tw-font-semibold tw-text-gray-800 tw-shadow"
                                @click="$emit('edit', v)"
                                title="Editează"
                            >
                                Editează
                            </button>
                        </div>
                    </div> -->
                </div>

                <!-- Body -->
                <div class="tw-p-3 tw-flex tw-flex-col tw-gap-2">
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <div
                            class="tw-font-semibold tw-text-gray-900 tw-truncate"
                        >
                            {{ v.brand || "-" }} {{ v.model || "" }}
                        </div>
                        <div
                            v-if="v.year"
                            class="tw-text-xs tw-text-gray-500 tw-ml-2"
                        >
                            {{ v.year }}
                        </div>
                    </div>

                    <div
                        class="tw-flex tw-items-center tw-gap-2 tw-text-xs tw-text-gray-500"
                    >
                        <span
                            class="tw-inline-flex tw-items-center tw-gap-1 tw-rounded-full tw-bg-gray-100 tw-px-2 tw-py-1"
                        >
                            <span>👤</span>
                            <span class="tw-max-w-[10rem] tw-truncate">{{
                                v.owner?.name || "—"
                            }}</span>
                        </span>
                        <span
                            v-if="v.price_per_day != null"
                            class="tw-ml-auto tw-text-gray-900 tw-font-semibold"
                        >
                            €{{ Number(v.price_per_day).toLocaleString() }}
                            <span class="tw-text-xs tw-text-gray-500">/zi</span>
                        </span>
                    </div>
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-pt-1"
                    >
                        <div class="tw-flex tw-gap-1">
                            <span
                                v-if="v.seats"
                                class="tw-text-[11px] tw-text-gray-600 tw-bg-gray-100 tw-px-2 tw-py-0.5 tw-rounded-full"
                                >{{ v.seats }} locuri</span
                            >
                            <span
                                v-if="v.doors"
                                class="tw-text-[11px] tw-text-gray-600 tw-bg-gray-100 tw-px-2 tw-py-0.5 tw-rounded-full"
                                >{{ v.doors }} uși</span
                            >
                        </div>
                        <inertia-link :href="route('admin.users.vehicles-documents.show',{
                            userId: user.id,
                            vehicleId: v.id
                        })"
                            class="tw-text-[11px] tw-font-medium tw-text-indigo-700 hover:tw-text-indigo-900"
                        >
                            Vezi documentele
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div
            v-if="pageCount > 1"
            class="tw-flex tw-items-center tw-justify-center tw-gap-2"
        >
            <button
                class="tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-3 tw-py-1.5 tw-text-sm tw-text-gray-700 disabled:tw-opacity-50"
                :disabled="page === 1"
                @click="page--"
            >
                ← Înapoi
            </button>
            <div class="tw-text-sm tw-text-gray-600">
                Pagina {{ page }} din {{ pageCount }}
            </div>
            <button
                class="tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-3 tw-py-1.5 tw-text-sm tw-text-gray-700 disabled:tw-opacity-50"
                :disabled="page === pageCount"
                @click="page++"
            >
                Înainte →
            </button>
        </div>
    </AdminDashboardLayout>
</template>

<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { computed, ref } from "vue";

const props = defineProps({
    vehicles: { type: Array, default: () => [] },
    user: { type: Object, required: true },
});

// --- Controls
const search = ref("");
const status = ref("");
const sortBy = ref("newest");
const page = ref(1);
const PAGE_SIZE = 12;

// --- Helpers
const PLACEHOLDER =
    "data:image/svg+xml;utf8," +
    encodeURIComponent(
        `<svg xmlns="http://www.w3.org/2000/svg" width="640" height="360"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="sans-serif" font-size="18">Fără imagine</text></svg>`
    );

function onImgError(e) {
    e.target.src = PLACEHOLDER;
}

function statusLabel(s) {
    if (s === "approved" || s === "active" || s === "verified")
        return "Aprobat";
    if (s === "inactive") return "Dezactivat";
    return "În așteptare";
}
function statusClass(s) {
    if (s === "approved" || s === "active" || s === "verified")
        return "tw-bg-green-500 tw-text-white";
    if (s === "inactive") return "tw-bg-red-500 tw-text-white";
    return "tw-bg-amber-100 tw-text-amber-800";
}

// --- Filtering + sorting
const filteredVehicles = computed(() => {
    const q = search.value.toLowerCase().trim();
    return props.vehicles.filter((v) => {
        const matchesStatus = status.value
            ? (v?.status || "") === status.value
            : true;
        const hay = `${v?.brand || ""} ${v?.model || ""} ${
            v?.owner?.name || ""
        } ${v?.license_plate || ""}`.toLowerCase();
        const matchesSearch = q ? hay.includes(q) : true;
        return matchesStatus && matchesSearch;
    });
});

const sortedVehicles = computed(() => {
    const list = [...filteredVehicles.value];
    switch (sortBy.value) {
        case "price_asc":
            list.sort(
                (a, b) =>
                    (a?.price_per_day ?? Infinity) -
                    (b?.price_per_day ?? Infinity)
            );
            break;
        case "price_desc":
            list.sort(
                (a, b) =>
                    (b?.price_per_day ?? -Infinity) -
                    (a?.price_per_day ?? -Infinity)
            );
            break;
        case "year_desc":
            list.sort((a, b) => (b?.year ?? 0) - (a?.year ?? 0));
            break;
        case "year_asc":
            list.sort((a, b) => (a?.year ?? 0) - (b?.year ?? 0));
            break;
        default: // newest (fallback pe id/created_at dacă există)
            list.sort((a, b) => {
                const ca = new Date(a?.created_at || 0).getTime();
                const cb = new Date(b?.created_at || 0).getTime();
                if (cb !== ca) return cb - ca;
                // fallback pe id string
                return String(b?.id || "").localeCompare(String(a?.id || ""));
            });
    }
    return list;
});

// --- Pagination
const pageCount = computed(() =>
    Math.max(1, Math.ceil(sortedVehicles.value.length / PAGE_SIZE))
);
const pagedVehicles = computed(() => {
    if (page.value > pageCount.value) page.value = pageCount.value;
    const start = (page.value - 1) * PAGE_SIZE;
    return sortedVehicles.value.slice(start, start + PAGE_SIZE);
});

// --- Navigation (avoidă dependența de route name)
function goShow(v) {
    if (!v?.id) return;
    // ajustează după setup-ul tău de rute admin
    // this.$inertia.visit(`/admin/vehicles/${v.id}`);
    window.location.href = `/admin/vehicles/${v.id}`;
}
</script>
