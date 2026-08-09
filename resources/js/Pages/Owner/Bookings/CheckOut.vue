<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-5xl tw-mx-auto tw-p-4 sm:tw-p-6 tw-space-y-6">
            <!-- Header -->
            <div class="tw-flex tw-items-start tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl sm:tw-text-3xl tw-font-semibold tw-tracking-tight">
                        Check-Out — Booking #{{ booking.id }}
                    </h1>
                    <p class="tw-text-sm tw-text-gray-500">
                        Perioadă: {{ booking.start_date }} → {{ booking.end_date }} • {{ booking.days }} zile
                    </p>
                </div>
            </div>

            <!-- Banner disponibilitate -->
            <div v-if="!canSubmit"
                class="tw-text-sm tw-text-gray-700 tw-bg-gray-50 tw-border tw-border-gray-200 tw-rounded-lg tw-px-3 tw-py-2">
                Check-out-ul va fi disponibil după data de finalizare a rezervării ({{ booking.end_date }}).
            </div>

            <!-- Comparație: Anterior (check-in) vs Acum (check-out) -->
            <section class="tw-bg-white tw-shadow-sm tw-border tw-rounded-2xl tw-p-4 sm:tw-p-6">
                <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
                    <h3 class="tw-font-medium tw-text-lg">Comparație poze</h3>
                    <span class="tw-text-xs tw-px-2 tw-py-1 tw-rounded-full" :class="allSet ? 'tw-bg-green-50 tw-text-green-700 tw-border tw-border-green-200'
                        : 'tw-bg-yellow-50 tw-text-yellow-700 tw-border tw-border-yellow-200'">
                        {{ takenCount }}/4
                    </span>
                </div>

                <p class="tw-text-sm tw-text-gray-600 tw-mb-4">
                    Recomandare ordine: față, spate, lateral stânga, lateral dreapta. Încarcă 4 poze „Acum” pentru
                    comparație.
                </p>

                <div class="tw-grid tw-grid-cols-1 tw-gap-4">
                    <!-- Card pe slot: Anterior vs Acum -->
                    <div v-for="i in 4" :key="i" class="tw-border tw-rounded-xl tw-overflow-hidden">
                        <div class="tw-grid sm:tw-grid-cols-2 tw-grid-cols-1">
                            <!-- Anterior -->
                            <div class="tw-bg-gray-50 tw-p-3">
                                <p class="tw-text-xs tw-text-gray-500 tw-mb-2">Anterior (check-in) — Slot {{ i }}</p>
                                <div
                                    class="aspect-square tw-w-full tw-bg-white tw-border tw-rounded-lg tw-overflow-hidden tw-grid tw-place-items-center">
                                    <img v-if="checkinPhotos[i - 1]" :src="checkinPhotos[i - 1]"
                                        class="tw-w-full tw-h-full tw-object-cover" alt="Check-in photo" />
                                    <div v-else class="tw-text-xs tw-text-gray-400">Fără poză</div>
                                </div>
                            </div>

                            <!-- Acum -->
                            <div class="tw-p-3">
                                <p class="tw-text-xs tw-text-gray-500 tw-mb-2">Acum (check-out) — Slot {{ i }}</p>
                                <div
                                    class="aspect-square tw-w-full tw-border tw-rounded-lg tw-overflow-hidden tw-bg-gray-50">
                                    <img v-if="previews[i - 1]" :src="previews[i - 1]"
                                        class="tw-w-full tw-h-full tw-object-cover" alt="Check-out preview" />
                                    <div v-else
                                        class="tw-w-full tw-h-full tw-grid tw-place-items-center tw-text-gray-500">
                                        <div class="tw-flex tw-flex-col tw-items-center">
                                            <CameraIcon class="tw-w-8 tw-h-8 tw-mb-2" />
                                            <span class="tw-text-xs">Încarcă poză</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tw-mt-2 tw-flex tw-items-center tw-gap-2">
                                    <button type="button"
                                        class="tw-flex-1 tw-text-sm tw-px-3 tw-py-2 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                                        @click="triggerCapture(i - 1)">
                                        {{ previews[i - 1] ? 'Refă poza' : 'Fă poză' }}
                                    </button>
                                    <button v-if="previews[i - 1]" type="button"
                                        class="tw-text-sm tw-px-3 tw-py-2 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                                        @click="clearPhoto(i - 1)">
                                        Șterge
                                    </button>
                                </div>
                                <input class="tw-sr-only" type="file" accept="image/*" capture="environment"
                                    :ref="el => (fileInputs[i - 1] = el)" @change="onFileChange(i - 1, $event)" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observații -->
                <div class="tw-mt-4">
                    <label class="tw-block tw-text-sm tw-text-gray-700 tw-mb-1">Observații (opțional)</label>
                    <textarea v-model="notes" rows="3" class="tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2"
                        placeholder="Ex: zgârieturi noi pe aripa stângă…"></textarea>
                </div>

                <!-- Submit -->
                <div class="tw-mt-6 tw-flex tw-flex-col sm:tw-flex-row tw-items-stretch sm:tw-items-center tw-gap-3">
                    <p class="tw-text-xs tw-text-gray-500 tw-flex-1">
                        Prin trimitere confirmi că fotografiile reflectă starea vehiculului la check-out.
                    </p>
                    <button type="button"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-px-4 tw-py-2.5 tw-rounded-xl tw-font-medium tw-border tw-shadow-sm disabled:tw-opacity-60 disabled:tw-cursor-not-allowed"
                        :class="allSet && canSubmit ? 'tw-bg-gray-900 tw-text-white hover:tw-bg-black' : 'tw-bg-white tw-text-gray-700 hover:tw-bg-gray-50'"
                        :disabled="!allSet || uploading || !canSubmit" @click="submit">
                        <svg v-if="uploading" class="tw-animate-spin tw--ml-1 tw-mr-2 tw-h-4 tw-w-4 tw-text-current"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>
                        {{ uploading ? 'Se încarcă...' : 'Finalizează check-out-ul' }}
                    </button>
                </div>
            </section>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue'
