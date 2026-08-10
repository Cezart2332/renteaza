<template>
    <Teleport to="body">
        <!-- OVERLAY (mobil) -->
        <transition name="fade">
            <div
                v-if="open"
                class="tw-fixed tw-inset-0 tw-z-[900] tw-bg-black/40 sm:tw-hidden"
                @click="close"
                aria-hidden="true"
            />
        </transition>

        <!-- PANEL (mobil) -->
        <transition name="slide-right">
            <aside
                v-if="open"
                class="tw-fixed tw-top-0 tw-right-0 tw-z-[99999] tw-h-svh tw-w-[400px] tw-max-[450px]:tw-w-[320px] sm:tw-hidden tw-bg-white tw-shadow-2xl tw-border-l tw-border-slate-200 tw-overflow-y-auto tw-overscroll-contain"
                role="dialog"
                aria-modal="true"
                aria-label="Meniu principal"
                @keydown.esc.window="close"
            >
                <div class="tw-relative tw-h-full tw-p-6 tw-max-[575px]:tw-p-5">
                    <!-- HEADER -->
                    <div
                        class="tw-mb-4 tw-flex tw-items-center tw-justify-between"
                    >
                        <a href="/" class="tw-flex tw-items-center tw-gap-3">
                            <img
                                :src="logoSrc"
                                alt="Renteaza"
                                class="tw-w-[150px]"
                            />
                        </a>

                        <button
                            class="tw-w-[44px] tw-h-[44px] tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-[var(--theme)] hover:tw-bg-[var(--theme2)] tw-text-white tw-transition focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[var(--theme2)] focus:tw-ring-offset-2"
                            @click="close"
                            aria-label="Închide meniul"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- NAV -->
                    <nav class="tw-mt-2 tw-mb-6">
                        <ul class="tw-space-y-1.5 tw-text-slate-700">
                            <li>
                                <Link
                                    :href="route('landing')"
                                    :class="
                                        navItemClass(route().current('landing'))
                                    "
                                >
                                    <i
                                        class="fas fa-home tw-w-5 tw-text-slate-400"
                                    ></i>
                                    <span>Acasă</span>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('car.index')"
                                    :class="
                                        navItemClass(
                                            route().current('car.index')
                                        )
                                    "
                                >
                                    <i
                                        class="fas fa-car tw-w-5 tw-text-slate-400"
                                    ></i>
                                    <span>Mașini</span>
                                </Link>
                            </li>
                            <Menu
                                as="div"
                                class="tw-relative tw-inline-block tw-w-full"
                            >
                                <!-- Butonul arată ca celelalte link-uri din meniu -->
                                <MenuButton :class="navItemBase">
                                    <i
                                        class="fas fa-briefcase tw-w-5 tw-text-slate-400"
                                    ></i>
                                    <span class="tw-flex-1 tw-text-left"
                                        >Servicii</span
                                    >
                                    <ChevronDownIcon
                                        class="tw-size-4 tw-text-slate-400 tw-transition group-data-[headlessui-state=open]:tw-rotate-180"
                                    />
                                </MenuButton>

                                <!-- Panel dropdown: închis implicit și pe mobil -->
                                <transition
                                    enter-active-class="tw-transition tw-ease-out tw-duration-100"
                                    enter-from-class="tw-transform tw-opacity-0 tw-scale-95"
                                    enter-to-class="tw-transform tw-scale-100 tw-opacity-100"
                                    leave-active-class="tw-transition tw-ease-in tw-duration-75"
                                    leave-from-class="tw-transform tw-scale-100 tw-opacity-100"
                                    leave-to-class="tw-transform tw-opacity-0 tw-scale-95"
                                >
                                    <MenuItems
                                        class="tw-absolute tw-left-0 tw-z-30 tw-mt-2 tw-w-56 tw-origin-top-left tw-rounded-md tw-bg-white tw-shadow-lg outline outline-1 outline-black/5"
                                    >
                                        <div class="tw-py-1">
                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    :href="
                                                        route('car.index', {
                                                            rentType: 2,
                                                        })
                                                    "
                                                    :class="[
                                                        'tw-block tw-px-4 tw-py-2 tw-text-sm',
                                                        active
                                                            ? 'tw-bg-gray-100 tw-text-gray-900'
                                                            : 'tw-text-gray-700',
                                                    ]"
                                                    preserve-scroll
                                                >
                                                    Ridesharing
                                                </Link>
                                            </MenuItem>

                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    :href="
                                                        route('car.index', {
                                                            rentType: 3,
                                                        })
                                                    "
                                                    :class="[
                                                        'tw-block tw-px-4 tw-py-2 tw-text-sm',
                                                        active
                                                            ? 'tw-bg-gray-100 tw-text-gray-900'
                                                            : 'tw-text-gray-700',
                                                    ]"
                                                    preserve-scroll
                                                >
                                                    Rent a Car
                                                </Link>
                                            </MenuItem>

                                            <MenuItem v-slot="{ active }">
                                                <Link
                                                    :href="
                                                        route('car.index', {
                                                            rentType: 1,
                                                        })
                                                    "
                                                    :class="[
                                                        'tw-block tw-px-4 tw-py-2 tw-text-sm',
                                                        active
                                                            ? 'tw-bg-gray-100 tw-text-gray-900'
                                                            : 'tw-text-gray-700',
                                                    ]"
                                                    preserve-scroll
                                                >
                                                    Peer to Peer
                                                </Link>
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>

                            <li>
                                <Link
                                    :href="route('faq')"
                                    :class="
                                        navItemClass(route().current('faq'))
                                    "
                                >
                                    <i
                                        class="fas fa-star tw-w-5 tw-text-slate-400"
                                    ></i>
                                    <span>Rent Club</span>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('contact')"
                                    :class="
                                        navItemClass(route().current('contact'))
                                    "
                                >
                                    <i
                                        class="fas fa-envelope tw-w-5 tw-text-slate-400"
                                    ></i>
                                    <span>Contact</span>
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('faq')"
                                    :class="
                                        navItemClass(route().current('faq'))
                                    "
                                >
                                    <i
                                        class="fas fa-question-circle tw-w-5 tw-text-slate-400"
                                    ></i>
                                    <span>FAQ</span>
                                </Link>
                            </li>
                        </ul>

                        <div class="mt-4">
                            <template v-if="!$page.props.user">
                                <inertia-link
                                    :href="route('register')"
                                    class="tw-block tw-w-full tw-rounded-lg tw-border tw-border-[var(--theme2)]/40 tw-bg-[var(--theme)] hover:tw-bg-[var(--theme2)] tw-px-4 tw-py-3 tw-text-center tw-font-semibold tw-text-white tw-transition"
                                >
                                    Înregistrează-te
                                </inertia-link>
                                <inertia-link
                                    :href="route('login')"
                                    class="tw-mt-2 tw-block tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-px-4 tw-py-3 tw-text-center tw-font-semibold tw-text-gray-800 tw-transition hover:tw-bg-gray-50"
                                >
                                    Autentificare
                                </inertia-link>
                            </template>
                            <inertia-link
                                v-else
                                :href="route('user.profile.show')"
                                class="tw-block tw-w-full tw-rounded-lg tw-border tw-border-[var(--theme2)]/40 tw-bg-[var(--theme)] hover:tw-bg-[var(--theme2)] tw-px-4 tw-py-3 tw-text-center tw-font-semibold tw-text-white tw-transition"
                            >
                                Dashboard
                            </inertia-link>
                        </div>
                    </nav>
                </div>
            </aside>
        </transition>
    </Teleport>
