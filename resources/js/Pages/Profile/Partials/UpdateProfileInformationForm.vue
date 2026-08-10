<script setup>
/**
 * Varianta anterioara citea usePage().props.auth.user, dar HandleInertiaRequests
 * din acest proiect partajeaza 'user', nu 'auth' — deci formularul crapa la
 * randare cu "Cannot read properties of undefined".
 */
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.user;

const form = useForm({
    name: user?.name ?? "",
    email: user?.email ?? "",
});

const field =
    "tw-block tw-w-full tw-rounded-xl tw-border tw-border-gray-200 tw-bg-gray-50/60 tw-px-4 tw-py-3 tw-text-[15px] tw-text-gray-900 tw-transition placeholder:tw-text-gray-400 focus:tw-border-[var(--theme2)] focus:tw-bg-white focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-[var(--theme2)]/10";
const label =
    "tw-mb-2 tw-block tw-text-[13px] tw-font-semibold tw-text-gray-700";
const errorText =
    "tw-mt-2 tw-text-[13px] tw-font-medium tw-text-[var(--theme)]";
</script>

<template>
    <section
        class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-6 sm:tw-p-8"
    >
        <header class="tw-mb-6">
            <h3 class="tw-text-[17px] tw-font-semibold tw-text-gray-900">
                Date personale
            </h3>
            <p class="tw-mt-1 tw-text-[14px] tw-text-gray-500">
                Numele și adresa de email asociate contului.
            </p>
        </header>

        <form
            class="tw-max-w-xl tw-space-y-5"
            @submit.prevent="form.patch(route('profile.update'))"
        >
            <div>
                <label for="profile-name" :class="label">Nume</label>
                <input
                    id="profile-name"
                    v-model="form.name"
                    type="text"
                    autocomplete="name"
                    :class="field"
                    required
                />
                <p v-if="form.errors.name" :class="errorText">
                    {{ form.errors.name }}
                </p>
            </div>

            <div>
                <label for="profile-email" :class="label">Email</label>
                <input
                    id="profile-email"
                    v-model="form.email"
                    type="email"
                    autocomplete="username"
                    :class="field"
                    required
                />
                <p v-if="form.errors.email" :class="errorText">
                    {{ form.errors.email }}
                </p>
            </div>

            <div
                v-if="mustVerifyEmail && user?.email_verified_at === null"
                class="tw-rounded-xl tw-bg-amber-50 tw-px-4 tw-py-3 tw-text-[14px] tw-text-amber-800"
            >
                Adresa de email nu este confirmată.
                <Link
                    :href="route('verification.send')"
                    method="post"
                    as="button"
                    class="tw-font-semibold tw-underline"
                >
                    Trimite din nou linkul
                </Link>
                <p v-if="status === 'verification-link-sent'" class="tw-mt-2 tw-font-medium">
                    Ți-am trimis un link nou de confirmare.
                </p>
            </div>

            <div class="tw-flex tw-items-center tw-gap-4 tw-pt-1">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="tw-rounded-xl tw-bg-[var(--theme)] tw-px-5 tw-py-3 tw-text-[15px] tw-font-semibold tw-text-white tw-transition hover:tw-brightness-95 disabled:tw-cursor-not-allowed disabled:tw-opacity-60"
                >
                    Salvează
                </button>
                <Transition
                    enter-active-class="tw-transition tw-ease-in-out"
                    enter-from-class="tw-opacity-0"
                    leave-active-class="tw-transition tw-ease-in-out"
                    leave-to-class="tw-opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="tw-text-[14px] tw-font-medium tw-text-emerald-600"
                    >
                        Salvat.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
