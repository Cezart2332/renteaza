<template>
    <header id="header-sticky"
        class="tw-relative tw-z-[999] tw-bg-white tw-shadow-[var(--box-shadow)] tw-border-b-[3px] tw-border-b-[var(--theme)]">
        <!-- container-fluid: 15px / 30px / 40px / 60px + loc rezervat pt. wedge la dreapta -->
        <div class="tw-px-[15px] md:tw-px-[30px] xl:tw-px-[40px] 2xl:tw-px-[60px] 2xl:tw-pr-[360px] tw-py-2.5">
            <div class="tw-max-w-screen-2xl tw-mx-auto">
                <!-- header-main -->
                <div class="tw-flex tw-items-center tw-justify-between tw-gap-6">
                    <!-- LEFT -->
                    <div class="tw-flex tw-items-center tw-gap-[80px]">
                        <div class="tw-shrink-0">
                            <inertia-link to="/" class="tw-block">
                                <img width="200" :src="imagePath('logo_renteaza.svg')" alt="Renteaza" />
                            </inertia-link>
                        </div>

                        <!-- MENIU DESKTOP -->
                        <nav class="tw-hidden sm:tw-block">
                            <ul class="tw-flex tw-items-center tw-gap-6">
                                <li>
                                    <inertia-link :href="route('landing')"
                                        class="tw-font-medium tw-text-[var(--text)] hover:tw-text-[var(--header)]">Acasă</inertia-link>
                                </li>
                                <li>
                                    <inertia-link :href="route('car.index')"
                                        class="tw-font-medium tw-text-[var(--text)] hover:tw-text-[var(--header)]">Mașini</inertia-link>
                                </li>
                                <Menu as="div" class="tw-relative tw-inline-block">
                                    <MenuButton
                                        class="tw-font-medium tw-text-[var(--text)] hover:tw-text-[var(--header)]">
                                        Servicii
                                        <ChevronDownIcon class="tw--mr-1 tw-size-5 tw-text-gray-400"
                                            aria-hidden="true" />
                                    </MenuButton>

                                    <transition enter-active-class="tw-transition tw-ease-out tw-duration-100"
                                        enter-from-class="tw-transform tw-opacity-0 tw-scale-95"
                                        enter-to-class="tw-transform tw-scale-100"
                                        leave-active-class="tw-transition tw-ease-in tw-duration-75"
                                        leave-from-class="tw-transform tw-scale-100"
                                        leave-to-class="tw-transform tw-opacity-0 tw-scale-95">
                                        <MenuItems
                                            class="tw-absolute tw-right-0 tw-z-10 tw-mt-2 tw-w-56 tw-origin-top-right tw-rounded-md tw-bg-white outline outline-1 outline-black/5 dark:tw-bg-gray-800 dark:tw-shadow-none dark:-outline-offset-1 dark:outline-white/10">
                                            <div class="tw-py-1">
                                                <MenuItem v-slot="{ active }">
                                                <MenuItem v-slot="{ active }">
                                                <Link :href="route(
                                                    'car.index',
                                                    {
                                                        rentType: 2,
                                                    }
                                                )
                                                    " :class="[
                                                                active
                                                                    ? 'tw-bg-gray-100 tw-text-gray-900 tw-outline-none dark:bg-white/5 dark:tw-text-white'
                                                                    : 'tw-text-gray-700 dark:tw-text-gray-300',
                                                                'tw-block tw-px-4 tw-py-2 tw-text-sm',
                                                            ]" preserve-scroll>
                                                Ridesharing
                                                </Link>
                                                </MenuItem>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                <Link :href="route('car.index', {
                                                    rentType: 3,
                                                })
                                                    " :class="[
                                                            active
                                                                ? 'tw-bg-gray-100 tw-text-gray-900 tw-outline-none dark:tw-bg-white/5 dark:tw-text-white'
                                                                : 'tw-text-gray-700 dark:tw-text-gray-300',
                                                            'tw-block tw-px-4 tw-py-2 tw-text-sm',
                                                        ]" preserve-scroll>
                                                Rent a Car
                                                </Link>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                <Link :href="route('car.index', {
                                                    rentType: 1,
                                                })
                                                    " :class="[
                                                            active
                                                                ? 'tw-bg-gray-100 tw-text-gray-900 tw-outline-none dark:tw-bg-white/5 dark:tw-text-white'
                                                                : 'tw-text-gray-700 dark:tw-text-gray-300',
                                                            'tw-block tw-px-4 tw-py-2 tw-text-sm',
                                                        ]" preserve-scroll>
                                                Peer to Peer
                                                </Link>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>

                                <li>
                                    <inertia-link :href="route('faq')"
                                        class="tw-font-medium tw-text-[var(--text)] hover:tw-text-[var(--header)]"><span
                                            class="tw-text-yellow-400">Rent Club</span></inertia-link>
                                </li>
                                <li>
                                    <inertia-link :href="route('contact')"
                                        class="tw-font-medium tw-text-[var(--text)] hover:tw-text-[var(--header)]">Contact</inertia-link>
                                </li>
                                <li>
                                    <inertia-link :href="route('faq')"
                                        class="tw-font-medium tw-text-[var(--text)] hover:tw-text-[var(--header)]">FAQ</inertia-link>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- RIGHT -->
                    <div
                        class="tw-flex tw-items-center tw-justify-end tw-gap-[25px] xl:tw-gap-[30px] 2xl:tw-gap-[70px]">
                        <!-- icon-items (hidden < 1280) -->
                        <div class="tw-hidden xl:tw-flex tw-items-center tw-gap-[15px]">
                            <div
                                class="tw-w-[50px] tw-h-[50px] tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-white tw-text-[var(--theme)] tw-shadow-[0_5px_20px_rgba(0,0,0,0.1)]">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <p class="tw-text-[14px] tw-text-[var(--text)] tw-leading-4">
                                    Sună acum
                                </p>
                                <h6 class="tw-text-[16px] tw-text-[var(--header)] tw-font-semibold">
                                    <a href="tel:+40750477517">+40 750 477 517</a>
                                </h6>
                            </div>
                        </div>

                        <!-- search-icon (border la ≥ xl) -->
                        <a href="#"
                            class="tw-hidden sm:tw-inline-block tw-text-[var(--header)] tw-text-[24px] xl:tw-border-l xl:tw-border-[var(--border)] xl:tw-pl-[30px] hover:tw-text-[var(--theme)]"
                            title="Căutare">
                            <i class="fa-regular fa-magnifying-glass"></i>
                        </a>

                        <!-- HAMBURGER - DOAR MOBIL -->
                        <button class="sm:tw-hidden tw-my-auto text-[20px] text-[var(--header)]"
                            @click="isMobileMenuOpen = !isMobileMenuOpen" aria-label="Deschide meniul">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- BUTON „LIPIT” la dreapta (wedge absolut) -->
        <div
            class="tw-hidden 2xl:tw-flex tw-items-center tw-absolute tw-inset-y-0 tw-right-0 tw-z-[1000] tw-bg-[var(--theme)] hover:tw-bg-[var(--theme2)] tw-w-[360px] [clip-path:polygon(12%_0,100%_0,100%_100%,0%_100%)]">
            <template v-if="page.props.user">
                <div v-if="authUserHasRole('user')">
                    <inertia-link :href="route('user.profile.show')"
                        class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center tw-text-white tw-text-[16px] tw-font-medium tw-px-[40px]">
                        Intră în Dashboard
                    </inertia-link>
                </div>
                <div v-if="authUserHasRole('company-owner')">
                    <inertia-link :href="route('company-owner.profile.edit')"
                        class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center tw-text-white tw-text-[16px] tw-font-medium tw-px-[40px]">
                        Intră în Dashboard
                    </inertia-link>
                </div>
                <div v-if="authUserHasRole('admin')">
                    <inertia-link :href="route('admin.dashboard')"
                        class="tw-h-full tw-w-full tw-flex tw-items-center tw-justify-center tw-text-white tw-text-[16px] tw-font-medium tw-px-[40px]">
                        Intră în Dashboard
                    </inertia-link>
                </div>
            </template>
            <template v-else>
                <div class="tw-flex tw-h-full tw-items-center tw-gap-1">
                    <inertia-link :href="route('login')"
                        class="tw-flex tw-items-center tw-justify-center tw-text-white tw-text-[16px] tw-font-medium tw-px-[24px] tw-py-2 tw-transition hover:tw-opacity-80">
                        Autentificare
                    </inertia-link>
                    <inertia-link :href="route('register')"
                        class="tw-flex tw-items-center tw-justify-center tw-rounded-lg tw-bg-white tw-text-[var(--theme)] tw-text-[16px] tw-font-semibold tw-px-[24px] tw-py-2 tw-mr-[16px] tw-transition hover:tw-bg-white/90">
                        Înregistrează-te
                    </inertia-link>
                </div>
            </template>
        </div>
        <MenuHeaderMobile v-model:open="isMobileMenuOpen" />
    </header>
</template>

<script setup>
import { ref } from "vue";
import MenuHeaderMobile from "./MenuHeaderMobile.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

const page = usePage();
const isMobileMenuOpen = ref(false);
</script>