import { ref, computed, onBeforeUnmount, h } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    booking: { type: Object, required: true },
    checkinPhotos: { type: Array, default: () => [] },
    canSubmit: { type: Boolean, default: false },
})

/** Poze noi (check-out) */
const files = ref([null, null, null, null])
const previews = ref([null, null, null, null])
const fileInputs = ref([null, null, null, null])
const uploading = ref(false)
const notes = ref('')

const takenCount = computed(() => files.value.filter(Boolean).length)
const allSet = computed(() => takenCount.value === 4)

const triggerCapture = (i) => { const el = fileInputs.value[i]; if (el) el.click() }
const onFileChange = (i, e) => {
    const file = e.target?.files?.[0]; if (!file) return
    if (previews.value[i]) URL.revokeObjectURL(previews.value[i])
    files.value[i] = file
    previews.value[i] = URL.createObjectURL(file)
    e.target.value = null
}
const clearPhoto = (i) => {
    if (previews.value[i]) URL.revokeObjectURL(previews.value[i])
    previews.value[i] = null; files.value[i] = null
}
onBeforeUnmount(() => { previews.value.forEach((u) => u && URL.revokeObjectURL(u)) })

const submit = () => {
    if (!allSet.value || uploading.value || !props.canSubmit) return
    uploading.value = true

    const fd = new FormData()
    fd.append('booking_id', props.booking.id)
    files.value.forEach((f, idx) => fd.append('photos[]', f, `checkout_${idx + 1}.jpg`))
    if (notes.value) fd.append('notes', notes.value)

    // schimbă numele rutei dacă ai alt prefix
    router.post(route('owner.bookings.checkout.store', { booking: props.booking.id }), fd, {
        forceFormData: true,
        preserveScroll: true,
        onFinish: () => { uploading.value = false },
    })
}

/** icoane mici inline */
const CameraIcon = (props, { attrs }) =>
    h('svg', { ...attrs, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'aria-hidden': 'true' }, [
        h('path', { d: 'M3 8.5A2.5 2.5 0 0 1 5.5 6h2l1-1.5h7L17.5 6H19a2.5 2.5 0 0 1 2.5 2.5v7A2.5 2.5 0 0 1 19 18H5.5A2.5 2.5 0 0 1 3 15.5v-7z', 'stroke-width': '1.5', 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }),
        h('circle', { cx: '12', cy: '12.5', r: '3.5', 'stroke-width': '1.5' }),
    ])
</script>
