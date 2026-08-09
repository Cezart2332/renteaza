<template>
    <div class="tw-flex tw-flex-col tw-gap-10">
        <div class="tw-my-8">
            <!-- Header -->
            <div class="sm:tw-flex sm:tw-items-center tw-bg-white tw-p-4 tw-rounded-t-2xl tw-shadow-lg">
                <div class="sm:tw-flex-auto tw-flex tw-flex-col tw-gap-2">
                    <h1 class="tw-text-base tw-font-semibold tw-text-gray-900 md:tw-text-2xl">
                        {{ title }}
                    </h1>
                    <span class="tw-text-sm tw-text-gray-500">{{
                        description
                    }}</span>
                </div>
            </div>

            <!-- Search -->
            <div v-if="searchable" class="sm:tw-flex sm:tw-items-center tw-gap-2 tw-bg-white tw-p-4">
                <div class="tw-relative tw-w-full sm:tw-w-1/3">
                    <input v-model="searchQuery" type="text" placeholder="Caută..."
                        class="tw-borderv focus:tw-text-black tw-text-black tw-border-gray-300 tw-rounded-full tw-pl-10 tw-pr-4 tw-py-2 tw-w-full focus:tw-ring-indigo-500 focus:tw-border-indigo-500 sm:tw-text-sm" />
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="tw-absolute tw-left-3 tw-top-1/4 tw--translate-y-1/2 tw-h-5 tw-w-5 tw-text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="m21 21-5.197-5.197M15.803 15.803a7.5 7.5 0 1 0-10.607-10.607 7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>
            <div class="tw-max-h-[70vh] tw-overflow-y-auto sm:tw-max-h-none sm:tw-overflow-visible">
                <!-- Table -->
                <table class="tw-bg-white tw-w-full tw-divide-y tw-divide-gray-300">
                    <thead class="tw-bg-gray-50">
                        <tr>
                            <th v-for="col in columns" :key="col.key" @click="handleSort(col)"
                                class="tw-px-6 tw-py-3 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-700">
                                <div class="tw-flex">
                                    <span>{{ col.label }}</span>
                                    <ChevronUpIcon v-if="col.sort && sortOrder === 'asc'"
                                        class="tw-w-4 tw-h-4 tw-inline tw-ml-1 tw-text-gray-600" />

                                    <ChevronDownIcon v-else-if="
                                        col.sort && sortOrder === 'desc'
                                    " class="tw-w-4 tw-h-4 tw-inline tw-ml-1 tw-text-gray-600" />
                                </div>
                            </th>
                            <th v-if="expandable && editable"
                                class="tw-px-3 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900">
                                Acțiuni
                            </th>
                            <th v-if="expandable"
                                class="tw-px-3 tw-py-3.5 tw-text-left tw-text-sm tw-font-semibold tw-text-gray-900">
                                Detalii
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tw-bg-white tw-divide-y tw-divide-gray-200 tw-shadow-lg">
                        <template v-for="(row, index) in rows.data" :key="row.id">
                            <tr>
                                <td v-for="col in columns" :key="col.key"
                                    class="tw-px-3 tw-py-4 tw-text-sm tw-text-gray-700">
                                    <template v-if="col.status === true">
                                        <div :class="statusClass(row[col.key])"
                                            class="tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-4 tw-py-1 tw-w-24 tw-rounded-full tw-text-sm tw-border tw-font-semibold">
                                            <div :class="statusDot(row[col.key])" class="tw-p-1.5 tw-rounded-full">
                                            </div>
                                            {{ row[col.key] }}
                                        </div>
                                    </template>
                                    <template v-else-if="col.rating === true">
                                        <div class="tw-flex tw-items-center">
                                            <StarRating :rating="row[col.key]" />
                                            <span class="tw-ml-2 tw-font-semibold">{{ row[col.key] }}/5</span>
                                        </div>
                                    </template>
                                    <template v-else>
                                        {{ row[col.key] ? row[col.key] : "-" }}
                                    </template>
                                </td>

                                <td v-if="expandable || editable"
                                    class="tw-whitespace-nowrap tw-py-4 tw-pl-3 tw-pr-4 tw-text-right tw-text-sm tw-font-medium sm:tw-pr-3">
                                    <div class="tw-flex tw-gap-2 tw-justify-start">
                                        <button v-if="expandable" @click="toggle(index)"
                                            class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-orange-100 tw-cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="tw-size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                        <div v-if="editable">
                                            <div
                                                class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-orange-50 tw-cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="tw-size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </div>
                                            <div class="tw-px-2 tw-py-1 tw-rounded-lg tw-bg-red-50 tw-cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="tw-size-6 tw-text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="expandable && expandedRow === index"
                                class="tw-bg-gray-50 tw-transition-all tw-duration-300">
                                <td colspan="100%" class="tw-px-6 tw-py-6">
                                    <div class="tw-flex tw-flex-row tw-justify-between">
                                        <div class="tw-border-l-4 tw-border-blue-500 tw-pl-4 tw-space-y-4">
                                            <div v-for="detail in row.details" :key="detail.label">
                                                <h4 class="tw-text-sm tw-font-semibold tw-text-gray-800 tw-mb-1">
                                                    {{ detail.label }}
                                                </h4>
                                                <p class="tw-text-gray-700">
                                                    {{ detail.value }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="tw-mt-6 tw-pt-4 tw-flex tw-items-center tw-gap-3">
                                            <!-- Contract disponibil -->
                                            <inertia-link v-if="showContractCTA(row.status)"
                                                :href="route('user.bookings.contract.show', { booking: row.id })"
                                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-rounded-xl tw-border tw-text-sm tw-bg-white hover:tw-bg-gray-50">
                                                Deschide contractul
                                            </inertia-link>

                                            <!-- Checkout disponibil -->
                                            <inertia-link v-if="showCheckoutCTA(row.status)"
                                                :href="route('user.bookings.checkout', { booking: row.id })"
                                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-rounded-xl tw-border tw-text-sm tw-bg-white hover:tw-bg-gray-50">
                                                Deschide checkout
                                            </inertia-link>

                                            <inertia-link v-if="showPaymentCTA(row.status)"
                                                :href="route('user.bookings.payment.show', { booking: row.id })"
                                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-rounded-xl tw-border tw-text-sm tw-bg-white hover:tw-bg-gray-50">
                                                Plătește
                                            </inertia-link>

                                            <!-- Check-in (după ce owner a acceptat / dacă a fost respins și trebuie refăcut) -->
                                            <inertia-link v-else-if="showCheckInCTA(row.status)"
                                                :href="route('user.bookings.checkin', { bookingId: row.id })"
                                                class="tw-inline-flex tw-items-center tw-px-3 tw-py-2 tw-rounded-xl tw-border tw-text-sm tw-bg-white hover:tw-bg-gray-50">
                                                Deschide check-in
                                            </inertia-link>

                                            <!-- În review la admin -->
                                            <span v-else-if="waitingAdmin(row.status)"
                                                class="tw-text-sm tw-text-yellow-700 tw-bg-yellow-50 tw-border tw-border-yellow-200 tw-rounded-lg tw-px-3 tw-py-1.5">
                                                Pozele sunt în review la administrator.
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div v-if="pagination" class="tw-my-4">
                <WebPagination :links="rows.links" />
            </div>
        </div>
    </div>
</template>

<script setup>
import StarRating from "@/Pages/Owner/Reviews/StarRating.vue";
import WebPagination from "./WebPagination.vue";
import { ChevronDownIcon, ChevronUpIcon } from "@heroicons/vue/24/outline";
import { ref, watch } from "vue";
import { debounce } from "lodash";

const props = defineProps({
    title: String,
    description: String,
    columns: Array,
    rows: Array,
    searchable: Boolean,
    pagination: Boolean,
    editable: Boolean,
    expandable: Boolean,
    expandedRows: Array,
    prevSearch: String,
});

const showPaymentCTA = (status) => {
    const s = (status ?? '').toString().toLowerCase()
    return s === 'payment_pending' || s === 'contract_signed' // dacă nu ai mutat încă statusul
}

const showCheckoutCTA = (status) => {
    const s = (status ?? '').toString().toLowerCase()
    return [
        'contract_signed',
        'payment_pending',
        'paid',
        'checkout_submitted',
        'checkout_approved',
        'checkout_rejected',
        'completed',
    ].includes(s)
}

function statusClass(status) {
    const s = (status ?? '').toString().toLowerCase();

    // ✅ positive / done-ish
    const GREEN = new Set([
        'active', 'approved', 'accepted',        // legacy
        'owner_accepted',
        'checkin_approved',
        'contract_signed',
        'paid',
        'checkout_approved',
        'completed',
        'checked_in'                              // legacy fallback
    ]);

    // ⏳ waiting / in-progress
    const YELLOW = new Set([
        'pending',
        'checkin_submitted',
        'contract_pending',
        'contract_partially_signed',
        'payment_pending',
        'checkout_submitted'
    ]);

    // ❌ error / stop
    const RED = new Set([
        'rejected',
        'cancelled', 'canceled',                  // both spellings
        'checkin_rejected',
        'checkout_rejected',
        'disputed'
    ]);

    // ⚪ neutral/disabled
    const GRAY = new Set([
        'inactive'
    ]);

    return {
        'tw-bg-green-100 tw-border-green-400 tw-text-green-800': GREEN.has(s),
        'tw-bg-yellow-100 tw-border-yellow-400 tw-text-yellow-800': YELLOW.has(s),
        'tw-bg-red-100 tw-border-red-400 tw-text-red-800': RED.has(s),
        'tw-bg-gray-200 tw-border-gray-400 tw-text-gray-800': GRAY.has(s) || (!GREEN.has(s) && !YELLOW.has(s) && !RED.has(s)),
    };
}

const normalize = (s) => (s ?? '').toString().toLowerCase()

const showContractCTA = (status) => {
    const s = normalize(status)
    return [
        'contract_pending',
        'contract_partially_signed',
        'contract_signed',
        'payment_pending',
        'paid',
        'checkout_submitted',
        'checkout_approved',
        'checkout_rejected',
        'completed',
    ].includes(s)
}

const showCheckInCTA = (status) => {
    const s = normalize(status)
    return ['owner_accepted', 'checkin_rejected'].includes(s)
}

const waitingAdmin = (status) => normalize(status) === 'checkin_submitted'


function statusDot(status) {
    return {
        "tw-bg-green-500":
            status === "active" ||
            status === "approved" ||
            status === "Accepted" ||
            status === "Approved",
        "tw-bg-yellow-500": status === "pending" || status === "Pending",
        "tw-bg-gray-500": status === "inactive",
        "tw-bg-red-500": status === "rejected" || status === "Cancelled",
    };
}

const expandedRow = ref(null);

function toggle(index) {
    expandedRow.value = expandedRow.value === index ? null : index;
}

const emit = defineEmits(["sort-changed", "search-changed"]);
const sortKey = ref("reviewed_at");
const sortOrder = ref("desc");

function handleSort(col) {
    if (!col.sort) return;
    if (sortKey.value === col.key) {
        sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    } else {
        sortKey.value = col.key;
        sortOrder.value = "asc";
    }

    emit("sort-changed", { key: sortKey.value, order: sortOrder.value });
}

const searchQuery = ref(props.prevSearch);

watch(
    searchQuery,
    debounce((value) => {
        emit("search-changed", value);
    }, 300)
);
</script>
