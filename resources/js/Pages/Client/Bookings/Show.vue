<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-5xl tw-mx-auto tw-px-4 md:tw-px-6 tw-py-6 tw-space-y-6">
            <!-- Title + status -->
            <header class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                <div>
                    <h1 class="tw-text-2xl md:tw-text-3xl tw-font-bold tw-text-gray-900">Rezervare #{{ booking.id }}
                    </h1>
                    <p class="tw-mt-1 tw-text-sm tw-text-gray-500">
                        Interval: <span class="tw-font-medium tw-text-gray-800">{{ prettyDate(booking.start_at)
                            }}</span>
                        &nbsp;–&nbsp;
                        <span class="tw-font-medium tw-text-gray-800">{{ prettyDate(booking.end_at) }}</span>
                    </p>
                </div>

                <span
                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-border tw-px-3 tw-py-1.5 tw-text-xs tw-font-semibold"
                    :class="statusClass(booking.status)">
                    <span class="tw-w-2 tw-h-2 tw-rounded-full" :class="statusDot(booking.status)"></span>
                    {{ prettyStatus(booking.status) }}
                </span>
            </header>

            <!-- BREADCRUMB-STYLE STEPPER -->
            <nav class="tw-relative">
                <div class="tw-flex tw-gap-3 tw-overflow-x-auto tw-py-2 tw-px-1 tw-scrollbar-thin"
                    style="scrollbar-gutter: stable">
                    <button v-for="(s, idx) in steps" :key="s.key" class="tw-shrink-0 tw-w-[260px] md:tw-w-auto tw-text-left tw-rounded-2xl tw-border tw-px-4 tw-py-3 tw-transition
                   tw-flex tw-items-start tw-gap-3 focus:tw-ring-2 focus:tw-ring-indigo-600" :class="[
                    s.completed ? 'tw-bg-green-50 tw-border-green-200'
                        : s.active ? 'tw-bg-indigo-50 tw-border-indigo-200'
                            : 'tw-bg-gray-50 tw-border-gray-200'
                ]" :disabled="!s.clickable" @click="s.clickable && scrollToId(s.anchor)">
                        <span
                            class="tw-h-8 tw-w-8 tw-rounded-full tw-grid tw-place-items-center tw-border tw-font-semibold"
                            :class="s.completed ? 'tw-bg-green-600 tw-border-green-600 tw-text-white'
                                : s.active ? 'tw-bg-indigo-600 tw-border-indigo-600 tw-text-white'
                                    : 'tw-bg-white tw-text-gray-700'">
                            <span v-if="s.completed">✓</span>
                            <span v-else>{{ idx + 1 }}</span>
                        </span>
                        <div class="tw-min-w-0">
                            <p class="tw-text-[11px] tw-tracking-wider tw-font-bold tw-uppercase" :class="s.completed ? 'tw-text-green-700'
                                : s.active ? 'tw-text-indigo-700'
                                    : 'tw-text-gray-500'">
                                Pasul {{ idx + 1 }}
                            </p>
                            <p class="tw-text-sm tw-font-semibold tw-truncate" :class="s.completed ? 'tw-text-green-800'
                                : s.active ? 'tw-text-indigo-800'
                                    : 'tw-text-gray-600'">
                                {{ s.title }}
                            </p>
                        </div>
                    </button>
                </div>
            </nav>

            <!-- PAS 1: Detalii & Acceptare -->
            <section :id="ids.details" class="tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm tw-p-5 md:tw-p-6">
                <div class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                    <div>
                        <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900">Detalii rezervare & acceptare</h2>
                        <p class="tw-text-sm tw-text-gray-600 tw-mt-1">
                            Verifică informațiile de rezervare și acceptă pentru a continua.
                        </p>
                    </div>
                    <span v-if="!canAccept"
                        class="tw-text-xs tw-font-medium tw-text-green-700 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-full tw-px-2.5 tw-py-1">
                        Pas finalizat
                    </span>
                </div>

                <dl class="tw-mt-4 tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4">
                    <div>
                        <dt class="tw-text-xs tw-font-semibold tw-text-gray-500 uppercase">Proprietar</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ booking.owner_name || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="tw-text-xs tw-font-semibold tw-text-gray-500 uppercase">Mașină</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ booking.car_label || '—' }}</dd>
                    </div>
                    <div>
                        <dt class="tw-text-xs tw-font-semibold tw-text-gray-500 uppercase">Preț total</dt>
                        <dd class="tw-mt-1 tw-text-sm tw-text-gray-900">{{ formatMoney(booking.total_price,
                            booking.currency || 'RON') }}</dd>
                    </div>
                </dl>

                <div class="tw-mt-5 tw-flex tw-flex-wrap tw-gap-2">
                    <button v-if="canAccept" @click="acceptBooking"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-bg-gray-900 tw-text-white tw-px-4 tw-py-2 tw-text-sm hover:tw-bg-black">
                        Acceptă rezervarea
                    </button>
                    <inertia-link :href="route('user.bookings.contract.show', { booking: booking.id })"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-bg-gray-200 tw-px-4 tw-py-2 tw-text-sm hover:tw-bg-gray-300">
                        Deschide contractul
                    </inertia-link>
                </div>
            </section>

            <!-- PAS 2: Contract -->
            <section :id="ids.contract" class="tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm tw-p-5 md:tw-p-6">
                <div class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                    <div>
                        <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900">Contract</h2>
                        <p class="tw-text-sm tw-text-gray-600 tw-mt-1">
                            Citește și semnează contractul. După semnare de către ambele părți, mergi la pasul următor.
                        </p>
                    </div>
                    <span v-if="contractFullySigned"
                        class="tw-text-xs tw-font-medium tw-text-green-700 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-full tw-px-2.5 tw-py-1">
                        Semnat
                    </span>
                </div>

                <div class="tw-mt-4 tw-flex tw-flex-wrap tw-gap-2">
                    <inertia-link :href="route('user.bookings.contract.show', { booking: booking.id })"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-bg-gray-200 tw-px-4 tw-py-2 tw-text-sm hover:tw-bg-gray-300">
                        Vezi contractul
                    </inertia-link>

                    <inertia-link v-if="canOpenContract && !contractFullySigned"
                        :href="route('user.bookings.contract.show', { booking: booking.id })"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-bg-indigo-600 tw-text-white tw-px-4 tw-py-2 tw-text-sm hover:tw-bg-indigo-700">
                        Semnează contractul
                    </inertia-link>
                </div>
            </section>

            <!-- PAS 3: Check-in -->
            <section :id="ids.checkin" class="tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm tw-p-5 md:tw-p-6">
                <div class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                    <div>
                        <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900">Check-in</h2>
                        <p class="tw-text-sm tw-text-gray-600 tw-mt-1">
                            Check-in-ul se activează în prima zi a rezervării ({{ prettyDate(booking.start_at) }}).
                        </p>
                    </div>
                    <span class="tw-text-xs tw-font-medium tw-rounded-full tw-px-2.5 tw-py-1"
                        :class="canCheckIn ? 'tw-text-indigo-700 tw-bg-indigo-50 tw-border tw-border-indigo-200' : 'tw-text-gray-600 tw-bg-gray-50 tw-border tw-border-gray-200'">
                        {{ canCheckIn ? 'Disponibil' : 'Blocat până la start' }}
                    </span>
                </div>

                <div class="tw-mt-4">
                    <button type="button" @click="goToCheckIn" :disabled="!canCheckIn"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-px-4 tw-py-2 tw-text-sm tw-font-medium
             tw-focus:outline-none tw-focus-visible:tw-ring-2 tw-focus-visible:tw-ring-indigo-500
             tw-bg-gray-900 tw-text-white hover:tw-bg-black
             disabled:tw-bg-gray-100 disabled:tw-text-gray-500 disabled:tw-cursor-not-allowed disabled:tw-border-gray-200">
                        Deschide check-in
                    </button>
                </div>
            </section>

            <!-- PAS 4: Check-out -->
            <section :id="ids.checkout" class="tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm tw-p-5 md:tw-p-6">
                <div class="tw-flex tw-items-start tw-justify-between tw-gap-4">
                    <div>
                        <h2 class="tw-text-lg tw-font-semibold tw-text-gray-900">Check-out</h2>
                        <p class="tw-text-sm tw-text-gray-600 tw-mt-1">
                            Check-out-ul se activează la începutul ultimei zile ({{ prettyDate(booking.end_at) }}).
                        </p>
                    </div>
                    <span class="tw-text-xs tw-font-medium tw-rounded-full tw-px-2.5 tw-py-1"
                        :class="canCheckOut ? 'tw-text-indigo-700 tw-bg-indigo-50 tw-border tw-border-indigo-200' : 'tw-text-gray-600 tw-bg-gray-50 tw-border tw-border-gray-200'">
                        {{ canCheckOut ? 'Disponibil' : 'Blocat până la final' }}
                    </span>
                </div>

                <div class="tw-mt-4">
                    <button type="button" @click="goToCheckOut" :disabled="!canCheckOut"
                        class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-xl tw-border tw-px-4 tw-py-2 tw-text-sm tw-font-medium
             tw-focus:outline-none tw-focus-visible:tw-ring-2 tw-focus-visible:tw-ring-indigo-500
             tw-bg-gray-900 tw-text-white hover:tw-bg-black
             disabled:tw-bg-gray-100 disabled:tw-text-gray-500 disabled:tw-cursor-not-allowed disabled:tw-border-gray-200">
                        Deschide check-out
                    </button>
                </div>
            </section>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue'
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

