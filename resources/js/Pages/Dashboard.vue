<script setup>
/**
 * Pagina generica de cont. Inainte era placeholder-ul Breeze ("You're logged
 * in!") cu clase Tailwind neprefixate, deci nestilizat.
 *
 * Fluxurile normale nu mai trec pe aici — login-ul si inregistrarea duc direct
 * in zona rolului — dar ruta ramane valida, asa ca pagina arata acum ce poate
 * face contul si trimite mai departe.
 */
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.user ?? null);

function hasRole(name) {
    return (user.value?.roles ?? []).some((r) => r.name === name);
}

const cards = computed(() => {
    const list = [];

    if (hasRole("admin")) {
        list.push({
            title: "Panou de administrare",
            text: "Utilizatori, documente de validat și rezervări.",
            href: route("admin.dashboard"),
            cta: "Deschide panoul",
        });
    }

    if (hasRole("user")) {
        list.push({
            title: "Mașinile mele",
            text: "Adaugă mașini, setează prețuri și disponibilitate.",
            href: route("user.cars.index"),
            cta: "Vezi mașinile",
        });
        list.push({
            title: "Rezervări",
            text: "Rezervările tale, ca proprietar și ca client.",
            href: route("user.dashboard"),
            cta: "Deschide",
        });
    }

    if (hasRole("company-owner")) {
        list.push({
            title: "Pagina publică a firmei",
            text: "Datele care apar clienților pe mini-site.",
            href: route("company-owner.profile.edit"),
            cta: "Editează",
        });
    }

    return list;
});
</script>

<template>
    <Head title="Contul meu" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="tw-text-[22px] tw-font-bold tw-text-gray-900">
                Salut, {{ user?.name }}
            </h2>
            <p class="tw-mt-1 tw-text-[15px] tw-text-gray-500">
                De aici ajungi în zonele contului tău.
            </p>
        </template>

        <div
            v-if="cards.length"
            class="tw-grid tw-gap-4 sm:tw-grid-cols-2 lg:tw-grid-cols-3"
        >
            <div
                v-for="card in cards"
                :key="card.title"
                class="tw-flex tw-flex-col tw-justify-between tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-6"
            >
                <div>
                    <h3 class="tw-text-[16px] tw-font-semibold tw-text-gray-900">
                        {{ card.title }}
                    </h3>
                    <p class="tw-mt-1.5 tw-text-[14px] tw-leading-relaxed tw-text-gray-500">
                        {{ card.text }}
                    </p>
                </div>
                <Link
                    :href="card.href"
                    class="tw-mt-5 tw-inline-flex tw-items-center tw-gap-1.5 tw-text-[14px] tw-font-semibold tw-text-[var(--theme2)] hover:tw-underline"
                >
                    {{ card.cta }} →
                </Link>
            </div>
        </div>

        <div
            v-else
            class="tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-white tw-p-8 tw-text-center"
        >
            <p class="tw-text-[15px] tw-text-gray-600">
                Contul tău nu are încă niciun rol atribuit, așa că nu are zone
                disponibile. Scrie-ne și îl activăm.
            </p>
            <Link
                href="/"
                class="tw-mt-4 tw-inline-block tw-text-[14px] tw-font-semibold tw-text-[var(--theme2)] hover:tw-underline"
            >
                Înapoi la pagina principală
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
