<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-5xl tw-mx-auto tw-p-4 sm:tw-p-6 tw-space-y-6">
            <!-- Header -->
            <div class="tw-flex tw-items-start tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl sm:tw-text-3xl tw-font-semibold tw-tracking-tight">
                        Check-In — Booking #{{ booking.id }}
                    </h1>
                    <p class="tw-text-sm tw-text-gray-500">
                        Perioadă: {{ booking.start_date }} → {{ booking.end_date }} • {{ booking.days }} zile
                    </p>
                </div>
            </div>

            <!-- Grid: Client + Car -->
            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4 sm:tw-gap-6">
                <!-- Client -->
                <section class="tw-bg-white tw-shadow-sm tw-border tw-rounded-2xl tw-p-4 sm:tw-p-6">
                    <div class="tw-flex tw-items-center tw-gap-4">
                        <img v-if="booking.client?.photo" :src="booking.client.photo" alt="Client photo"
                            class="tw-w-16 tw-h-16 tw-rounded-full tw-object-cover tw-border" />
                        <div v-else
                            class="tw-w-16 tw-h-16 tw-rounded-full tw-bg-gray-200 tw-grid tw-place-items-center tw-text-gray-600 tw-font-semibold">
                            {{ initials(booking.client?.name) }}
                        </div>

                        <div class="tw-min-w-0">
                            <div class="tw-flex tw-items-center tw-gap-2">
                                <h2 class="tw-font-medium tw-text-lg tw-truncate">{{ booking.client?.name }}</h2>
                                <span
                                    class="tw-inline-flex tw-items-center tw-text-xs tw-px-2 tw-py-0.5 tw-rounded-full tw-bg-gray-100 tw-text-gray-700">
                                    {{ booking.client?.location || '—' }}
                                </span>
                            </div>
                            <div
                                class="tw-mt-1 tw-flex tw-flex-wrap tw-items-center tw-gap-2 tw-text-sm tw-text-gray-600">
                                <span class="tw-inline-flex tw-items-center tw-gap-1">
                                    <StarIcon v-for="i in 5" :key="i" class="tw-w-4 tw-h-4" :class="i <= Math.round(Number(booking.client?.rating || 0))
                                        ? 'fill-yellow-400 stroke-yellow-400'
                                        : 'fill-gray-200 stroke-gray-300'
                                        " />
                                    <span class="tw-ml-1">{{ booking.client?.rating ?? '0' }}/5</span>
                                </span>
                                <span>•</span>
                                <span>Rezervări: {{ booking.client?.reservations_count ?? 0 }}</span>
                                <span>•</span>
                                <span>Client din {{ booking.client?.created_at }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Car -->
                <section class="tw-bg-white tw-shadow-sm tw-border tw-rounded-2xl tw-p-4 sm:tw-p-6">
                    <h3 class="tw-font-medium tw-text-lg tw-mb-3">Mașină</h3>
                    <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-text-sm">
                        <div class="tw-space-y-1">
                            <p class="tw-text-gray-500">Marcă</p>
                            <p class="tw-font-medium">{{ booking.car?.brand }}</p>
                        </div>
                        <div class="tw-space-y-1">
                            <p class="tw-text-gray-500">Model</p>
                            <p class="tw-font-medium">{{ booking.car?.model }}</p>
                        </div>
                        <div class="tw-space-y-1">
                            <p class="tw-text-gray-500">An</p>
                            <p class="tw-font-medium">{{ booking.car?.year }}</p>
                        </div>
                        <div class="tw-space-y-1">
                            <p class="tw-text-gray-500">Zile</p>
                            <p class="tw-font-medium">{{ booking.days }}</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Detalii rezervare (cu mascarea câmpurilor sensibile) -->
            <section class="tw-bg-white tw-shadow-sm tw-border tw-rounded-2xl tw-p-4 sm:tw-p-6">
                <h3 class="tw-font-medium tw-text-lg tw-mb-4">Detalii rezervare</h3>
                <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4">
                    <div v-for="(item, idx) in booking.details" :key="idx" class="tw-border tw-rounded-xl tw-p-4">
                        <p class="tw-text-xs tw-uppercase tw-tracking-wide tw-text-gray-500">
                            {{ item.label }}
                        </p>
                        <div class="tw-mt-1 tw-flex tw-items-center tw-gap-2">
                            <p class="tw-font-medium tw-break-all">
                                <span v-if="item.is_sensitive && !revealed[idx]">••••••••</span>
                                <span v-else>{{ item.value }}</span>
                            </p>
                            <button v-if="item.is_sensitive" type="button"
                                class="tw-text-xs tw-px-2 tw-py-1 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                                @click="toggleReveal(idx)">
                                {{ revealed[idx] ? 'Ascunde' : 'Afișează' }}
                            </button>
                        </div>
                    </div>
                </div>
            </section>


            <div v-if="booking.status === 'checkin_submitted'"
                class="tw-mb-3 tw-text-sm tw-text-yellow-700 tw-bg-yellow-50 tw-border tw-border-yellow-200 tw-rounded-lg tw-px-3 tw-py-2">
                Pozele au fost trimise — așteaptă verificarea administratorului.
            </div>
            <div v-else-if="!allSet"
                class="tw-mb-3 tw-text-sm tw-text-gray-700 tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-lg tw-px-3 tw-py-2">
                Check-in-ul este blocat până când confirmi rezervarea.
            </div>

            <!-- Foto Check-In -->
            <section class="tw-bg-white tw-shadow-sm tw-border tw-rounded-2xl tw-p-4 sm:tw-p-6">
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
                    <h3 class="tw-font-medium tw-text-lg">Fotografii Check-In (4 necesare)</h3>
                    <span class="tw-text-xs tw-px-2 tw-py-1 tw-rounded-full" :class="allSet
                        ? 'tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-200'
                        : 'tw-bg-yellow-50 tw-text-yellow-700 tw-border tw-border-yellow-200'
                        ">
                        {{ takenCount }}/4
                    </span>
                </div>

                <p class="tw-text-sm tw-text-gray-600 tw-mb-4">
                    Recomandare: față, spate, lateral stânga, lateral dreapta. Pe mobil se
                    deschide direct camera (spate).
                </p>

                <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-4 tw-gap-3 sm:tw-gap-4">
                    <div v-for="i in 4" :key="i"
                        class="tw-relative tw-border tw-rounded-xl tw-overflow-hidden tw-bg-gray-50">
                        <!-- Preview / Placeholder -->
                        <div class="aspect-square tw-w-full">
                            <img v-if="previews[i - 1]" :src="previews[i - 1]" alt="Photo preview"
                                class="tw-w-full tw-h-full tw-object-cover" />
                            <div v-else class="tw-w-full tw-h-full tw-grid tw-place-items-center tw-text-gray-500">
                                <div class="tw-flex tw-flex-col tw-items-center">
                                    <CameraIcon class="tw-w-8 tw-h-8 tw-mb-2" />
                                    <span class="tw-text-xs">Slot {{ i }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="tw-p-2 tw-flex tw-items-center tw-justify-between tw-gap-2">
                            <button type="button"
                                class="tw-w-full tw-text-sm tw-px-3 tw-py-2 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                                @click="triggerCapture(i - 1)">
                                {{ previews[i - 1] ? 'Refă poza' : 'Fă poză' }}
                            </button>
                            <button v-if="previews[i - 1]" type="button"
                                class="tw-text-sm tw-px-3 tw-py-2 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                                @click="clearPhoto(i - 1)" aria-label="Șterge poza" title="Șterge poza">
                                Șterge
                            </button>
                        </div>

                        <!-- Hidden input -->
                        <input class="tw-sr-only" type="file" accept="image/*" capture="environment"
                            :ref="el => (fileInputs[i - 1] = el)" @change="onFileChange(i - 1, $event)" />
                    </div>
                </div>

                <!-- Submit -->
                <div class="tw-mt-6 tw-flex tw-flex-col sm:tw-flex-row tw-items-stretch sm:tw-items-center tw-gap-3">
                    <p class="tw-text-xs tw-text-gray-500 tw-flex-1">
                        Prin trimitere confirmi că ai consimțământul pentru fotografii și că
                        imaginile reflectă starea vehiculului la check-in.
                    </p>
                    <button type="button"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-px-4 tw-py-2.5 tw-rounded-xl tw-font-medium tw-border tw-shadow-sm disabled:tw-opacity-60 disabled:tw-cursor-not-allowed"
                        :class="allSet ? 'tw-bg-gray-900 tw-text-white hover:tw-bg-black' : 'tw-bg-white tw-text-gray-700 hover:tw-bg-gray-50'
                            " :disabled="!allSet || uploading" @click="submit">
                        <svg v-if="uploading" class="tw-animate-spin tw--ml-1 tw-mr-2 tw-h-4 tw-w-4 tw-text-current"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>
                        {{ uploading ? 'Se încarcă...' : 'Finalizează check-inul' }}
                    </button>
                </div>
            </section>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue'
import { ref, computed, watch, onBeforeUnmount, h } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    booking: { type: Object, required: true },
})