/**
 * EXPECTED PROPS (adjust if your names differ):
 * - booking: {
 *     id,
 *     status,                 // string
 *     total_price, currency,  // number, 'RON'|'EUR'..
 *     start_at, end_at,       // ISO strings
 *     owner_name, car_label,  // optional strings for display
 *   }
 */
const props = defineProps({
    booking: { type: Object, required: true },
})

/* ---------------------------------------------------------
   Helpers
--------------------------------------------------------- */
const S = computed(() => (props.booking.status || '').toString().toLowerCase())

const ids = {
    details: 'step-details',
    contract: 'step-contract',
    checkin: 'step-checkin',
    checkout: 'step-checkout',
}

const normalizeDate = (d) => (d ? new Date(d) : null)
const startDate = computed(() => normalizeDate(props.booking.start_at))
const endDate = computed(() => normalizeDate(props.booking.end_at))
const now = () => new Date()

// “Completed” contract if status already beyond signing
const contractFullySigned = computed(() =>
    [
        'contract_signed',
        'payment_pending',
        'paid',
        'checkin_submitted',
        'checkin_approved',
        'checkout_submitted',
        'checkout_approved',
        'completed',
    ].includes(S.value)
)

const canOpenContract = computed(() =>
    [
        'contract_pending',
        'contract_partially_signed',
        'contract_signed',
        'payment_pending',
        'paid',
        'checkin_submitted',
        'checkin_approved',
        'checkout_submitted',
        'checkout_approved',
        'completed',
    ].includes(S.value)
)

