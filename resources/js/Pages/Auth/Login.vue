<script setup>
import AuthPanel from "@/Components/Auth/AuthPanel.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    canResetPassword: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Autentificare" />

    <AuthPanel
        initial-tab="login"
        :can-reset-password="canResetPassword"
        :status="status"
    />
</template>
