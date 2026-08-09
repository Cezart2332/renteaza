<template>
    <OwnerDashboardLayout>
        <div class="tw-px-4 sm:tw-px-8 tw-py-6 tw-space-y-6">

            <!-- Header + Controls -->
            <div
                class="tw-flex tw-flex-col md:tw-flex-row tw-items-start md:tw-items-center tw-justify-between tw-gap-4">
                <div>
                    <h1 class="tw-text-2xl tw-font-semibold tw-text-gray-900">Rezervările mele</h1>
                    <p class="tw-text-sm tw-text-gray-500">Vezi și gestionează toate rezervările într-un singur loc.</p>
                </div>

                <div class="tw-flex tw-w-full md:tw-w-auto tw-items-center tw-gap-2">
                    <!-- Search -->
                    <div class="tw-relative tw-flex-1 md:tw-w-72">
                        <input v-model="search" type="text" placeholder="Caută după proprietar sau mașină…"
                            class="tw-w-full tw-rounded-xl tw-border tw-border-gray-300 tw-bg-white tw-pl-10 tw-pr-3 tw-py-2.5 tw-text-sm tw-text-gray-900 focus:tw-ring-2 focus:tw-ring-gray-900"
                            @input="onSearch" />
                        <svg class="tw-absolute tw-left-3 tw-top-1/2 -tw-translate-y-1/2 tw-h-5 tw-w-5 tw-text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m21 21-5.197-5.197M15.803 15.803a7.5 7.5 0 1 0-10.607-10.607 7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>

                    <!-- Sort -->
                    <div class="tw-relative">
                        <select v-model="sort.key"
                            class="tw-appearance-none tw-rounded-xl tw-border tw-border-gray-300 tw-bg-white tw-pl-3 tw-pr-8 tw-py-2.5 tw-text-sm tw-text-gray-900 focus:tw-ring-2 focus:tw-ring-gray-900"
                            @change="fetch">
                            <option disabled value="">Sortare după…</option>
                            <option value="start">Data începerii</option>
                            <option value="end">Data finalizării</option>
                        </select>
                        <button
                            class="tw-ml-2 tw-inline-flex tw-items-center tw-justify-center tw-h-10 tw-w-10 tw-rounded-xl tw-border tw-border-gray-300 hover:tw-bg-gray-50"
                            @click="toggleOrder" :title="sort.order === 'asc' ? 'Crescător' : 'Descrescător'">
                            <svg v-if="sort.order === 'asc'" class="tw-h-5 tw-w-5 tw-text-gray-700" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor">
                                <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    d="m4 12 4-4 4 4M8 8v8m6 0h6" />
                            </svg>
                            <svg v-else class="tw-h-5 tw-w-5 tw-text-gray-700" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor">
                                <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    d="m12 16 4-4 4 4M16 12v8M4 4h6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status quick filter chips (optional) -->
            <div class="tw-flex tw-flex-wrap tw-gap-2">
                <button v-for="chip in statusChips" :key="chip.value"
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-border tw-px-3 tw-py-1.5 tw-text-xs tw-font-medium transition"
                    :class="statusChipClass(chip.value)" @click="toggleStatusFilter(chip.value)">
                    <span class="tw-w-2 tw-h-2 tw-rounded-full" :class="statusDotColor(chip.value)"></span>
                    {{ chip.label }}
                </button>
            </div>

            <!-- Cards list -->
            <div class="tw-space-y-3">
                <TransitionGroup name="list" tag="div" class="tw-space-y-3">
                    <div v-for="item in filteredRows" :key="item.id" @click="redirectToInfo(item.id)"
                        class="tw-group tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm hover:tw-shadow-md tw-transition-shadow tw-p-4 sm:tw-p-5 tw-cursor-pointer">
                        <!-- Top line: car + status -->
                        <div class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                            <div class="tw-flex tw-items-center tw-gap-3 min-w-0">
                                <div
                                    class="tw-h-10 tw-w-10 tw-rounded-xl tw-bg-gray-100 tw-grid tw-place-items-center tw-text-gray-600 tw-flex-shrink-0">
                                    <!-- car icon -->
                                    <svg class="tw-h-5 tw-w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 13h18l-1.2-3.6a3 3 0 0 0-2.84-2.04H7.04A3 3 0 0 0 4.2 9.4L3 13Z" />
                                        <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 16h.01M17 16h.01M5 20h14" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="tw-text-base tw-font-semibold tw-text-gray-900 tw-truncate">
                                        {{ item.car }}
                                    </h3>
                                    <p class="tw-text-sm tw-text-gray-600 tw-truncate">
                                        Proprietar: <span class="tw-font-medium">{{ item.owner }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Status pill -->
                            <div class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-border tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold"
                                :class="statusClass(item.status)" :title="item.status">
                                <span class="tw-w-2 tw-h-2 tw-rounded-full" :class="statusDot(item.status)"></span>
                                {{ prettyStatus(item.status) }}
                            </div>
                        </div>

                        <!-- Dates + meta -->
                        <div class="tw-mt-3 tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-3">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-text-sm">
                                <svg class="tw-h-4 tw-w-4 tw-text-gray-500" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3M4 11h16M6 21h12a2 2 0 0 0 2-2v-8H4v8a2 2 0 0 0 2 2Z" />
                                </svg>
                                <span class="tw-text-gray-500">Start:</span>
                                <span class="tw-font-medium tw-text-gray-900">{{ item.start }}</span>
                            </div>

                            <div class="tw-flex tw-items-center tw-gap-2 tw-text-sm">
                                <svg class="tw-h-4 tw-w-4 tw-text-gray-500" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3M4 11h16M6 21h12a2 2 0 0 0 2-2v-8H4v8a2 2 0 0 0 2 2Z" />
                                </svg>
                                <span class="tw-text-gray-500">Sfârșit:</span>
                                <span class="tw-font-medium tw-text-gray-900">{{ item.end }}</span>
                            </div>

                            <div class="tw-flex tw-items-center tw-gap-2 tw-text-sm">
                                <svg class="tw-h-4 tw-w-4 tw-text-gray-500" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m6-6H6" />
                                </svg>
                                <span class="tw-text-gray-500">ID:</span>
                                <span class="tw-font-medium tw-text-gray-900">#{{ item.id }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="tw-mt-4 tw-flex tw-flex-wrap tw-gap-2">
                            <!-- Contract -->
                            <inertia-link v-if="showContractCTA(item.status)"
                                :href="route('user.bookings.contract.show', { booking: item.id })"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-px-3 tw-py-2 tw-text-sm hover:tw-bg-gray-50">
                                <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 7h10M7 11h10M7 15h6M5 3h10a2 2 0 0 1 2 2v14l-4-2-4 2-4-2V5a2 2 0 0 1 2-2Z" />
                                </svg>
                                Contract
                            </inertia-link>

                            <!-- Check-in -->
                            <inertia-link v-if="showCheckInCTA(item.status)"
                                :href="route('user.bookings.checkin', { bookingId: item.id })"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-px-3 tw-py-2 tw-text-sm hover:tw-bg-gray-50">
                                <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 12h16M12 4v16" />
                                </svg>
                                Check-in
                            </inertia-link>

                            <!-- Payment -->
                            <inertia-link v-if="showPaymentCTA(item.status)"
                                :href="route('user.bookings.payment.show', { booking: item.id })"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-px-3 tw-py-2 tw-text-sm hover:tw-bg-gray-50">
                                <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7h18M3 11h18M7 15h6" />
                                </svg>
                                Plătește
                            </inertia-link>

                            <!-- Checkout -->
                            <inertia-link v-if="showCheckoutCTA(item.status)"
                                :href="route('user.bookings.checkout', { booking: item.id })"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-px-3 tw-py-2 tw-text-sm hover:tw-bg-gray-50">
                                <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 3v18m9-9H3" />
                                </svg>
                                Checkout
                            </inertia-link>
                        </div>
                    </div>
                </TransitionGroup>

                <!-- Empty state -->
                <div v-if="!filteredRows.length" class="tw-bg-white tw-border tw-rounded-2xl tw-p-10 tw-text-center">
                    <div
                        class="tw-mx-auto tw-h-10 tw-w-10 tw-rounded-xl tw-bg-gray-100 tw-grid tw-place-items-center tw-mb-3">
                        <svg class="tw-h-5 tw-w-5 tw-text-gray-500" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7h18M3 11h18M7 15h6" />
                        </svg>
                    </div>
                    <p class="tw-text-gray-700 tw-font-medium">Nicio rezervare găsită</p>
                    <p class="tw-text-sm tw-text-gray-500">Încearcă să schimbi filtrele sau căutarea.</p>
                </div>
            </div>

            <!-- Pagination (dacă trimiți links din backend) -->
            <div v-if="rows?.links" class="tw-pt-2">
                <WebPagination :links="rows.links" />
            </div>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue'
import WebPagination from '@/Components/WebPagination.vue'
import { ref, computed } from 'vue'
import { debounce } from 'lodash'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    stats: Array,
    upcomingList: Array,
    rows: { type: [Array, Object], required: true }, // poate fi array simplu sau pagination {data,links}
    prevSearch: String,
})