// Owner acceptance step (tune if your flow differs)
const canAccept = computed(() => ['pending'].includes(S.value))

// Check-in available on/after start date and only once contract handled
const canCheckIn = computed(() => {
    const dateOk = startDate.value ? now() >= stripToDay(startDate.value) : true
    const statusOk = ['contract_signed', 'payment_pending', 'paid', 'checkin_submitted', 'checkin_approved'].includes(S.value)
    return dateOk && statusOk
})

// Check-out available on/after end date morning (or earlier if you prefer)
const canCheckOut = computed(() => {
    const dateOk = endDate.value ? now() >= stripToDay(endDate.value) : true
    const statusOk = ['paid', 'checkin_approved', 'checkout_submitted', 'checkout_approved', 'completed'].includes(S.value)
    return dateOk && statusOk
})

const goToCheckIn = () => {
    if (!canCheckIn.value && canCheckIn !== true) return
    router.visit(route('user.bookings.checkin', { bookingId: booking.id }))
}

const goToCheckOut = () => {
    if (!canCheckOut.value && canCheckOut !== true) return
    router.visit(route('user.bookings.checkout', { booking: booking.id }))
}

function stripToDay(d) {
    const x = new Date(d)
    x.setHours(0, 0, 0, 0)
    return x
}

function scrollToId(anchor) {
    const el = document.getElementById(anchor)
    if (!el) return
    window.scrollTo({ top: el.offsetTop - 16, behavior: 'smooth' })
}

