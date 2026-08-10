<template>
    <AdminDashboardLayout>
        <div class="tw-flex tw-flex-col tw-gap-14">
            <div
                class="tw-bg-white tw-rounded-lg tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-4"
            >
                <div class="tw-flex tw-flex-col tw-gap-10">
                    <div
                        class="tw-flex tw-flex-row tw-items-center tw-justify-between"
                    >
                        <span class="tw-text-3xl text-[#3B82F6] font-cocon-bold"
                            >Utilizatori ({{ users.total }})</span
                        >
                    </div>
                    <!-- Filters -->
                    <div class="tw-flex tw-flex-col tw-gap-10">
                        <div
                            class="tw-grid tw-grid-cols-1 tw-gap-6 sm:tw-grid-cols-2 lg:tw-grid-cols-6"
                        >
                            <div v-for="filter in filters" :key="filter.id">
                                <Filter
                                    :filter="filter"
                                    :value="filterValues[filter.model]"
                                    bgColor="#DBEAFE"
                                    textColor="#1D4ED8"
                                    @update:value="
                                        updateFilter(filter.model, $event)
                                    "
                                />
                            </div>
                            <!-- Reset button -->
                            <div class="tw-flex tw-justify-start tw-mt-6">
                                <button
                                    type="button"
                                    @click="resetFilters"
                                    class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-cursor-pointer font-cocon tw-text-sm tw-rounded-3xl hover:tw-bg-blue-200 tw-bg-[#DBEAFE]"
                                >
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tw-bg-white tw-rounded-lg">
                <div class="tw-px-4 sm:tw-px-6 lg:tw-px-8">
                    <!-- Table -->
                    <div class="tw-mt-8 tw-flow-root">
                        <div
                            class="tw--mx-4 tw--my-2 tw-overflow-x-auto sm:tw--mx-6 lg:tw--mx-8"
                        >
                            <div
                                class="tw-inline-block tw-min-w-full tw-align-middle sm:tw-px-6 lg:tw-px-8 tw-py-6"
                            >
                                <table
                                    class="tw-min-w-full tw-divide-y tw-divide-gray-300"
                                >
                                    <thead>
                                        <tr class="font-cocon-bold">
                                            <th
                                                v-for="column in columns"
                                                :key="column.name"
                                                scope="col"
                                                class="tw-px-3 tw-py-3.5 tw-w-36 tw-text-left tw-text-sm tw-text-gray-700"
                                                :class="{
                                                    'tw-cursor-pointer':
                                                        column.sortable,
                                                }"
                                            >
                                                <template
                                                    v-if="column.sortable"
                                                >
                                                    <div
                                                        @click="
                                                            sortBy(column.name)
                                                        "
                                                        class="tw-flex tw-flex-row group tw-items-center tw-whitespace-normal tw-break-words tw-leading-tight"
                                                    >
                                                        {{ column.label }}
                                                        <span
                                                            class="tw-invisible tw-ml-2 tw-flex-none tw-rounded tw-text-gray-400 group-hover:tw-visible group-focus:tw-visible"
                                                        >
                                                            <ChevronDownIcon
                                                                class="tw-h-5 tw-w-5"
                                                                aria-hidden="true"
                                                            />
                                                        </span>
                                                    </div>
                                                </template>

                                                <template v-else>
                                                    {{ column.label }}
                                                </template>
                                            </th>
                                            <th
                                                scope="col"
                                                class="tw-text-center text-md tw-w-24"
                                            >
                                                Acțiuni
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="tw-divide-y tw-divide-gray-200 tw-font-bold tw-text-gray-500"
                                    >
                                        <tr
                                            v-for="(row, index) in users.data"
                                            :key="row.id"
                                            class="even:tw-bg-gray-50 font-cocon-light"
                                        >
                                            <td
                                                class="tw-text-sm tw-px-3 tw-py-2"
                                            >
                                                <div
                                                    class="tw-flex tw-flex-col tw-gap-1"
                                                >
                                                    <span
                                                        class="text-slate-900 tw-font-semibold"
                                                    >
                                                        {{ row.name }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td
                                                class="tw-px-3 tw-py-2 tw-text-sm"
                                            >
                                                {{ row.email }}
                                            </td>
                                            <td
                                                class="tw-px-3 tw-py-2 tw-text-sm"
                                            >
                                                {{
                                                    formatDateRo(
                                                        row.email_verified_at
                                                    )
                                                        ? formatDateRo(
                                                              row.email_verified_at
                                                          )
                                                        : "N/A"
                                                }}
                                            </td>
                                            <td
                                                class="tw-px-3 tw-py-2 tw-text-sm"
                                            >
                                                {{
                                                    row.phone
                                                        ? row.phone
                                                        : "N/A"
                                                }}
                                            </td>
                                            <td
                                                class="tw-px-3 tw-py-2 tw-text-sm"
                                            >
                                                <span
                                                    :class="{
                                                        'tw-bg-blue-100 tw-text-blue-600 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm':
                                                            row.status ===
                                                            'pending',
                                                        'tw-bg-red-100 tw-text-red-600 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm':
                                                            row.status ===
                                                            'declined',
                                                        'tw-bg-green-100 tw-text-green-600 tw-px-3 tw-py-1 tw-rounded-full tw-text-sm':
                                                            row.status ===
                                                            'accepted',
                                                    }"
                                                >
                                                    {{ row.status }}
                                                </span>
                                            </td>
                                            <td
                                                class="tw-whitespace-nowrap tw-py-2 tw-pl-3 tw-pr-4 tw-text-right tw-text-sm tw-font-medium sm:tw-pr-3"
                                            >
                                                <div
                                                    class="tw-flex tw-items-center tw-justify-center tw-gap-2"
                                                >
                                                    <!-- Aprobarea era accesibila doar prin edit -> documente
                                                         personale. Acum se poate face direct din lista. -->
                                                    <button
                                                        v-if="row.status !== 'accepted'"
                                                        type="button"
                                                        title="Acceptă utilizatorul"
                                                        class="tw-rounded-lg tw-bg-emerald-600 tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold tw-text-white hover:tw-bg-emerald-700"
                                                        @click="setUserStatus(row, 'accepted')"
                                                    >
                                                        Acceptă
                                                    </button>
                                                    <button
                                                        v-if="row.status !== 'declined'"
                                                        type="button"
                                                        title="Respinge utilizatorul"
                                                        class="tw-rounded-lg tw-bg-[var(--theme)] tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold tw-text-white hover:tw-brightness-95"
                                                        @click="setUserStatus(row, 'declined')"
                                                    >
                                                        Respinge
                                                    </button>
                                                    <inertia-link
                                                        :href="
                                                            route(
                                                                'admin.users.edit',
                                                                row.id
                                                            )
                                                        "
                                                        class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-orange-50 hover:tw-bg-orange-200 tw-cursor-pointer"
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            class="tw-size-6 text-[#FA902F]"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                                            />
                                                        </svg>
                                                    </inertia-link>
                                                    <div
                                                        class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-red-50 hover:tw-bg-red-200 tw-cursor-pointer"
                                                        @click="
                                                            openDialogForDeleteUser(
                                                                row.id
                                                            )
                                                        "
                                                    >
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
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
                    </div>
                    <TransitionRoot as="template" :show="open">
                        <Dialog
                            as="div"
                            class="tw-relative tw-z-10"
                            @close="open = false"
                        >
                            <TransitionChild
                                as="template"
                                enter="tw-ease-out tw-duration-300"
                                enter-from="tw-opacity-0"
                                enter-to="tw-opacity-100"
                                leave="tw-ease-in tw-duration-200"
                                leave-from="tw-opacity-100"
                                leave-to="tw-opacity-0"
                            >
                                <div
                                    class="tw-fixed tw-inset-0 tw-bg-gray-500 tw-bg-opacity-75 tw-transition-opacity"
                                />
                            </TransitionChild>
                            <div
                                class="tw-fixed tw-inset-0 tw-z-10 tw-w-screen tw-overflow-y-auto"
                            >
                                <div
                                    class="tw-flex tw-min-h-full tw-items-end tw-justify-center tw-p-4 tw-text-center sm:tw-items-center sm:tw-p-0"
                                >
                                    <TransitionChild
                                        as="template"
                                        enter="tw-ease-out tw-duration-300"
                                        enter-from="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95"
                                        enter-to="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100"
                                        leave="tw-ease-in tw-duration-200"
                                        leave-from="tw-opacity-100 tw-translate-y-0 sm:tw-scale-100"
                                        leave-to="tw-opacity-0 tw-translate-y-4 sm:tw-translate-y-0 sm:tw-scale-95"
                                    >
                                        <DialogPanel
                                            class="tw-relative tw-transform tw-overflow-hidden tw-rounded-lg tw-bg-white tw-px-4 tw-pb-4 tw-pt-5 tw-text-left tw-shadow-xl tw-transition-all sm:tw-my-8 sm:tw-w-full sm:tw-max-w-sm sm:tw-p-6"
                                        >
                                            <div>
                                                <div
                                                    class="tw-mx-auto tw-flex tw-h-12 tw-w-12 tw-items-center tw-justify-center tw-rounded-full tw-bg-green-100"
                                                >
                                                    <CheckIcon
                                                        class="tw-h-6 tw-w-6 tw-text-green-600"
                                                        aria-hidden="true"
                                                    />
                                                </div>
                                                <div
                                                    class="tw-mt-3 tw-text-center sm:tw-mt-5"
                                                >
                                                    <DialogTitle
                                                        as="h3"
                                                        class="tw-text-base tw-font-semibold tw-leading-6 tw-text-gray-900"
                                                    >
                                                        Ești sigur că vrei să
                                                        ștergi acest utilizator?
                                                    </DialogTitle>
                                                </div>
                                            </div>
                                            <div
                                                class="tw-flex tw-flex-row tw-gap-5 tw-mt-5 sm:tw-mt-6"
                                            >
                                                <button
                                                    class="tw-inline-flex tw-w-full tw-cursor-pointer tw-justify-center tw-rounded-md tw-bg-indigo-600 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-white tw-shadow-xs hover:tw-bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                                    @click="deleteItem"
                                                >
                                                    Da
                                                </button>
                                                <button
                                                    class="tw-inline-flex tw-w-full tw-justify-center tw-cursor-pointer tw-rounded-md tw-bg-red-600 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-white tw-shadow-xs hover:tw-bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                                                    @click="closeDialog"
                                                >
                                                    Nu
                                                </button>
                                            </div>
                                        </DialogPanel>
                                    </TransitionChild>
                                </div>
                            </div>
                        </Dialog>
                    </TransitionRoot>
                </div>
            </div>
            <WebPagination :links="users.links" class="tw-mt-6" />
        </div>
    </AdminDashboardLayout>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import debounce from "lodash/fp/debounce";