/** --------- Helpers UI ---------- */
const initials = (name) => {
    if (!name) return 'NA'
    const parts = String(name).trim().split(/\s+/)
    return (parts[0]?.[0] || '') + (parts[1]?.[0] || '')
}

/** --------- Sensitive fields toggle ---------- */
const revealed = ref([])
watch(
    () => props.booking?.details,
    (list) => {
        revealed.value = Array.isArray(list) ? list.map(() => false) : []
    },
    { immediate: true }
)
const toggleReveal = (idx) => {
    revealed.value[idx] = !revealed.value[idx]
}

/** --------- Photos capture logic ---------- */
const files = ref([null, null, null, null]) // File | null
const previews = ref([null, null, null, null]) // object URL | null
const fileInputs = ref([null, null, null, null])
const uploading = ref(false)

const takenCount = computed(() => files.value.filter(Boolean).length)
const allSet = computed(() => takenCount.value === 4)

const triggerCapture = (i) => {
    const el = fileInputs.value[i]
    if (el) el.click()
}

const onFileChange = (i, e) => {
    const file = e.target?.files?.[0]
    if (!file) return

    // Revoke old preview if exists
    if (previews.value[i]) URL.revokeObjectURL(previews.value[i])

    files.value[i] = file
    previews.value[i] = URL.createObjectURL(file)

    // Allow selecting the same file again later
    e.target.value = null
}

