<script setup>
/**
 * Panou de autentificare combinat: login si inregistrare in acelasi ecran,
 * comutabile fara reincarcare de pagina.
 *
 * Folosit de Pages/Auth/Login.vue si Pages/Auth/Register.vue, ca ambele rute
 * sa ramana valide si linkabile direct.
 *
 * Nota: proiectul are prefix Tailwind 'tw-'. Componentele partajate
 * PrimaryButton / InputError / Checkbox au clase NEprefixate, deci se
 * randeaza nestilizate — de aceea markup-ul de aici e de sine statator.
 */
import { computed, nextTick, ref, watch } from "vue";
import { Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    initialTab: { type: String, default: "login" },
    canResetPassword: { type: Boolean, default: true },
    status: { type: String, default: "" },
});

const tab = ref(props.initialTab === "register" ? "register" : "login");
const showPassword = ref(false);

const loginForm = useForm({
    email: "",
    password: "",
    remember: false,
});

const registerForm = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const activeForm = computed(() =>
    tab.value === "login" ? loginForm : registerForm
);

const firstFieldId = computed(() =>
    tab.value === "login" ? "login-email" : "register-name"
);

function switchTab(next) {
    if (tab.value === next) return;
    tab.value = next;
    showPassword.value = false;
    // erorile celuilalt formular nu mai sunt relevante
    loginForm.clearErrors();
    registerForm.clearErrors();
    nextTick(() => document.getElementById(firstFieldId.value)?.focus());
}

// daca serverul intoarce erori de inregistrare, ne asiguram ca tabul potrivit
// e vizibil (ex. email deja folosit, dupa un submit de pe /register)
watch(
    () => registerForm.errors,
    (errors) => {
        if (Object.keys(errors).length) tab.value = "register";
    },
    { deep: true }
);

const submitLogin = () =>
    loginForm.post(route("login"), {
        onFinish: () => loginForm.reset("password"),
    });

const submitRegister = () =>
    registerForm.post(route("register"), {
        onFinish: () =>
            registerForm.reset("password", "password_confirmation"),
    });

const fieldClass =
    "tw-block tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-3.5 tw-py-2.5 tw-text-[15px] tw-text-gray-900 tw-shadow-sm tw-transition placeholder:tw-text-gray-400 focus:tw-border-[var(--theme2)] focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[var(--theme2)]/20 disabled:tw-bg-gray-50";

const labelClass =
    "tw-mb-1.5 tw-block tw-text-sm tw-font-medium tw-text-gray-800";
</script>

