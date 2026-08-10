<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value?.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value?.focus();
            }
        },
    });
};

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
                Schimbă parola
            </h3>
            <p class="tw-mt-1 tw-text-[14px] tw-text-gray-500">
                Folosește o parolă lungă, pe care nu o refolosești altundeva.
            </p>
        </header>

        <form class="tw-max-w-xl tw-space-y-5" @submit.prevent="updatePassword">
            <div>
                <label for="current-password" :class="label">
                    Parola actuală
                </label>
                <input
                    id="current-password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    autocomplete="current-password"
                    :class="field"
                />
                <p v-if="form.errors.current_password" :class="errorText">
                    {{ form.errors.current_password }}
                </p>
            </div>

            <div>
                <label for="new-password" :class="label">Parolă nouă</label>
                <input
                    id="new-password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Minim 8 caractere"
                    :class="field"
                />
                <p v-if="form.errors.password" :class="errorText">
                    {{ form.errors.password }}
                </p>
            </div>

            <div>
                <label for="confirm-password" :class="label">
                    Confirmă parola nouă
                </label>
                <input
                    id="confirm-password"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    :class="field"
                />
                <p v-if="form.errors.password_confirmation" :class="errorText">
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <div class="tw-flex tw-items-center tw-gap-4 tw-pt-1">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="tw-rounded-xl tw-bg-[var(--theme)] tw-px-5 tw-py-3 tw-text-[15px] tw-font-semibold tw-text-white tw-transition hover:tw-brightness-95 disabled:tw-cursor-not-allowed disabled:tw-opacity-60"
                >
                    Schimbă parola
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
                        Parola a fost schimbată.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