</template>

<script setup>
import { onMounted, onBeforeUnmount } from "vue";
import { Link } from "@inertiajs/vue3"; // dacă folosești <Link>. Dacă ai alias pentru <inertia-link>, schimbă aici.
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    open: { type: Boolean, default: false },
    logoSrc: { type: String, default: "/images/logo_renteaza.svg" },
    ctaHref: { type: String, default: "/#rezerva" },
});
const emit = defineEmits(["update:open"]);

const close = () => emit("update:open", false);
const navItemBase =
    "tw-group tw-flex tw-w-full tw-items-center tw-gap-3 tw-rounded-xl tw-px-3 tw-py-3 " +
    "tw-text-[15px] tw-font-medium tw-text-slate-800 hover:tw-bg-slate-100 " +
    "focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[var(--theme2)] focus:tw-ring-offset-2";

function handleResize() {
    if (window.innerWidth >= 640 && props.open) close();
}

function navItemClass(isActive) {
    return [
        "tw-group tw-flex tw-items-center tw-gap-3 tw-rounded-xl tw-px-3 tw-py-3 tw-transition tw-text-[15px] tw-font-medium",
        "hover:tw-bg-slate-100 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-[var(--theme2)] focus:tw-ring-offset-2",
        isActive ? "tw-bg-slate-100 tw-text-slate-900" : "tw-text-slate-800",
    ];
}

onMounted(() => window.addEventListener("resize", handleResize));
onBeforeUnmount(() => window.removeEventListener("resize", handleResize));
</script>

<style scoped>
/* tranziții */
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}

.slide-right-enter-from {
    transform: translateX(100%);
}
.slide-right-leave-to {
    transform: translateX(100%);
}
.slide-right-enter-active,
.slide-right-leave-active {
    transition: transform 0.45s ease-in-out;
}

/* ascunde scrollbar */
aside::-webkit-scrollbar {
    display: none;
}
aside {
    scrollbar-width: none;
}
</style>
