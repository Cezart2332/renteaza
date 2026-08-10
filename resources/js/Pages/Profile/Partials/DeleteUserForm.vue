<script setup>
/**
 * Confirmarea se face inline, nu prin componenta Modal: aceasta are clase
 * Tailwind neprefixate si s-ar randa fara stiluri.
 */
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirming = ref(false);
const passwordInput = ref(null);

const form = useForm({ password: "" });

const startConfirm = () => {
    confirming.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const cancel = () => {
    confirming.value = false;
    form.clearErrors();
    form.reset();
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => (confirming.value = false),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const field =
    "tw-block tw-w-full tw-rounded-xl tw-border tw-border-gray-200 tw-bg-white tw-px-4 tw-py-3 tw-text-[15px] tw-text-gray-900 tw-transition placeholder:tw-text-gray-400 focus:tw-border-[var(--theme)] focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-[var(--theme)]/10";
</script>

<template>
    <section
        class="tw-rounded-2xl tw-border tw-border-[var(--theme)]/25 tw-bg-white tw-p-6 sm:tw-p-8"
    >
        <header class="tw-mb-6">
            <h3 class="tw-text-[17px] tw-font-semibold tw-text-gray-900">
                Șterge contul
            </h3>
            <p class="tw-mt-1 tw-max-w-xl tw-text-[14px] tw-leading-relaxed tw-text-gray-500">
                Ștergerea este definitivă. Odată șters contul, se pierd toate
                datele asociate — rezervări, documente și mașinile listate.
                Salvează-ți întâi ce vrei să păstrezi.
            </p>
        </header>

        <button
            v-if="!confirming"
            type="button"
            class="tw-rounded-xl tw-bg-[var(--theme)] tw-px-5 tw-py-3 tw-text-[15px] tw-font-semibold tw-text-white tw-transition hover:tw-brightness-95"
            @click="startConfirm"
        >
            Șterge contul
        </button>

        <div
            v-else
            class="tw-max-w-xl tw-rounded-xl tw-bg-[var(--theme)]/[0.04] tw-p-5"
        >
            <p class="tw-text-[15px] tw-font-semibold tw-text-gray-900">
                Sigur vrei să ștergi contul?
            </p>
            <p class="tw-mt-1 tw-text-[14px] tw-text-gray-600">
                Confirmă cu parola ta. Acțiunea nu poate fi anulată.
            </p>

            <form class="tw-mt-4" @submit.prevent="deleteUser">
                <label for="delete-password" class="tw-sr-only">Parolă</label>
                <input
                    id="delete-password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    autocomplete="current-password"
                    placeholder="Parola"
                    :class="field"
                    @keyup.enter="deleteUser"
                />
                <p
                    v-if="form.errors.password"
                    class="tw-mt-2 tw-text-[13px] tw-font-medium tw-text-[var(--theme)]"
                >
                    {{ form.errors.password }}
                </p>

                <div class="tw-mt-4 tw-flex tw-flex-wrap tw-gap-3">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="tw-rounded-xl tw-bg-[var(--theme)] tw-px-5 tw-py-3 tw-text-[15px] tw-font-semibold tw-text-white tw-transition hover:tw-brightness-95 disabled:tw-cursor-not-allowed disabled:tw-opacity-60"
                    >
                        Da, șterge definitiv
                    </button>
                    <button
                        type="button"
                        class="tw-rounded-xl tw-border tw-border-gray-300 tw-bg-white tw-px-5 tw-py-3 tw-text-[15px] tw-font-semibold tw-text-gray-700 tw-transition hover:tw-bg-gray-50"
                        @click="cancel"
                    >
                        Renunță
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>
