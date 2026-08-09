<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-lg tw-mx-auto tw-p-4 sm:tw-p-6 tw-space-y-4">
            <h1 class="tw-text-2xl tw-font-semibold">Cont bancar pentru încasări</h1>

            <div v-if="flash?.success"
                class="tw-text-sm tw-text-green-700 tw-bg-green-50 tw-border tw-border-green-200 tw-rounded-lg tw-px-3 tw-py-2">
                {{ flash.success }}
            </div>

            <form @submit.prevent="submit"
                class="tw-bg-white tw-border tw-rounded-2xl tw-shadow-sm tw-p-4 sm:tw-p-6 tw-space-y-4">
                <div>
                    <label class="tw-block tw-text-sm tw-text-gray-700">Nume titular</label>
                    <input v-model="form.account_holder_name" type="text"
                        class="tw-mt-1 tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2 tw-text-black" />
                    <p v-if="errors.account_holder_name" class="tw-text-sm tw-text-red-600">{{
                        errors.account_holder_name }}</p>
                </div>

                <div>
                    <label class="tw-block tw-text-sm tw-text-gray-700">IBAN</label>
                    <input v-model="form.iban" type="text" placeholder="ROxx XXXX XXXX XXXX XXXX XXXX"
                        class="tw-mt-1 tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2 uppercase tw-text-black" />
                    <p v-if="errors.iban" class="tw-text-sm tw-text-red-600">{{ errors.iban }}</p>
                </div>

                <div>
                    <label class="tw-block tw-text-sm tw-text-gray-700">Banca (opțional)</label>
                    <input v-model="form.bank_name" type="text"
                        class="tw-mt-1 tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2 tw-text-black" />
                </div>

                <div>
                    <label class="tw-block tw-text-sm tw-text-gray-700">Monedă</label>
                    <select v-model="form.currency" class="tw-mt-1 tw-w-full tw-border tw-rounded-lg tw-px-3 tw-py-2">
                        <option value="RON">RON</option>
                        <option value="EUR">EUR</option>
                    </select>
                </div>

                <div class="tw-flex tw-items-center tw-justify-between">
                    <p class="tw-text-sm tw-text-gray-600">
                        Status verificare: <span class="tw-font-medium">{{ bank?.status ?? 'pending' }}</span>
                    </p>
                    <button :disabled="processing"
                        class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-rounded-xl tw-border tw-shadow-sm tw-bg-gray-900 tw-text-white hover:tw-bg-black disabled:tw-opacity-60">
                        {{ processing ? 'Se salvează…' : 'Salvează' }}
                    </button>
                </div>
            </form>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue'
import { reactive, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({ bank: Object })
const flash = usePage().props.flash || {}
const errors = usePage().props.errors || {}

const form = reactive({
    account_holder_name: props.bank?.account_holder_name || '',
    iban: (props.bank?.iban || '').toUpperCase(),
    bank_name: props.bank?.bank_name || '',
    currency: props.bank?.currency || 'RON',
})

const processing = ref(false)
const submit = () => {
    processing.value = true
    router.post(route('user.payments.bank.store'), form, {
        preserveScroll: true,
        onFinish: () => { processing.value = false },
    })
}
</script>
