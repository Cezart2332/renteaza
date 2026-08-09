<template>
    <OwnerDashboardLayout>
        <div class="sm:tw-flex sm:tw-flex-row sm:tw-items-start tw-mt-12">
            <!-- Filter section -->
            <div class="tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-w-12/12 sm:tw-w-3/12">
                <div
                    class="tw-mt-8 tw-overflow-hidden tw-rounded-2xl tw-bg-blue-50 tw-shadow-xl tw-p-8 tw-flex tw-flex-col tw-gap-6"
                >
                    <div
                        class="tw-flex tw-flex-row tw-items-center tw-justify-between"
                    >
                        <span class="tw-text-xl tw-font-bold">Filtre</span>
                        <button
                            class="tw-text-sm tw-text-blue-800 hover:tw-underline"
                            @click="resetFilters"
                        >
                            Resetează
                        </button>
                    </div>

                    <!-- Status -->
                    <div class="tw-flex tw-flex-col tw-gap-4">
                        <span class="tw-font-bold tw-text-sm">Status</span>
                        <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
                            <label class="tw-flex tw-items-center tw-gap-2">
                                <input
                                    type="checkbox"
                                    value="active"
                                    v-model="filters.statuses"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700"
                                    >Activ</span
                                >
                            </label>
                            <label class="tw-flex tw-items-center tw-gap-2">
                                <input
                                    type="checkbox"
                                    value="inactive"
                                    v-model="filters.statuses"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700"
                                    >Inactiv</span
                                >
                            </label>
                            <label class="tw-flex tw-items-center tw-gap-2">
                                <input
                                    type="checkbox"
                                    value="pending"
                                    v-model="filters.statuses"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700"
                                    >În așteptare</span
                                >
                            </label>
                        </div>
                    </div>

                    <!-- Fuel type -->
                    <div class="tw-flex tw-flex-col tw-gap-4">
                        <span class="tw-font-bold tw-text-sm"
                            >Tip combustibil</span
                        >
                        <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
                            <label class="tw-flex tw-items-center tw-gap-2">
                                <input
                                    type="checkbox"
                                    value="electric"
                                    v-model="filters.fuels"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700"
                                    >Electric</span
                                >
                            </label>
                            <label class="tw-flex tw-items-center tw-gap-2">
                                <input
                                    type="checkbox"
                                    value="hibrid"
                                    v-model="filters.fuels"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700"
                                    >Hibrid</span
                                >
                            </label>
                            <label class="tw-flex tw-items-center tw-gap-2">
                                <input
                                    type="checkbox"
                                    value="diesel"
                                    v-model="filters.fuels"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700"
                                    >Diesel</span
                                >
                            </label>
                            <label class="tw-flex tw-items-center tw-gap-2">
                                <input
                                    type="checkbox"
                                    value="benzina"
                                    v-model="filters.fuels"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700"
                                    >Benzină</span
                                >
                            </label>
                        </div>
                    </div>

                    <div class="tw-flex tw-flex-col tw-gap-4">
                        <span class="tw-font-bold tw-text-sm">Brand</span>

                        <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
                            <label
                                v-for="b in facets.brands"
                                :key="b.value"
                                class="tw-flex tw-items-center tw-gap-2"
                            >
                                <input
                                    type="checkbox"
                                    :value="b.value"
                                    v-model="filters.brands"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300"
                                />
                                <span class="tw-text-sm tw-text-gray-700">{{
                                    b.label
                                }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Verified -->
                    <div class="tw-flex tw-items-center tw-justify-between">
                        <span class="tw-text-sm tw-font-bold">Verificate</span>
                        <Toggle v-model="filters.verified" />
                    </div>

                    <button
                        class="tw-flex tw-items-center tw-justify-center tw-bg-blue-600 tw-text-white tw-rounded-xl tw-py-2 tw-cursor-pointer hover:tw-bg-blue-700"
                        @click="applyFilters"
                        type="button"
                    >
                        Aplică filtre
                    </button>
                </div>
            </div>

            <!-- Table section -->
            <div
                class="tw-flex tw-flex-col tw-gap-10 tw-px-4 sm:tw-px-6 lg:tw-px-8 sm:tw-w-9/12"
            >
                <div
                    class="tw-mt-8 tw-overflow-hidden tw-rounded-2xl tw-shadow-xl tw-divide-y"
                >
                    <div
                        class="sm:tw-flex sm:tw-items-center tw-bg-white tw-p-4"
                    >
                        <div
                            class="sm:tw-flex-auto tw-flex tw-flex-col tw-gap-2"
                        >
                            <h1
                                class="tw-text-base tw-font-semibold tw-text-gray-900 md:tw-text-2xl"
                            >
                                Mașini
                            </h1>
                            <span class="tw-text-sm tw-text-gray-500">
                                Aici poți vizualiza toate mașinile tale. Poți
                                adăuga, edita sau șterge mașini.
                            </span>
                        </div>
                        <inertia-link
                            :href="route('user.cars.create')"
                            class="tw-mt-4 tw-flex tw-flex-row tw-items-center tw-justify-center tw-bg-blue-600 tw-text-white tw-rounded-xl tw-py-1 tw-px-3 tw-cursor-pointer hover:tw-bg-blue-700"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="tw-size-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 4.5v15m7.5-7.5h-15"
                                />
                            </svg>
                            <span>Adaugă mașină</span>
                        </inertia-link>
                    </div>

                    <div
                        class="sm:tw-flex sm:tw-items-center tw-gap-2 tw-bg-white tw-p-4"
                    >
                        <div class="tw-relative tw-w-full sm:tw-w-1/3">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Caută mașină"
                                class="tw-border tw-border-gray-300 tw-rounded-full tw-pl-10 tw-pr-4 tw-py-2 tw-w-full focus:tw-ring-indigo-500 focus:tw-border-indigo-500 sm:tw-text-sm"
                            />
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="tw-absolute tw-left-3 tw-top-1/4 tw-transform tw--translate-y-1/2 tw-h-5 tw-w-5 tw-text-gray-400"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                                />
                            </svg>
                        </div>
                    </div>
                    <div
                        class="tw-max-h-[70vh] tw-overflow-y-auto sm:tw-max-h-none sm:tw-overflow-visible"
                    >
                        <table
                            class="tw-bg-white tw-min-w-full tw-divide-y tw-divide-gray-300"
                        >
                            <thead class="tw-bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="tw-py-3.5 tw-pl-4 tw-pr-3 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900 sm:tw-pl-3"
                                    >
                                        Model
                                    </th>
                                    <th
                                        scope="col"
                                        class="tw-px-3 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900"
                                    >
                                        Brand
                                    </th>
                                    <th
                                        scope="col"
                                        class="tw-px-3 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900"
                                    >
                                        Preț/zi
                                    </th>
                                    <th
                                        scope="col"
                                        class="tw-px-3 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900"
                                    >
                                        Status
                                    </th>
                                    <th
                                        scope="col"
                                        class="tw-px-3 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900"
                                    >
                                        Acțiuni
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="tw-bg-white tw-divide-y tw-divide-gray-200"
                            >
                                <tr v-for="car in cars.data" :key="car.brand">
                                    <td
                                        class="tw-whitespace-nowrap tw-py-4 tw-pl-4 tw-pr-3 tw-text-sm tw-font-medium tw-text-gray-900 sm:tw-pl-3"
                                    >
                                        {{ car.brand }}
                                    </td>
                                    <td
                                        class="tw-whitespace-nowrap tw-px-3 tw-py-4 tw-text-sm tw-text-gray-500"
                                    >
                                        {{ car.model }}
                                    </td>
                                    <td
                                        class="tw-whitespace-nowrap tw-px-3 tw-py-4 tw-text-sm tw-text-gray-500"
                                    >
                                        {{ car.price_per_day }}
                                    </td>
                                    <td
                                        class="tw-whitespace-nowrap tw-px-3 tw-py-4 tw-font-bold"
                                    >
                                        <div
                                            :class="{
                                                'tw-bg-green-100 tw-border-green-400 tw-text-green-800':
                                                    car.status === 'active',
                                                'tw-bg-gray-200 tw-border-gray-400 tw-text-gray-800':
                                                    car.status === 'inactive',
                                                'tw-bg-yellow-100 tw-border-yellow-400 tw-text-yellow-800':
                                                    car.status === 'pending',
                                            }"
                                            class="tw-flex tw-flex-row tw-items-center tw-justify-center tw-gap-2 tw-px-3 tw-py-1 tw-w-24 tw-rounded-full tw-text-sm tw-border"
                                        >
                                            <div
                                                :class="{
                                                    'tw-bg-green-500':
                                                        car.status === 'active',
                                                    'tw-bg-gray-500':
                                                        car.status ===
                                                        'inactive',
                                                    'tw-bg-yellow-500':
                                                        car.status ===
                                                        'pending',
                                                }"
                                                class="tw-w-3 tw-h-3 tw-rounded-full"
                                            ></div>
                                            <span>
                                                {{ car.status }}
                                            </span>
                                        </div>
                                    </td>

                                    <td
                                        class="tw-whitespace-nowrap tw-py-4 tw-px-3 tw-text-sm tw-text-gray-900 tw-text-center tw-align-middle tw-w-[120px]"
                                    >
                                        <div
                                            class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2"
                                        >
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'user.cars.documents',
                                                        car.slug
                                                    )
                                                "
                                                class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-blue-50 tw-cursor-pointer"
                                            >
                                                <svg
                                                    width="24px"
                                                    height="24px"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        d="M5 8C5 5.17157 5 3.75736 5.87868 2.87868C6.75736 2 8.17157 2 11 2H13C15.8284 2 17.2426 2 18.1213 2.87868C19 3.75736 19 5.17157 19 8V16C19 18.8284 19 20.2426 18.1213 21.1213C17.2426 22 15.8284 22 13 22H11C8.17157 22 6.75736 22 5.87868 21.1213C5 20.2426 5 18.8284 5 16V8Z"
                                                        stroke="#1C274C"
                                                        stroke-width="1.5"
                                                    />
                                                    <path
                                                        d="M5 4.07617C4.02491 4.17208 3.36857 4.38885 2.87868 4.87873C2 5.75741 2 7.17163 2 10.0001V14.0001C2 16.8285 2 18.2427 2.87868 19.1214C3.36857 19.6113 4.02491 19.828 5 19.9239"
                                                        stroke="#1C274C"
                                                        stroke-width="1.5"
                                                    />
                                                    <path
                                                        d="M19 4.07617C19.9751 4.17208 20.6314 4.38885 21.1213 4.87873C22 5.75741 22 7.17163 22 10.0001V14.0001C22 16.8285 22 18.2427 21.1213 19.1214C20.6314 19.6113 19.9751 19.828 19 19.9239"
                                                        stroke="#1C274C"
                                                        stroke-width="1.5"
                                                    />
                                                    <path
                                                        d="M9 13H15"
                                                        stroke="#1C274C"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                    />
                                                    <path
                                                        d="M9 9H15"
                                                        stroke="#1C274C"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                    />
                                                    <path
                                                        d="M9 17H12"
                                                        stroke="#1C274C"
                                                        stroke-width="1.5"
                                                        stroke-linecap="round"
                                                    />
                                                </svg>
                                            </inertia-link>
                                            <inertia-link
                                                class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-orange-50 tw-cursor-pointer"
                                                :href="
                                                    route(
                                                        'user.cars.edit',
                                                        car.slug
                                                    )
                                                "
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="tw-size-6"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                                    />
                                                </svg>
                                            </inertia-link>
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'user.calendar.show',
                                                        car.slug
                                                    )
                                                "
                                                class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-green-50 tw-cursor-pointer"
                                            >
                                                <img
                                                    :src="
                                                        imagePath(
                                                            'calendar.png'
                                                        )
                                                    "
                                                    class="tw-w-6 tw-h-6"
                                                    alt=""
                                                />
                                            </inertia-link>
                                            <div
                                                class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-red-50 tw-cursor-pointer"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    @click="deleteCar(car.id)"
                                                    class="tw-size-6 tw-text-red-500"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <WebPagination :links="cars.links" />
            </div>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import Toggle from "@/Components/Toggle.vue";