<template>
    <div
        class="tw-flex tw-min-h-screen tw-items-center tw-justify-center tw-bg-gray-50 tw-px-4 tw-py-10"
    >
        <div class="tw-w-full tw-max-w-[440px]">
            <!-- Brand -->
            <div class="tw-mb-7 tw-text-center">
                <Link href="/" class="tw-inline-block">
                    <img
                        :src="imagePath('logo_renteaza.svg')"
                        alt="RENTeaza"
                        class="tw-mx-auto tw-h-10 tw-w-auto"
                    />
                </Link>
                <h1
                    class="tw-mt-5 tw-text-[26px] tw-font-bold tw-leading-tight tw-text-gray-900"
                >
                    {{
                        tab === "login"
                            ? "Bine ai revenit"
                            : "Creează-ți contul"
                    }}
                </h1>
                <p class="tw-mt-1.5 tw-text-[15px] tw-text-gray-500">
                    {{
                        tab === "login"
                            ? "Conectează-te ca să îți gestionezi rezervările."
                            : "Durează un minut și închiriezi imediat."
                    }}
                </p>
            </div>

            <div
                class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-6 tw-shadow-sm sm:tw-p-8"
            >
                <!-- Comutator -->
                <div
                    role="tablist"
                    aria-label="Autentificare sau înregistrare"
                    class="tw-mb-6 tw-grid tw-grid-cols-2 tw-gap-1 tw-rounded-xl tw-bg-gray-100 tw-p-1"
                >
                    <button
                        v-for="item in [
                            { key: 'login', label: 'Autentificare' },
                            { key: 'register', label: 'Cont nou' },
                        ]"
                        :key="item.key"
                        type="button"
                        role="tab"
                        :aria-selected="tab === item.key"
                        :class="[
                            'tw-rounded-lg tw-px-4 tw-py-2 tw-text-sm tw-font-semibold tw-transition focus:tw-outline-none focus-visible:tw-ring-2 focus-visible:tw-ring-[var(--theme2)]/40',
                            tab === item.key
                                ? 'tw-bg-white tw-text-gray-900 tw-shadow-sm'
                                : 'tw-text-gray-500 hover:tw-text-gray-800',
                        ]"
                        @click="switchTab(item.key)"
                    >
                        {{ item.label }}
                    </button>
                </div>

                <!-- Mesaj de la server (ex. link de resetare trimis) -->
                <p
                    v-if="status"
                    class="tw-mb-5 tw-rounded-lg tw-bg-emerald-50 tw-px-4 tw-py-3 tw-text-sm tw-font-medium tw-text-emerald-700"
                >
                    {{ status }}
                </p>

                <!-- LOGIN -->
                <form
                    v-show="tab === 'login'"
                    class="tw-space-y-5"
                    novalidate
                    @submit.prevent="submitLogin"
                >
                    <div>
                        <label for="login-email" :class="labelClass">
                            Email
                        </label>
                        <input
                            id="login-email"
                            v-model="loginForm.email"
                            type="email"
                            autocomplete="username"
                            placeholder="nume@exemplu.ro"
                            :class="fieldClass"
                            required
                        />
                        <p
                            v-if="loginForm.errors.email"
                            class="tw-mt-1.5 tw-text-sm tw-text-[var(--theme)]"
                        >
                            {{ loginForm.errors.email }}
                        </p>
                    </div>

                    <div>
                        <div class="tw-flex tw-items-baseline tw-justify-between">
                            <label for="login-password" :class="labelClass">
                                Parolă
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="tw-mb-1.5 tw-text-sm tw-font-medium tw-text-[var(--theme2)] hover:tw-underline"
                            >
                                Ai uitat parola?
                            </Link>
                        </div>
                        <div class="tw-relative">
                            <input
                                id="login-password"
                                v-model="loginForm.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                placeholder="••••••••"
                                :class="[fieldClass, 'tw-pr-16']"
                                required
                            />
                            <button
                                type="button"
                                class="tw-absolute tw-inset-y-0 tw-right-0 tw-px-3.5 tw-text-sm tw-font-medium tw-text-gray-500 hover:tw-text-gray-800"
                                @click="showPassword = !showPassword"
                            >
                                {{ showPassword ? "Ascunde" : "Arată" }}
                            </button>
                        </div>
                        <p
                            v-if="loginForm.errors.password"
                            class="tw-mt-1.5 tw-text-sm tw-text-[var(--theme)]"
                        >
                            {{ loginForm.errors.password }}
                        </p>
                    </div>

                    <label
                        class="tw-flex tw-cursor-pointer tw-items-center tw-gap-2.5 tw-text-sm tw-text-gray-700"
                    >
                        <input
                            v-model="loginForm.remember"
                            type="checkbox"
                            class="tw-h-4 tw-w-4 tw-rounded tw-border-gray-300 tw-text-[var(--theme)] focus:tw-ring-[var(--theme2)]/30"
                        />
                        Ține-mă minte
                    </label>

                    <button
                        type="submit"
                        :disabled="loginForm.processing"
                        class="tw-w-full tw-rounded-lg tw-bg-[var(--theme)] tw-px-4 tw-py-3 tw-text-[15px] tw-font-semibold tw-text-white tw-shadow-sm tw-transition hover:tw-brightness-95 focus:tw-outline-none focus-visible:tw-ring-2 focus-visible:tw-ring-[var(--theme)]/40 disabled:tw-cursor-not-allowed disabled:tw-opacity-60"
                    >
                        {{
                            loginForm.processing
                                ? "Se conectează…"
                                : "Intră în cont"
                        }}
                    </button>
                </form>

                <!-- REGISTER -->
                <form
                    v-show="tab === 'register'"
                    class="tw-space-y-5"
                    novalidate
                    @submit.prevent="submitRegister"
                >
                    <div>
                        <label for="register-name" :class="labelClass">
                            Nume complet
                        </label>
                        <input
                            id="register-name"
                            v-model="registerForm.name"
                            type="text"
                            autocomplete="name"
                            placeholder="Ion Popescu"
                            :class="fieldClass"
                            required
                        />
                        <p
                            v-if="registerForm.errors.name"
                            class="tw-mt-1.5 tw-text-sm tw-text-[var(--theme)]"
                        >
                            {{ registerForm.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label for="register-email" :class="labelClass">
                            Email
                        </label>
                        <input
                            id="register-email"
                            v-model="registerForm.email"
                            type="email"
                            autocomplete="username"
                            placeholder="nume@exemplu.ro"
                            :class="fieldClass"
                            required
                        />
                        <p
                            v-if="registerForm.errors.email"
                            class="tw-mt-1.5 tw-text-sm tw-text-[var(--theme)]"
                        >
                            {{ registerForm.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label for="register-password" :class="labelClass">
                            Parolă
                        </label>
                        <div class="tw-relative">
                            <input
                                id="register-password"
                                v-model="registerForm.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="new-password"
                                placeholder="Minim 8 caractere"
                                :class="[fieldClass, 'tw-pr-16']"
                                required
                            />
                            <button
                                type="button"
                                class="tw-absolute tw-inset-y-0 tw-right-0 tw-px-3.5 tw-text-sm tw-font-medium tw-text-gray-500 hover:tw-text-gray-800"
                                @click="showPassword = !showPassword"
                            >
                                {{ showPassword ? "Ascunde" : "Arată" }}
                            </button>
                        </div>
                        <p
                            v-if="registerForm.errors.password"
                            class="tw-mt-1.5 tw-text-sm tw-text-[var(--theme)]"
                        >
                            {{ registerForm.errors.password }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="register-password-confirmation"
                            :class="labelClass"
                        >
                            Confirmă parola
                        </label>
                        <input
                            id="register-password-confirmation"
                            v-model="registerForm.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            :class="fieldClass"
                            required
                        />
                        <p
                            v-if="registerForm.errors.password_confirmation"
                            class="tw-mt-1.5 tw-text-sm tw-text-[var(--theme)]"
                        >
                            {{ registerForm.errors.password_confirmation }}
                        </p>
                    </div>

                    <button
                        type="submit"
                        :disabled="registerForm.processing"
                        class="tw-w-full tw-rounded-lg tw-bg-[var(--theme)] tw-px-4 tw-py-3 tw-text-[15px] tw-font-semibold tw-text-white tw-shadow-sm tw-transition hover:tw-brightness-95 focus:tw-outline-none focus-visible:tw-ring-2 focus-visible:tw-ring-[var(--theme)]/40 disabled:tw-cursor-not-allowed disabled:tw-opacity-60"
                    >
                        {{
                            registerForm.processing
                                ? "Se creează contul…"
                                : "Creează cont"
                        }}
                    </button>
                </form>
            </div>

            <p class="tw-mt-6 tw-text-center tw-text-sm tw-text-gray-500">
                <template v-if="tab === 'login'">
                    Nu ai cont?
                    <button
                        type="button"
                        class="tw-font-semibold tw-text-[var(--theme2)] hover:tw-underline"
                        @click="switchTab('register')"
                    >
                        Înregistrează-te
                    </button>
                </template>
                <template v-else>
                    Ai deja cont?
                    <button
                        type="button"
                        class="tw-font-semibold tw-text-[var(--theme2)] hover:tw-underline"
                        @click="switchTab('login')"
                    >
                        Autentifică-te
                    </button>
                </template>
            </p>
        </div>
    </div>
</template>