import Filter from "@/Components/Filter.vue";
import WebPagination from "@/Components/WebPagination.vue";
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { router } from "@inertiajs/vue3";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";

// props
function setUserStatus(row, status) {
    router.put(
        route("admin.users.status.update", row.id),
        { status },
        { preserveScroll: true, preserveState: false }
    );
}

const props = defineProps({
    users: {
        type: Array,
        required: true,
    },
    prevFilters: {
        type: Object,
        default: () => ({}),
    },
    orderBy: {
        type: String,
        default: "created_at",
    },
    orderDirection: {
        type: String,
        default: "desc",
    },
    userStatusOptions: {
        type: Array,
        required: true,
    },
});

// static definitions
const columns = [
    { name: "name", label: "Nume", sortable: false },
    { name: "email", label: "Email", sortable: false },
    { name: "email_verified_at", label: "Email Verificat", sortable: true },
    { name: "phone", label: "Telefon", sortable: false },
    { name: "status", label: "Status", sortable: false },
];

const filters = [
    { model: "name", label: "Nume", type: "text", placeholder: "Nume..." },
    { model: "email", label: "Email", type: "text", placeholder: "Email..." },
    {
        model: "email_verified_at",
        label: "Email Verificat",
        type: "date",
        placeholder: "Email Verificat...",
    },
    {
        model: "status",
        label: "Status",
        type: "select",
        placeholder: "Selectează Status...",
        options: [...props.userStatusOptions],
    },
];