function formatMoney(amount, cur = 'RON') {
    try {
        return new Intl.NumberFormat('ro-RO', { style: 'currency', currency: cur }).format(amount ?? 0)
    } catch { return `${amount ?? 0} ${cur}` }
}

function prettyDate(iso) {
    if (!iso) return '—'
    const d = new Date(iso)
    return d.toLocaleString('ro-RO', {
        weekday: 'short',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}

function prettyStatus(s) {
    const m = (s || '').toString().toLowerCase().replaceAll('_', ' ')
    return m.charAt(0).toUpperCase() + m.slice(1)
}

function statusClass(status) {
    const s = (status ?? '').toString().toLowerCase()
    const GREEN = new Set(['owner_accepted', 'checkin_approved', 'contract_signed', 'paid', 'checkout_approved', 'completed'])
    const YELLOW = new Set(['pending', 'checkin_submitted', 'contract_pending', 'contract_partially_signed', 'payment_pending', 'checkout_submitted'])
    const RED = new Set(['rejected', 'cancelled', 'checkin_rejected', 'checkout_rejected', 'disputed'])
    return {
        'tw-bg-green-100 tw-border-green-300 tw-text-green-800': GREEN.has(s),
        'tw-bg-yellow-100 tw-border-yellow-300 tw-text-yellow-800': YELLOW.has(s),
        'tw-bg-red-100 tw-border-red-300 tw-text-red-800': RED.has(s),
        'tw-bg-gray-100 tw-border-gray-300 tw-text-gray-800': !GREEN.has(s) && !YELLOW.has(s) && !RED.has(s),
    }
}
function statusDot(status) {
    const s = (status ?? '').toString().toLowerCase()
    return {
        'tw-bg-green-600': ['owner_accepted', 'checkin_approved', 'contract_signed', 'paid', 'checkout_approved', 'completed'].includes(s),
        'tw-bg-yellow-500': ['pending', 'checkin_submitted', 'contract_pending', 'contract_partially_signed', 'payment_pending', 'checkout_submitted'].includes(s),
        'tw-bg-red-500': ['rejected', 'cancelled', 'checkin_rejected', 'checkout_rejected', 'disputed'].includes(s),
        'tw-bg-gray-500': true,
    }
}

/* ---------------------------------------------------------
   Stepper model
--------------------------------------------------------- */
const steps = computed(() => ([
    {
        key: 'details',
        title: 'Detalii & acceptare',
        anchor: ids.details,
        active: canAccept.value || ['owner_accepted'].includes(S.value),
        completed: !canAccept.value && !['rejected', 'cancelled'].includes(S.value),
        clickable: true,
    },
    {
        key: 'contract',
        title: 'Contract',
        anchor: ids.contract,
        active: canOpenContract.value && !contractFullySigned.value,
        completed: contractFullySigned.value,
        clickable: canOpenContract.value,
    },
    {
        key: 'checkin',
        title: 'Check-in',
        anchor: ids.checkin,
        active: canCheckIn.value && ['paid', 'contract_signed', 'checkin_submitted', 'checkin_approved'].includes(S.value),
        completed: ['checkin_submitted', 'checkin_approved', 'checkout_submitted', 'checkout_approved', 'completed'].includes(S.value),
        clickable: canCheckIn.value,
    },
    {
        key: 'checkout',
        title: 'Check-out',
        anchor: ids.checkout,
        active: canCheckOut.value && ['paid', 'checkin_approved', 'checkout_submitted'].includes(S.value),
        completed: ['checkout_submitted', 'checkout_approved', 'completed'].includes(S.value),
        clickable: canCheckOut.value,
    },
]))

/* ---------------------------------------------------------
   Actions (adjust routes if your names differ)
--------------------------------------------------------- */
function acceptBooking() {
    // update this route name if your accept URL is different
    router.post(route('user.bookings.accept', { booking: props.booking.id }), {}, {
        preserveScroll: true,
    })
}
</script>
