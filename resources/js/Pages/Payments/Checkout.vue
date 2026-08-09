<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-md tw-mx-auto tw-p-4 sm:tw-p-6 tw-space-y-4">
            <h1 class="tw-text-2xl tw-font-semibold">Plată rezervare #{{ booking.id }}</h1>
            <p class="tw-text-gray-600">Total de plată: <span class="tw-font-medium">{{ booking.total }} RON</span></p>

            <div id="payment-element" class="tw-bg-white tw-border tw-rounded-xl tw-p-4"></div>

            <button
                class="tw-w-full tw-mt-3 tw-px-4 tw-py-2.5 tw-rounded-xl tw-font-medium tw-border tw-shadow-sm tw-bg-gray-900 tw-text-white hover:tw-bg-black disabled:tw-opacity-60"
                :disabled="loading" @click="pay">
                {{ loading ? 'Se procesează…' : 'Plătește acum' }}
            </button>

            <p v-if="error"
                class="tw-text-sm tw-text-red-700 tw-bg-red-50 tw-border tw-border-red-200 tw-rounded-lg tw-px-3 tw-py-2">
                {{ error }}
            </p>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue'
import { onMounted, ref } from 'vue'
import { loadStripe } from '@stripe/stripe-js'

const props = defineProps({
    booking: Object,
    publishableKey: String,
    clientSecret: String,
    returnUrl: String,
})

let stripe, elements
const loading = ref(false)
const error = ref('')

onMounted(async () => {
    stripe = await loadStripe(props.publishableKey)
    elements = stripe.elements({ clientSecret: props.clientSecret })
    const paymentElement = elements.create('payment')
    paymentElement.mount('#payment-element')
})

const pay = async () => {
    error.value = ''
    loading.value = true
    const { error: err } = await stripe.confirmPayment({
        elements,
        confirmParams: { return_url: props.returnUrl },
    })
    if (err) error.value = err.message || 'A apărut o eroare la plată.'
    loading.value = false
}
</script>