// reactive state
const filterValues = reactive(
    filters.reduce((acc, f) => {
        acc[f.model] = props.prevFilters[f.model] ?? "";
        return acc;
    }, {})
);

const open = ref(false);
const userIdToDelete = ref(null);

// watch + debounce
watch(
    filterValues,
    debounce(300, (newFilters) => {
        router.get(
            route("admin.users.index"),
            { filters: newFilters },
            { preserveState: true, replace: true }
        );
    }),
    { deep: true }
);

// methods
function openDialogForDeleteUser(id) {
    open.value = true;
    userIdToDelete.value = id;
}

function closeDialog() {
    open.value = false;
}

function updateFilter(model, newValue) {
    filterValues[model] = newValue;
}

function deleteItem() {
    router.delete(route("admin.users.destroy", userIdToDelete.value), {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
    open.value = false;
}

function resetFilters() {
    Object.keys(filterValues).forEach((key) => {
        filterValues[key] = "";
    });
}

const orderBy = ref(props.orderBy || "created_at");
const orderDirection = ref(props.orderDirection || "desc");
function sortBy(columnName) {
    if (orderBy.value === columnName) {
        orderDirection.value = orderDirection.value === "asc" ? "desc" : "asc";
    } else {
        orderBy.value = columnName;
        orderDirection.value = "asc";
    }

    router.get(
        route("admin.users.index"),
        {
            orderBy: orderBy.value,
            orderDirection: orderDirection.value,
            filters: filterValues,
        },
        { preserveState: true, replace: true }
    );
}

function formatDateRo(dateString) {
    if (!dateString) return "";
    const date = new Date(dateString);
    const day = date.getDate();
    const monthNames = [
        "ianuarie",
        "februarie",
        "martie",
        "aprilie",
        "mai",
        "iunie",
        "iulie",
        "august",
        "septembrie",
        "octombrie",
        "noiembrie",
        "decembrie",
    ];
    return `${day} ${monthNames[date.getMonth()]} ${date.getFullYear()}`;
}
</script>
