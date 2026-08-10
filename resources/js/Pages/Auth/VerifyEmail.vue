<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Confirmare email" />

        <div class="tw-mb-4 tw-text-sm tw-text-gray-600">
            Mulțumim că ți-ai creat cont. Înainte să începi, confirmă-ți adresa
            de email dând clic pe linkul pe care tocmai ți l-am trimis. Dacă nu
            l-ai primit, îți trimitem altul.
        </div>

        <div
            class="tw-mb-4 tw-text-sm tw-font-medium tw-text-green-600"
            v-if="verificationLinkSent"
        >
            Ți-am trimis un link nou de confirmare pe adresa de email folosită
            la înregistrare.
        </div>

        <form @submit.prevent="submit">
            <div class="tw-mt-4 tw-flex tw-items-center tw-justify-between">
                <PrimaryButton
                    :class="{ 'tw-opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Retrimite emailul de confirmare
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="tw-rounded-md tw-text-sm tw-text-gray-600 tw-underline hover:tw-text-gray-900 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-ring-offset-2"
                    >Ieși din cont</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
