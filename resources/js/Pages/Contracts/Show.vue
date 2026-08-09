<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-3xl tw-mx-auto tw-p-4 sm:tw-p-6 tw-space-y-4">
            <!-- Header -->
            <div class="tw-flex tw-items-start tw-justify-between">
                <div>
                    <h1 class="tw-text-2xl tw-font-semibold">Contract — Booking #{{ booking.id }}</h1>
                    <p class="tw-text-sm tw-text-gray-600">
                        Status booking: <span class="tw-font-medium">{{ booking.status }}</span>
                        <span class="tw-text-gray-400"> • </span>
                        Status contract: <span class="tw-font-medium">{{ contract.status }}</span>
                    </p>
                </div>

                <span class="tw-text-xs tw-px-2 tw-py-1 tw-rounded-full tw-border" :class="viewer_role === 'owner'
                    ? 'tw-bg-blue-50 tw-text-blue-700 tw-border-blue-200'
                    : viewer_role === 'client'
                        ? 'tw-bg-emerald-50 tw-text-emerald-700 tw-border-emerald-200'
                        : 'tw-bg-gray-50 tw-text-gray-700 tw-border-gray-200'">
                    Vezi ca: {{ roleLabel }}
                </span>
            </div>

            <!-- Flash succes -->
            <div v-if="flash?.success"
                class="tw-text-sm tw-text-green-700 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-lg tw-px-3 tw-py-2">
                {{ flash.success }}
            </div>

            <!-- Document (dacă ai un PDF generat) -->
            <section class="tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm tw-p-4 sm:tw-p-6">
                <h2 class="tw-font-medium tw-text-lg tw-mb-3">Document</h2>
                <div class="tw-flex tw-items-center tw-justify-between tw-gap-3">
                    <p class="tw-text-sm tw-text-gray-600">
                        {{ contract.document ? 'Deschide documentul pentru a-l citi, apoi semnează.' : 'Documentul va fi disponibil după generare.' }}
                    </p>
                    <a v-if="contract.document" :href="contract.document" target="_blank" rel="noopener"
                        class="tw-text-sm tw-px-3 tw-py-2 tw-rounded-xl tw-border hover:tw-bg-gray-50">
                        Deschide document
                    </a>
                </div>
            </section>

            <!-- Semnatari -->
            <section class="tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm tw-p-4 sm:tw-p-6">
                <h2 class="tw-font-medium tw-text-lg tw-mb-3">Semnatari</h2>
                <ul class="tw-space-y-2">
                    <li v-for="s in signers" :key="s.id" class="tw-flex tw-items-center tw-justify-between tw-text-sm">
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <span class="tw-text-xs tw-px-2 tw-py-0.5 tw-rounded-full tw-border" :class="s.role === 'owner'
                                ? 'tw-bg-blue-50 tw-text-blue-700 tw-border-blue-200'
                                : 'tw-bg-emerald-50 tw-text-emerald-700 tw-border-emerald-200'">
                                {{ s.role === 'owner' ? 'Proprietar' : 'Client' }}
                            </span>
                            <span class="tw-font-medium">{{ s.name }}</span>
                            <span class="tw-text-gray-500">({{ s.email }})</span>
                        </div>
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <span v-if="s.has_signed" class="tw-text-green-700">Semnat la {{ s.signed_at }}</span>
                            <span v-else class="tw-text-gray-500">Nesemnat</span>
                        </div>
                    </li>
                </ul>
            </section>

            <!-- CTA: Semnare -->
            <div class="tw-flex tw-items-center tw-gap-3">
                <button v-if="canSign" type="button"
                    class="tw-inline-flex tw-items-center tw-justify-center tw-px-4 tw-py-2.5 tw-rounded-xl tw-font-medium tw-border tw-shadow-sm tw-bg-gray-900 tw-text-white hover:tw-bg-black disabled:tw-opacity-60 disabled:tw-cursor-not-allowed"
                    :disabled="signing" @click="sign">
                    <svg v-if="signing" class="tw-animate-spin tw--ml-1 tw-mr-2 tw-h-4 tw-w-4 tw-text-current"
                        viewBox="0 0 24 24" fill="none">
                        <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="tw-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>
                    Semnează ca {{ roleLabel }}
                </button>

                <p v-else class="tw-text-sm tw-text-gray-600">
                    {{ viewer_role === 'admin'
                        ? 'Vizualizare doar pentru administrator.'
                        : 'Așteaptă cealaltă semnătură sau schimbarea statusului.' }}
                </p>
            </div>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue'
// dacă ai un layout separat pentru client, îl poți folosi cu un wrapper dinamic.
// momentan folosim același layout pentru simplitate.

import { computed, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
    booking: Object,
    contract: Object,
    signers: Array,
    viewer_role: String, // 'owner' | 'client' | 'admin'
    canSign: Boolean,
    my_signer_id: [Number, String, null],
})

const flash = usePage().props.flash || {}
const signing = ref(false)

const roleLabel = computed(() =>
    props.viewer_role === 'owner' ? 'Proprietar'
        : props.viewer_role === 'client' ? 'Client'
            : 'Administrator'
)

const sign = () => {
    if (signing.value) return
    signing.value = true
    router.post(route('user.bookings.contract.sign', { booking: props.booking.id }), {}, {
        preserveScroll: true,
        onFinish: () => { signing.value = false },
    })
}
</script>