import WebPagination from "@/Components/WebPagination.vue";
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    cars: { type: Object, required: true },
    prev: {
        type: Object,
        default: () => ({
            search: "",
            statuses: [],
            fuels: [],
            brands: [],
            verified: false,
        }),
    },
    facets: { type: Object, default: () => ({ brands: [] }) },
});

const filters = reactive({
    search: props.prev?.search ?? "",
    statuses: props.prev?.statuses ?? [],
    fuels: props.prev?.fuels ?? [],
    brands: props.prev?.brands ?? [],
    verified: !!props.prev?.verified,
});

function applyFilters() {
    router.get(
        route("user.cars.index"),
        {
            search: filters.search || undefined,
            statuses: filters.statuses.length ? filters.statuses : undefined,
            fuels: filters.fuels.length ? filters.fuels : undefined,
            brands: filters.brands.length ? filters.brands : undefined,
            verified: filters.verified ? 1 : undefined,
        },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}

function resetFilters() {
    filters.search = "";
    filters.statuses = [];
    filters.fuels = [];
    filters.brands = [];
    filters.verified = false;
    applyFilters();
}

const deleteCar = (carId) => {
    if (confirm("Sigur vrei să ștergi această mașină?")) {
        router.delete(route("user.cars.destroy", carId), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }
};
</script>