/* ----------------- Data & Controls ------------------ */
const sort = ref({ key: 'start', order: 'desc' })
const search = ref(props.prevSearch || '')
const activeStatusFilters = ref(new Set())

const statusChips = [
    { value: 'pending', label: 'În așteptare' },
    { value: 'owner_accepted', label: 'Acceptată' },
    { value: 'checkin_submitted', label: 'Check-in trimis' },
    { value: 'checkin_approved', label: 'Check-in ok' },
    { value: 'contract_pending', label: 'Contract' },
    { value: 'payment_pending', label: 'De plată' },
    { value: 'paid', label: 'Plătită' },
    { value: 'checkout_submitted', label: 'Checkout trimis' },
    { value: 'completed', label: 'Finalizată' },
]

/* ----------------- Helpers ------------------ */
const normalize = (v) => (v ?? '').toString().toLowerCase()

const showPaymentCTA = (status) => {
    const s = normalize(status)
    return s === 'payment_pending' || s === 'contract_signed'
}
const showCheckoutCTA = (status) => {
    const s = normalize(status)
    return ['contract_signed', 'payment_pending', 'paid', 'checkout_submitted', 'checkout_approved', 'checkout_rejected', 'completed'].includes(s)
}
const showContractCTA = (status) => {
    const s = normalize(status)
    return ['contract_pending', 'contract_partially_signed', 'contract_signed', 'payment_pending', 'paid', 'checkout_submitted', 'checkout_approved', 'checkout_rejected', 'completed'].includes(s)
}
const showCheckInCTA = (status) => {
    const s = normalize(status)
    return ['owner_accepted', 'checkin_rejected'].includes(s)
}

