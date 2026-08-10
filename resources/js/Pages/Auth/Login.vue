<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div
            class="tw-min-h-screen tw-flex tw-items-center tw-justify-center tw-px-4"
        >
            <div class="tw-w-full tw-max-w-md tw-space-y-6">
                <!-- Branding + Title -->
                <div class="tw-text-center tw-space-y-3">
                    <img
                        :src="imagePath('logo_renteaza.svg')"
                        alt="RENTeaza Logo"
                        class="tw-mx-auto tw-h-10 sm:tw-h-12 tw-w-auto"
                    />

                    <h1
                        class="tw-text-2xl sm:tw-text-3xl tw-font-extrabold tw-text-gray-900"
                    >
                        Intră în contul tău
                    </h1>
                    <p class="tw-text-sm tw-text-gray-600">
                        Bun venit înapoi! Te rugăm să te conectezi pentru a
                        continua.
                    </p>

                    <!-- Optional status from backend (e.g., password reset link sent) -->
                    <p
                        v-if="status"
                        class="tw-text-sm tw-font-medium tw-text-emerald-600"
                    >
                        {{ status }}
                    </p>
                </div>

                <!-- Form Card -->
                <form
                    @submit.prevent="submit"
                    class="tw-bg-white tw-rounded-2xl tw-shadow-lg tw-p-5 sm:tw-p-8 tw-space-y-5"
                >
                    <div class="tw-space-y-4">
                        <!-- Email -->
                        <div>
                            <InputLabel
                                for="email"
                                value="Email"
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                            />
                            <div class="tw-mt-1">
                                <TextInput
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    class="tw-appearance-none tw-text-black tw-block focus:tw-text-black tw-w-full tw-px-3 tw-h-11 tw-border tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-placeholder-gray-400 focus:tw-outline-none focus:tw-ring-indigo-500 focus:tw-border-indigo-500"
                                />
                            </div>
                            <InputError
                                :message="form.errors.email"
                                class="tw-mt-2 tw-text-sm tw-text-red-600"
                            />
                        </div>

                        <!-- Password -->
                        <div>
                            <InputLabel
                                for="password"
                                value="Parolă"
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                            />
                            <div class="tw-mt-1">
                                <TextInput
                                    id="password"
                                    type="password"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                    class="tw-appearance-none tw-text-black focus:tw-text-black tw-block tw-w-full tw-px-3 tw-h-11 tw-border tw-border-gray-300 tw-rounded-lg tw-shadow-sm tw-placeholder-gray-400 focus:tw-outline-none focus:tw-ring-indigo-500 focus:tw-border-indigo-500"
                                />
                            </div>
                            <InputError
                                :message="form.errors.password"
                                class="tw-mt-2 tw-text-sm tw-text-red-600"
                            />
                        </div>

                        <!-- Remember + Forgot -->
                        <div
                            class="tw-flex tw-flex-col tw-gap-3 sm:tw-flex-row sm:tw-items-center sm:tw-justify-between"
                        >
                            <label class="tw-flex tw-items-center">
                                <Checkbox
                                    v-model:checked="form.remember"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 focus:tw-ring-indigo-500 tw-border-gray-300 tw-rounded"
                                />
                                <span
                                    class="tw-ml-2 tw-text-sm tw-text-gray-700"
                                    >Rămâi conectat</span
                                >
                            </label>

                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="tw-text-sm tw-font-medium tw-text-indigo-600 hover:tw-text-indigo-500 tw-self-start sm:tw-self-auto"
                            >
                                Ai uitat parola?
                            </Link>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <PrimaryButton
                            type="submit"
                            :disabled="form.processing"
                            :class="[
                                'tw-group tw-relative tw-w-full tw-flex tw-justify-center tw-items-center tw-h-11 tw-px-4 tw-border tw-border-transparent tw-text-base tw-font-medium tw-rounded-lg tw-text-white tw-bg-indigo-600 hover:tw-bg-indigo-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-indigo-500',
                                form.processing &&
                                    'tw-opacity-50 tw-cursor-not-allowed',
                            ]"
                        >
                            Intră în cont
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Ruta /register exista, dar nu era linkuita de nicaieri in
                     interfata, asa ca pagina de inregistrare era inaccesibila. -->
                <p class="tw-text-center tw-text-sm tw-text-gray-600">
                    Nu ai cont?
                    <Link
                        :href="route('register')"
                        class="tw-font-semibold tw-text-indigo-600 hover:tw-text-indigo-700"
                    >
                        Înregistrează-te
                    </Link>
                </p>

                <!-- Footer helper (optional) -->
                <p class="tw-text-center tw-text-xs tw-text-gray-500">
                    Protejăm datele tale.
                    <span class="tw-hidden xs:tw-inline"
                        >Autentificarea este securizată.</span
                    >
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
