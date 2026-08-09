<template>
    <div class="tw-h-screen">
        <!-- Mobile overlay sidebar -->
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog
                class="tw-relative tw-z-50 lg:tw-hidden"
                @close="sidebarOpen = false"
            >
                <!-- Backdrop -->
                <TransitionChild
                    as="template"
                    enter="tw-transition-opacity tw-ease-linear tw-duration-300"
                    enter-from="tw-opacity-0"
                    enter-to="tw-opacity-100"
                    leave="tw-transition-opacity tw-ease-linear tw-duration-300"
                    leave-from="tw-opacity-100"
                    leave-to="tw-opacity-0"
                >
                    <div class="tw-fixed tw-inset-0 bg-gray-900/80" />
                </TransitionChild>

                <div class="tw-fixed tw-inset-0 tw-flex">
                    <!-- Slide-in panel -->
                    <TransitionChild
                        as="template"
                        enter="tw-transition tw-ease-in-out tw-duration-300 tw-transform"
                        enter-from="tw--translate-x-full"
                        enter-to="tw-translate-x-0"
                        leave="tw-transition tw-ease-in-out tw-duration-300 tw-transform"
                        leave-from="tw-translate-x-0"
                        leave-to="tw--translate-x-full"
                    >
                        <DialogPanel
                            id="mobile-sidebar"
                            class="tw-relative tw-flex tw-w-full tw-max-w-xs tw-flex-1"
                        >
                            <!-- Sidebar content -->
                            <div
                                class="tw-flex grow tw-flex-col tw-overflow-y-auto tw-bg-gray-900 tw-ring-1 ring-white/10"
                            >
                                <!-- Header: logo + close -->
                                <div
                                    class="tw-relative tw-flex tw-items-center tw-justify-between tw-h-14 tw-px-4 tw-bg-gradient-to-b tw-from-gray-900 tw-to-gray-800"
                                >
                                    <div
                                        class="tw-flex tw-items-center tw-justify-between tw-mt-4 tw-mb-4 tw-h-16 tw-px-4 tw-bg-gradient-to-r tw-from-gray-900 tw-to-gray-800 tw-border-b tw-border-gray-700"
                                    >
                                        <!-- Stânga: logo + titlu -->
                                        <div
                                            class="tw-flex tw-items-center tw-gap-3"
                                        >
                                            <img
                                                class="tw-h-8 tw-w-auto"
                                                src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                                                alt="Your Company"
                                            />
                                            <span
                                                class="tw-text-lg tw-font-semibold tw-text-white"
                                            >
                                                Dashboard
                                            </span>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        class="tw-inline-flex tw-items-center tw-justify-center tw-h-10 tw-w-10 tw-rounded-full tw-text-gray-300 hover:tw-text-white hover:tw-bg-white/10 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500"
                                        @click="sidebarOpen = false"
                                        aria-label="Închide meniul"
                                    >
                                        <XMarkIcon
                                            class="tw-h-6 tw-w-6"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                                <!-- Nav -->
                                <nav
                                    class="tw-flex tw-flex-1 tw-flex-col tw-px-4 tw-pb-4"
                                >
                                    <ul
                                        role="list"
                                        class="tw-flex tw-flex-1 tw-flex-col tw-gap-y-7"
                                    >
                                        <li>
                                            <!-- PROPRIETAR -->
                                            <ul
                                                role="list"
                                                class="tw--mx-2 tw-space-y-1"
                                            >
                                                <li
                                                    v-for="item in companyOwnerRoutes"
                                                    :key="item.name"
                                                >
                                                    <inertia-link
                                                        :href="
                                                            route(item.route)
                                                        "
                                                        :class="[
                                                            route().current(
                                                                item.route
                                                            )
                                                                ? 'tw-bg-gray-800 tw-text-white'
                                                                : 'tw-text-gray-400 hover:tw-bg-gray-800 hover:tw-text-white',
                                                            'group tw-flex tw-gap-x-3 tw-rounded-md tw-p-2 tw-text-sm/6 tw-font-semibold',
                                                        ]"
                                                        @click="
                                                            sidebarOpen = false
                                                        "
                                                    >
                                                        <component
                                                            :is="item.icon"
                                                            class="tw-size-6 tw-shrink-0"
                                                            aria-hidden="true"
                                                        />
                                                        {{ item.name }}
                                                    </inertia-link>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar (desktop) -->
        <div
            class="tw-hidden lg:tw-fixed lg:tw-inset-y-0 lg:tw-left-0 lg:tw-z-50 lg:tw-flex lg:tw-w-72 lg:tw-flex-col tw-h-screen"
        >
            <div
                class="tw-flex tw-flex-col tw-h-full tw-justify-between tw-bg-gray-900 tw-px-6"
            >
                <div>
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-mt-4 tw-mb-4 tw-h-16 tw-px-4 tw-bg-gradient-to-r tw-from-gray-900 tw-to-gray-800 tw-border-b tw-border-gray-700"
                    >
                        <!-- Stânga: logo + titlu -->
                        <div class="tw-flex tw-items-center tw-gap-3">
                            <img
                                class="tw-h-8 tw-w-auto"
                                src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                                alt="Your Company"
                            />
                            <span
                                class="tw-text-lg tw-font-semibold tw-text-white"
                            >
                                Dashboard
                            </span>
                        </div>
                    </div>
                    <nav class="tw-flex tw-flex-1 tw-flex-col tw-mt-4">
                        <ul
                            role="list"
                            class="tw-flex tw-flex-1 tw-flex-col tw-gap-y-7"
                        >
                            <li>
                                <ul role="list" class="tw--mx-2 tw-space-y-1">
                                    <li
                                        v-for="item in companyOwnerRoutes"
                                        :key="item.name"
                                    >
                                        <inertia-link
                                            :href="route(item.route)"
                                            :class="[
                                                route().current(item.route)
                                                    ? 'tw-bg-gray-800 tw-text-white'
                                                    : 'tw-text-gray-400 hover:tw-bg-gray-800 hover:tw-text-white',
                                                'group tw-flex tw-gap-x-3 tw-rounded-md tw-p-2 tw-text-sm/6 tw-font-semibold',
                                            ]"
                                        >
                                            <component
                                                :is="item.icon"
                                                class="tw-size-6 tw-shrink-0"
                                                aria-hidden="true"
                                            />
                                            {{ item.name }}
                                        </inertia-link>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Bottom: logout -->
                <div class="tw-mb-6">
                    <a
                        href="#"
                        class="tw-flex tw-items-center tw-gap-x-4 tw-py-3 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-gray-800"
                    >
                        <div @click="logout">
                            <span>Logout</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Top bar (mobile only) -->
        <div
            class="tw-sticky tw-top-0 tw-z-40 tw-flex tw-items-center tw-gap-x-6 tw-bg-gray-900 tw-px-4 tw-py-4 tw-shadow-sm sm:tw-px-6 lg:tw-hidden"
        >
            <button
                type="button"
                class="tw--m-2.5 tw-p-2.5 tw-text-gray-400 lg:tw-hidden"
                @click="sidebarOpen = true"
            >
                <span class="tw-sr-only">Open sidebar</span>
                <Bars3Icon class="tw-size-6" aria-hidden="true" />
            </button>
            <div class="tw-flex-1 tw-text-sm/6 tw-font-semibold tw-text-white">
                Dashboard
            </div>
            <a href="#"><span class="tw-sr-only">Your profile</span></a>
        </div>

        <!-- Main content -->
        <main class="lg:tw-pl-72 tw-min-h-screen tw-bg-[#F2F4F5]">
            <div class="tw-py-10 tw-px-4 tw-sm:tw-px-6 lg:tw-px-8">
                <slot />
            </div>
        </main>
    </div>

    <!-- Notifications -->
    <div
        aria-live="assertive"
        class="tw-pointer-events-none tw-fixed tw-inset-0 tw-flex tw-items-start tw-justify-end tw-px-4 tw-py-6 sm:tw-p-6"
    >
        <div class="tw-flex tw-w-full tw-flex-col tw-items-center tw-space-y-4">
            <transition
                enter-active-class="tw-transform tw-ease-out tw-duration-300 tw-transition"
                enter-from-class="tw-translate-y-2 tw-opacity-0 sm:tw-translate-y-0 sm:tw-translate-x-2"
                enter-to-class="tw-translate-y-0 tw-opacity-100 sm:tw-translate-x-0"
                leave-active-class="tw-transition tw-ease-in tw-duration-100"
                leave-from-class="tw-opacity-100"
                leave-to-class="tw-opacity-0"
            >
                <div
                    v-if="show"
                    class="tw-pointer-events-auto tw-w-full tw-max-w-sm tw-overflow-hidden tw-rounded-lg tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5"
                >
                    <div class="tw-p-4">
                        <div class="tw-flex tw-items-start">
                            <template v-if="$page.props.message">
                                <div class="tw-flex-shrink-0">
                                    <CheckCircleIcon
                                        class="tw-h-6 tw-w-6 tw-text-green-400"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="tw-ml-3 tw-w-0 tw-flex-1 tw-pt-0.5">
                                    <p
                                        class="tw-text-sm tw-font-medium tw-text-gray-900"
                                    >
                                        {{ $page.props.message }}
                                    </p>
                                </div>
                            </template>

                            <template v-if="$page.props.errorMessage">
                                <div class="tw-flex-shrink-0">
                                    <XCircleIcon
                                        class="tw-h-6 tw-w-6 tw-text-red-400"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="tw-ml-3 tw-w-0 tw-flex-1 tw-pt-0.5">
                                    <p
                                        class="tw-text-sm tw-font-medium tw-text-gray-900"
                                    >
                                        {{ errorMessage }}
                                    </p>
                                </div>
                            </template>

                            <div class="tw-ml-4 tw-flex tw-flex-shrink-0">
                                <button
                                    type="button"
                                    @click="show = false"
                                    class="tw-inline-flex tw-rounded-md tw-bg-white tw-text-gray-400 hover:tw-text-gray-500 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-500 focus:tw-ring-offset-2"
                                >
                                    <span class="tw-sr-only">Close</span>
                                    <XMarkIcon
                                        class="tw-h-5 tw-w-5"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, Transition, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";

import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    Bars3Icon,
    UsersIcon,
    XMarkIcon,
    RocketLaunchIcon,
    UserCircleIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";

const page = usePage();
const show = ref(false);
const message = computed(() => page.props.message);
const errorMessage = computed(() => page.props.errorMessage);
const content = ref("");

onMounted(() => {
    router.on("success", () => {
        if (message.value) {
            content.value = message.value;
            show.value = true;
        } else if (errorMessage.value) {
            content.value = errorMessage.value;
            show.value = true;
        }

        setTimeout(() => {
            show.value = false;
            content.value = "";
        }, 3500);
    });
});

const logout = () => {
    router.post(route("logout"));
};

const companyOwnerRoutes = [
    {
        name: "Mini Site",
        route: "company-owner.profile.edit",
        icon: UserCircleIcon,
        current: false,
    },
];

const sidebarOpen = ref(false);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
