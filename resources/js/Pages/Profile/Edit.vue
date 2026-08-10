<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});
</script>

<template>
    <Head title="Contul meu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-[22px] tw-font-bold tw-text-gray-900">
                Contul meu
            </h2>
            <p class="tw-mt-1 tw-text-[15px] tw-text-gray-500">
                Datele de autentificare și setările contului.
            </p>
        </template>

        <div class="tw-space-y-6">
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
            />
            <UpdatePasswordForm />
            <DeleteUserForm />
        </div>
    </AuthenticatedLayout>
</template>