const clearPhoto = (i) => {
    if (previews.value[i]) URL.revokeObjectURL(previews.value[i])
    previews.value[i] = null
    files.value[i] = null
}

onBeforeUnmount(() => {
    previews.value.forEach((url) => url && URL.revokeObjectURL(url))
})

const submit = () => {
    if (!allSet.value || uploading.value) return
    uploading.value = true

    const fd = new FormData()
    fd.append('booking_id', props.booking.id)

    files.value.forEach((f, idx) => {
        // Numele e opțional, dar util
        fd.append('photos[]', f, `checkin_${idx + 1}.jpg`)
    })

    // IMPORTANT: schimbă numele rutei dacă ai altceva în backend.
    // Presupunem: Route::post('/bookings/{booking}/check-in/photos', ...)->name('user.bookings.checkin.store');
    router.post(route('user.bookings.checkin.store', { booking: props.booking.id }), fd, {
        forceFormData: true,
        preserveScroll: true,
        onFinish: () => { uploading.value = false },
    })
}

/** --------- Tiny inline icons (no extra deps) ---------- */
const CameraIcon = (props, { attrs }) =>
    h(
        'svg',
        {
            ...attrs,
            viewBox: '0 0 24 24',
            fill: 'none',
            stroke: 'currentColor',
            'aria-hidden': 'true',
        },
        [
            h('path', {
                d: 'M3 8.5A2.5 2.5 0 0 1 5.5 6h2l1-1.5h7L17.5 6H19a2.5 2.5 0 0 1 2.5 2.5v7A2.5 2.5 0 0 1 19 18H5.5A2.5 2.5 0 0 1 3 15.5v-7z',
                'stroke-width': '1.5',
                'stroke-linecap': 'round',
                'stroke-linejoin': 'round',
            }),
            h('circle', { cx: '12', cy: '12.5', r: '3.5', 'stroke-width': '1.5' }),
        ]
    )

const StarIcon = (props, { attrs }) =>
    h(
        'svg',
        {
            ...attrs,
            viewBox: '0 0 24 24',
            fill: 'currentColor',
            stroke: 'currentColor',
            'aria-hidden': 'true',
        },
        [
            h('path', {
                d: 'M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.4 5.82 22 7 14.14l-5-4.87 6.91-1.01L12 2z',
            }),
        ]
    )
</script>
