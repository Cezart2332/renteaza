<script setup>
/**
 * Layout simplu pentru paginile de cont generice (/dashboard si /profile).
 *
 * Varianta anterioara era scaffolding-ul Breeze nemodificat: 198 de linii cu
 * clase Tailwind NEprefixate, intr-un proiect care are prefix 'tw-'. Nicio clasa
 * nu se aplica, deci pagina se randa complet nestilizata (de aici si logo-ul
 * urias). Zonele reale ale aplicatiei (/user, /admin, /company-owner) folosesc
 * alte layout-uri, asa ca aici e nevoie doar de un cadru curat.
 */
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
// HandleInertiaRequests partajeaza 'user' cu roles[]
const user = computed(() => page.props.user ?? null);

function hasRole(name) {
    return (user.value?.roles ?? []).some((r) => r.name === name);
}

// unde trimitem utilizatorul in zona lui reala de lucru
const homeLink = computed(() => {
    if (hasRole("admin")) return { href: route("admin.dashboard"), label: "Panou admin" };
    if (hasRole("user")) return { href: route("user.dashboard"), label: "Panoul meu" };
    if (hasRole("company-owner"))
        return { href: route("company-owner.profile.edit"), label: "Pagina firmei" };
    return { href: "/", label: "Acasă" };
});
</script>

<template>
    <div class="tw-min-h-screen tw-bg-gray-50">
        <header class="tw-border-b tw-border-gray-200 tw-bg-white">
            <div
                class="tw-mx-auto tw-flex tw-h-16 tw-max-w-6xl tw-items-center tw-justify-between tw-gap-4 tw-px-4 sm:tw-px-6 lg:tw-px-8"
            >
                <Link href="/" class="tw-flex tw-items-center">
                    <img
                        src="/images/logo_renteaza.svg"
                        alt="RENTeaza"
                        class="tw-h-8 tw-w-auto"
                    />
                </Link>

                <nav class="tw-flex tw-items-center tw-gap-1 sm:tw-gap-2">
                    <Link
                        :href="homeLink.href"
                        class="tw-rounded-lg tw-px-3 tw-py-2 tw-text-[14px] tw-font-medium tw-text-gray-600 tw-transition hover:tw-bg-gray-100 hover:tw-text-gray-900"
                    >
                        {{ homeLink.label }}
                    </Link>
                    <Link
                        :href="route('profile.edit')"
                        class="tw-rounded-lg tw-px-3 tw-py-2 tw-text-[14px] tw-font-medium tw-text-gray-600 tw-transition hover:tw-bg-gray-100 hover:tw-text-gray-900"
                    >
                        Contul meu
                    </Link>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        type="button"
                        class="tw-rounded-lg tw-px-3 tw-py-2 tw-text-[14px] tw-font-medium tw-text-[var(--theme)] tw-transition hover:tw-bg-[var(--theme)]/5"
                    >
                        Ieși din cont
                    </Link>
                </nav>
            </div>
        </header>

        <div v-if="$slots.header" class="tw-border-b tw-border-gray-200 tw-bg-white">
            <div class="tw-mx-auto tw-max-w-6xl tw-px-4 tw-py-6 sm:tw-px-6 lg:tw-px-8">
                <slot name="header" />
            </div>
        </div>

        <main class="tw-mx-auto tw-max-w-6xl tw-px-4 tw-py-8 sm:tw-px-6 lg:tw-px-8">
            <slot />
        </main>
    </div>
</template>
