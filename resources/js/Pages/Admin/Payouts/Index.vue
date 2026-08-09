<template>
    <AdminDashboardLayout>
        <div class="tw-max-w-5xl tw-mx-auto tw-p-4 sm:tw-p-6 tw-space-y-4">
            <div class="tw-flex tw-items-center tw-justify-between">
                <h1 class="tw-text-2xl tw-font-semibold">Payouts manuale</h1>
                <a :href="route('admin.payouts.export')"
                    class="tw-px-3 tw-py-2 tw-rounded-lg tw-border hover:tw-bg-gray-50" target="_blank" rel="noopener">
                    Export CSV
                </a>
            </div>

            <div class="tw-bg-white tw-border tw-rounded-xl tw-shadow-sm">
                <table class="tw-w-full tw-text-sm">
                    <thead class="tw-bg-gray-50">
                        <tr>
                            <th class="tw-text-left tw-px-4 tw-py-2">Booking</th>
                            <th class="tw-text-left tw-px-4 tw-py-2">Proprietar</th>
                            <th class="tw-text-left tw-px-4 tw-py-2">IBAN</th>
                            <th class="tw-text-left tw-px-4 tw-py-2">Net</th>
                            <th class="tw-text-left tw-px-4 tw-py-2">Status</th>
                            <th class="tw-text-right tw-px-4 tw-py-2">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in rows" :key="r.id" class="tw-border-t">
                            <td class="tw-px-4 tw-py-2">#{{ r.id }}</td>
                            <td class="tw-px-4 tw-py-2">{{ r.owner || '—' }}</td>
                            <td class="tw-px-4 tw-py-2">{{ r.iban || '—' }}</td>
                            <td class="tw-px-4 tw-py-2">{{ (r.net / 100).toFixed(2) }} {{ r.currency }}</td>
                            <td class="tw-px-4 tw-py-2">
                                <span class="tw-text-xs tw-px-2 tw-py-1 tw-rounded-full tw-border"
                                    :class="r.payout_status === 'manual_required' ? 'tw-bg-yellow-50 tw-text-yellow-700 tw-border-yellow-200' : 'tw-bg-green-50 tw-text-green-700 tw-border-green-200'">
                                    {{ r.payout_status }}
                                </span>
                            </td>
                            <td class="tw-px-4 tw-py-2 tw-text-right">
                                <button v-if="r.payout_status === 'manual_required'"
                                    class="tw-px-3 tw-py-1.5 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                                    @click="openModal(r)">
                                    Marchează ca plătit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- modal mic -->
            <div v-if="modal.open" class="tw-fixed tw-inset-0 tw-bg-black/30 tw-grid tw-place-items-center">
                <div class="tw-bg-white tw-rounded-xl tw-border tw-shadow-lg tw-w-full tw-max-w-md tw-p-4 space-y-3">
                    <h3 class="tw-font-medium">Confirmă payout pentru booking #{{ modal.row?.id }}</h3>
                    <input v-model="reference" type="text" placeholder="Referință bancă / Nr. OP"
                        class="tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2" />
                    <div class="tw-flex tw-justify-end tw-gap-2">
                        <button class="tw-px-3 tw-py-2 tw-rounded-lg tw-border" @click="closeModal">Anulează</button>
                        <button class="tw-px-3 tw-py-2 tw-rounded-lg tw-border tw-bg-gray-900 tw-text-white"
                            @click="markPaid">Marchează</button>
                    </div>
                </div>
            </div>
        </div>
    </AdminDashboardLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminDashboardLayout from '@/Layouts/AdminDashboardLayout.vue'
import { reactive, ref } from 'vue'

const props = defineProps({ rows: Array })

const modal = reactive({ open: false, row: null })
const reference = ref('')

const openModal = (row) => {
    modal.open = true
    modal.row = row
    reference.value = `booking_${row.id}`
}
const closeModal = () => { modal.open = false; modal.row = null; reference.value = '' }

const markPaid = () => {
    router.post(route('admin.payouts.markPaid', { booking: modal.row.id }), { payout_reference: reference.value }, {
        preserveScroll: true,
        onSuccess: closeModal,
    })
}
</script>
