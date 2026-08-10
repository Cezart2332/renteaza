<script setup>
/**
 * Panou de autentificare combinat: login si inregistrare pe acelasi ecran,
 * comutabile fara reincarcare de pagina.
 *
 * Folosit de Pages/Auth/Login.vue si Pages/Auth/Register.vue, ca ambele rute
 * sa ramana valide si linkabile direct.
 *
 * Nota: proiectul are prefix Tailwind 'tw-'. Componentele partajate
 * PrimaryButton / InputError / Checkbox au clase NEprefixate, deci s-ar
 * randa nestilizate — de aceea markup-ul de aici e de sine statator.
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
    account_type: "user",
});

const accountTypes = [
    {
        value: "user",
        title: "Vreau să închiriez",
        description: "Cauți o mașină pentru o perioadă.",
    },
    {
        value: "company-owner",
        title: "Vreau să dau spre închiriere",
        description:
            "Îți listezi mașinile și primești în plus o pagină publică de firmă.",
    },
];

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

// daca serverul intoarce erori de inregistrare, aducem tabul potrivit in fata
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

const field =
    "tw-block tw-w-full tw-rounded-xl tw-border tw-border-gray-200 tw-bg-gray-50/60 tw-px-4 tw-py-3 tw-text-[15px] tw-text-gray-900 tw-transition placeholder:tw-text-gray-400 focus:tw-border-[var(--theme2)] focus:tw-bg-white focus:tw-outline-none focus:tw-ring-4 focus:tw-ring-[var(--theme2)]/10";

const label = "tw-mb-2 tw-block tw-text-[13px] tw-font-semibold tw-text-gray-700";

const submitBtn =
    "tw-w-full tw-rounded-xl tw-bg-[var(--theme)] tw-px-4 tw-py-3.5 tw-text-[15px] tw-font-semibold tw-text-white tw-shadow-[0_6px_20px_-6px_rgba(255,55,38,0.6)] tw-transition hover:tw-brightness-95 focus:tw-outline-none focus-visible:tw-ring-4 focus-visible:tw-ring-[var(--theme)]/25 disabled:tw-cursor-not-allowed disabled:tw-opacity-60";

const errorText = "tw-mt-2 tw-text-[13px] tw-font-medium tw-text-[var(--theme)]";
</script>

<template>
    <div class="tw-min-h-screen tw-bg-white lg:tw-grid lg:tw-grid-cols-2">
        <!-- Panou de brand (doar pe ecrane mari) -->
        <aside
            class="tw-relative tw-hidden tw-overflow-hidden lg:tw-flex lg:tw-flex-col lg:tw-justify-between tw-bg-[#0b1220] tw-p-12 tw-text-white"
        >
            <img
                src="/images/car-banner.jpg"
                alt=""
                aria-hidden="true"
                class="tw-absolute tw-inset-0 tw-h-full tw-w-full tw-object-cover tw-opacity-25"
            />
            <div
                class="tw-absolute tw-inset-0"
                style="
                    background: linear-gradient(
                        140deg,
                        rgba(11, 18, 32, 0.92) 0%,
                        rgba(0, 92, 181, 0.75) 55%,
                        rgba(255, 55, 38, 0.7) 100%
                    );
                "
            ></div>

            <div class="tw-relative">
                <Link href="/">
                    <img
                        src="/images/logo_renteaza.svg"
                        alt="RENTeaza"
                        class="tw-h-9 tw-w-auto"
                        style="filter: brightness(0) invert(1)"
                    />
                </Link>
            </div>

            <div class="tw-relative tw-max-w-md">
                <h2
                    class="tw-text-[34px] tw-font-bold tw-leading-[1.15] tw-tracking-tight tw-text-white"
                >
                    Mașina potrivită, exact când ai nevoie de ea.
                </h2>
                <p class="tw-mt-4 tw-text-[15px] tw-leading-relaxed tw-text-white/75">
                    Rezervi în câteva minute, cu preț final afișat de la început
                    și contract semnat digital.
                </p>

                <ul class="tw-mt-9 tw-space-y-4">
                    <li
                        v-for="item in [
                            'Flotă verificată, de la firme partenere',
                            'Fără costuri ascunse la predare',
                            'Asistență pe toată durata închirierii',
                        ]"
                        :key="item"
                        class="tw-flex tw-items-start tw-gap-3 tw-text-[15px] tw-text-white/90"
                    >
                        <svg
                            class="tw-mt-0.5 tw-h-5 tw-w-5 tw-flex-none"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        {{ item }}
                    </li>
                </ul>
            </div>

            <p class="tw-relative tw-text-[13px] tw-text-white/50">
                © {{ new Date().getFullYear() }} RENTeaza
            </p>
        </aside>

        <!-- Formular -->
        <main
            class="tw-flex tw-items-center tw-justify-center tw-px-5 tw-py-12 sm:tw-px-10"
        >
            <div class="tw-w-full tw-max-w-[420px]">
                <!-- logo doar pe mobil, unde panoul de brand e ascuns -->
                <Link href="/" class="tw-mb-8 tw-block lg:tw-hidden">
                    <img
                        src="/images/logo_renteaza.svg"
                        alt="RENTeaza"
                        class="tw-h-9 tw-w-auto"
                    />
                </Link>

                <h1
                    class="tw-text-[28px] tw-font-bold tw-leading-tight tw-tracking-tight tw-text-gray-900"
                >
                    {{ tab === "login" ? "Bine ai revenit" : "Creează-ți contul" }}
                </h1>
                <p class="tw-mt-2 tw-text-[15px] tw-text-gray-500">
                    {{
                        tab === "login"
                            ? "Conectează-te ca să îți gestionezi rezervările."
                            : "Durează un minut. Alegi mai jos ce vrei să faci."
                    }}
                </p>

                <!-- Comutator -->
                <div
                    role="tablist"
                    aria-label="Autentificare sau înregistrare"
                    class="tw-mt-8 tw-flex tw-gap-6 tw-border-b tw-border-gray-200"
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
                            'tw--mb-px tw-border-b-2 tw-px-1 tw-pb-3 tw-text-[15px] tw-font-semibold tw-transition focus:tw-outline-none',
                            tab === item.key
                                ? 'tw-border-[var(--theme)] tw-text-gray-900'
                                : 'tw-border-transparent tw-text-gray-400 hover:tw-text-gray-700',
                        ]"
                        @click="switchTab(item.key)"
                    >
                        {{ item.label }}
                    </button>
                </div>

                <p
                    v-if="status"
                    class="tw-mt-6 tw-rounded-xl tw-bg-emerald-50 tw-px-4 tw-py-3 tw-text-sm tw-font-medium tw-text-emerald-700"
                >
                    {{ status }}
                </p>

                <!-- LOGIN -->
                <form
                    v-show="tab === 'login'"
                    class="tw-mt-7 tw-space-y-5"
                    novalidate
                    @submit.prevent="submitLogin"
                >
                    <div>
                        <label for="login-email" :class="label">Email</label>
                        <input
                            id="login-email"
                            v-model="loginForm.email"
                            type="email"
                            autocomplete="username"
                            placeholder="nume@exemplu.ro"
                            :class="field"
                            required
                        />
                        <p v-if="loginForm.errors.email" :class="errorText">
                            {{ loginForm.errors.email }}
                        </p>
                    </div>

                    <div>
                        <div class="tw-flex tw-items-baseline tw-justify-between">
                            <label for="login-password" :class="label">
                                Parolă
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="tw-mb-2 tw-text-[13px] tw-font-semibold tw-text-[var(--theme2)] hover:tw-underline"
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
                                :class="[field, 'tw-pr-16']"
                                required
                            />
                            <button
                                type="button"
                                class="tw-absolute tw-inset-y-0 tw-right-0 tw-px-4 tw-text-[13px] tw-font-semibold tw-text-gray-400 hover:tw-text-gray-700"
                                @click="showPassword = !showPassword"
                            >
                                {{ showPassword ? "Ascunde" : "Arată" }}
                            </button>
                        </div>
                        <p v-if="loginForm.errors.password" :class="errorText">
                            {{ loginForm.errors.password }}
                        </p>
                    </div>

                    <label
                        class="tw-flex tw-cursor-pointer tw-items-center tw-gap-2.5 tw-text-[14px] tw-text-gray-600"
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
                        :class="submitBtn"
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
                    class="tw-mt-7 tw-space-y-5"
                    novalidate
                    @submit.prevent="submitRegister"
                >
                    <fieldset>
                        <legend :class="label">Ce vrei să faci?</legend>
                        <div class="tw-grid tw-gap-2.5">
                            <label
                                v-for="opt in accountTypes"
                                :key="opt.value"
                                :class="[
                                    'tw-flex tw-cursor-pointer tw-items-start tw-gap-3 tw-rounded-xl tw-border tw-p-3.5 tw-transition',
                                    registerForm.account_type === opt.value
                                        ? 'tw-border-[var(--theme)] tw-bg-[var(--theme)]/[0.04]'
                                        : 'tw-border-gray-200 hover:tw-border-gray-300',
                                ]"
                            >
                                <input
                                    v-model="registerForm.account_type"
                                    type="radio"
                                    name="account_type"
                                    :value="opt.value"
                                    class="tw-mt-0.5 tw-h-4 tw-w-4 tw-border-gray-300 tw-text-[var(--theme)] focus:tw-ring-[var(--theme)]/30"
                                />
                                <span class="tw-block">
                                    <span
                                        class="tw-block tw-text-[14px] tw-font-semibold tw-text-gray-900"
                                    >
                                        {{ opt.title }}
                                    </span>
                                    <span
                                        class="tw-mt-0.5 tw-block tw-text-[13px] tw-text-gray-500"
                                    >
                                        {{ opt.description }}
                                    </span>
                                </span>
                            </label>
                        </div>
                        <p v-if="registerForm.errors.account_type" :class="errorText">
                            {{ registerForm.errors.account_type }}
                        </p>
                    </fieldset>

                    <div>
                        <label for="register-name" :class="label">
                            Nume complet
                        </label>
                        <input
                            id="register-name"
                            v-model="registerForm.name"
                            type="text"
                            autocomplete="name"
                            placeholder="Ion Popescu"
                            :class="field"
                            required
                        />
                        <p v-if="registerForm.errors.name" :class="errorText">
                            {{ registerForm.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label for="register-email" :class="label">Email</label>
                        <input
                            id="register-email"
                            v-model="registerForm.email"
                            type="email"
                            autocomplete="username"
                            placeholder="nume@exemplu.ro"
                            :class="field"
                            required
                        />
                        <p v-if="registerForm.errors.email" :class="errorText">
                            {{ registerForm.errors.email }}
                        </p>
                    </div>

                    <div>
                        <label for="register-password" :class="label">
                            Parolă
                        </label>
                        <div class="tw-relative">
                            <input
                                id="register-password"
                                v-model="registerForm.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="new-password"
                                placeholder="Minim 8 caractere"
                                :class="[field, 'tw-pr-16']"
                                required
                            />
                            <button
                                type="button"
                                class="tw-absolute tw-inset-y-0 tw-right-0 tw-px-4 tw-text-[13px] tw-font-semibold tw-text-gray-400 hover:tw-text-gray-700"
                                @click="showPassword = !showPassword"
                            >
                                {{ showPassword ? "Ascunde" : "Arată" }}
                            </button>
                        </div>
                        <p v-if="registerForm.errors.password" :class="errorText">
                            {{ registerForm.errors.password }}
                        </p>
                    </div>

                    <div>
                        <label
                            for="register-password-confirmation"
                            :class="label"
                        >
                            Confirmă parola
                        </label>
                        <input
                            id="register-password-confirmation"
                            v-model="registerForm.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            placeholder="••••••••"
                            :class="field"
                            required
                        />
                        <p
                            v-if="registerForm.errors.password_confirmation"
                            :class="errorText"
                        >
                            {{ registerForm.errors.password_confirmation }}
                        </p>
                    </div>

                    <button
                        type="submit"
                        :disabled="registerForm.processing"
                        :class="submitBtn"
                    >
                        {{
                            registerForm.processing
                                ? "Se creează contul…"
                                : "Creează cont"
                        }}
                    </button>
                </form>

                <p class="tw-mt-8 tw-text-[14px] tw-text-gray-500">
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
        </main>
    </div>
</template>