function prettyStatus(s) {
    const map = {
        pending: 'În așteptare',
        owner_accepted: 'Acceptată',
        checkin_submitted: 'Check-in trimis',
        checkin_approved: 'Check-in aprobat',
        checkin_rejected: 'Check-in respins',
        contract_pending: 'Contract',
        contract_partially_signed: 'Contract (parțial)',
        contract_signed: 'Contract semnat',
        payment_pending: 'De plată',
        paid: 'Plătită',
        checkout_submitted: 'Checkout trimis',
        checkout_approved: 'Checkout aprobat',
        checkout_rejected: 'Checkout respins',
        completed: 'Finalizată',
        rejected: 'Respinsă',
        cancelled: 'Anulată',
        disputed: 'Dispută',
    }
    return map[normalize(s)] || s
}

function statusClass(status) {
    const s = normalize(status)
    const GREEN = new Set(['approved', 'accepted', 'owner_accepted', 'checkin_approved', 'contract_signed', 'paid', 'checkout_approved', 'completed', 'checked_in'])
    const YELLOW = new Set(['pending', 'checkin_submitted', 'contract_pending', 'contract_partially_signed', 'payment_pending', 'checkout_submitted'])
    const RED = new Set(['rejected', 'cancelled', 'canceled', 'checkin_rejected', 'checkout_rejected', 'disputed'])
    const GRAY = new Set(['inactive'])

    return {
        'tw-bg-green-50 tw-text-green-700 tw-border-green-200': GREEN.has(s),
        'tw-bg-yellow-50 tw-text-yellow-700 tw-border-yellow-200': YELLOW.has(s),
        'tw-bg-red-50 tw-text-red-700 tw-border-red-200': RED.has(s),
        'tw-bg-gray-100 tw-text-gray-700 tw-border-gray-200': GRAY.has(s) || (!GREEN.has(s) && !YELLOW.has(s) && !RED.has(s)),
    }
}
function statusDot(status) {
    const s = normalize(status)
    if (['approved', 'accepted', 'owner_accepted', 'checkin_approved', 'contract_signed', 'paid', 'checkout_approved', 'completed', 'checked_in'].includes(s)) return 'tw-bg-green-500'
    if (['pending', 'checkin_submitted', 'contract_pending', 'contract_partially_signed', 'payment_pending', 'checkout_submitted'].includes(s)) return 'tw-bg-yellow-500'
    if (['rejected', 'cancelled', 'canceled', 'checkin_rejected', 'checkout_rejected', 'disputed'].includes(s)) return 'tw-bg-red-500'
    return 'tw-bg-gray-400'
}
function statusDotColor(s) {
    return statusDot(s)
}

function statusChipClass(value) {
    const active = activeStatusFilters.value.has(value)
    return active
        ? 'tw-bg-gray-900 tw-text-white tw-border-gray-900'
        : 'tw-bg-white tw-text-gray-700 tw-border-gray-200 hover:tw-bg-gray-50'
}

function toggleStatusFilter(value) {
    if (activeStatusFilters.value.has(value)) activeStatusFilters.value.delete(value)
    else activeStatusFilters.value.add(value)
}

/* ----------------- Data source adapter ------------------ */
const list = computed(() => {
    // Acceptă fie array simplu, fie {data: []}
    return Array.isArray(props.rows) ? props.rows : (props.rows?.data || [])
})

/* ----------------- Filtering + sorting client-side (vizual) ------------------ */
const filteredRows = computed(() => {
    const q = normalize(search.value)
    let arr = [...list.value]

    if (q) {
        arr = arr.filter(r => {
            const owner = normalize(r.owner)
            const car = normalize(r.car)
            return owner.includes(q) || car.includes(q)
        })
    }

    if (activeStatusFilters.value.size) {
        arr = arr.filter(r => activeStatusFilters.value.has(normalize(r.status)))
    }

    if (sort.value.key) {
        const k = sort.value.key
        const dir = sort.value.order === 'asc' ? 1 : -1
        arr.sort((a, b) => (String(a[k] || '').localeCompare(String(b[k] || ''))) * dir)
    }
    return arr
})

/* ----------------- Server sync (Inertia) ------------------ */
const onSearch = debounce(() => fetch(), 300)

function toggleOrder() {
    sort.value.order = sort.value.order === 'asc' ? 'desc' : 'asc'
    fetch()
}

function fetch() {
    router.get(
        route('user.client_bookings.index'),
        {
            key: sort.value.key,
            order: sort.value.order,
            search: search.value || undefined,
            status: Array.from(activeStatusFilters.value),
        },
        { preserveState: true, preserveScroll: true, replace: true }
    )
}

function redirectToInfo(bookingId) {
    router.get(route('user.client_bookings.show', { booking: bookingId }))
}
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
    transition: all 180ms ease;
}

.list-enter-from {
    opacity: 0;
    transform: translateY(4px);
}

.list-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
