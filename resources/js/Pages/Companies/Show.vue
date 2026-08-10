<template>
    <AppLayout>
        <section class="tw-bg-gradient-to-b from-slate-50 tw-to-white">
            <div class="tw-mx-auto sm:tw-px-6 tw-py-8 lg:tw-px-32">
                <!-- CARD HEADER -->
                <Transition
                    appear
                    enter-active-class="tw-duration-500 tw-ease-out"
                    enter-from-class="tw-opacity-0 tw-translate-y-2"
                    enter-to-class="tw-opacity-100 tw-translate-y-0"
                >
                    <div
                        class="tw-relative tw-overflow-hidden tw-rounded-3xl tw-bg-white tw-shadow-sm tw-ring-1 ring-black/5"
                    >
                        <div class="tw-p-6 sm:tw-p-8">
                            <div class="tw-flex tw-items-start tw-gap-4">
                                <!-- Logo / fallback inițiale -->
                                <div
                                    class="tw-h-20 tw-w-20 tw-rounded-2xl tw-bg-slate-100 tw-overflow-hidden tw-ring-1 ring-black/5 tw-flex tw-items-center tw-justify-center"
                                >
                                    <img
                                        v-if="company.logo"
                                        :src="company.logo"
                                        :alt="company.name + ' logo'"
                                        class="tw-h-full tw-w-full tw-object-contain"
                                    />
                                    <span
                                        v-else
                                        class="tw-text-2xl tw-font-semibold tw-text-slate-500"
                                        >{{ initials }}</span
                                    >
                                </div>

                                <div class="tw-flex-1">
                                    <h1
                                        class="tw-text-3xl sm:tw-text-4xl tw-font-bold tw-tracking-tight tw-text-slate-900"
                                    >
                                        {{ company.name }}
                                    </h1>
                                    <p
                                        v-if="company.description"
                                        class="tw-mt-2 tw-text-slate-600 tw-leading-relaxed"
                                    >
                                        {{ company.description }}
                                    </p>

                                    <div
                                        class="tw-mt-5 tw-flex tw-flex-wrap tw-items-center tw-gap-3"
                                    >
                                        <a
                                            v-if="company.website"
                                            :href="
                                                normalizeUrl(company.website)
                                            "
                                            target="_blank"
                                            rel="noopener"
                                            class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-bg-[var(--theme)] tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-white hover:tw-bg-blue-700 tw-transition"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                class="tw-h-4 tw-w-4"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M13 7h4m0 0v4m0-4l-6 6"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M8 7h-1a3 3 0 00-3 3v7a3 3 0 003 3h7a3 3 0 003-3v-1"
                                                />
                                            </svg>
                                            Vezi site firmă parteneră
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalii -->
                        <div
                            class="tw-border-t tw-border-white/10 tw-p-6 sm:tw-p-8 tw-bg-[var(--theme2)] tw-ml-4 tw-mr-4 tw-rounded-3xl"
                        >
                            <dl
                                class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-6"
                            >
                                <!-- Email -->
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <svg
                                        class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                    <div>
                                        <dt
                                            class="tw-text-sm tw-font-semibold tw-text-white"
                                        >
                                            Email
                                        </dt>
                                        <dd class="tw-text-sm tw-text-white/85">
                                            <a
                                                v-if="company.email"
                                                :href="`mailto:${company.email}`"
                                                class="tw-text-white tw-underline tw-decoration-white/50 hover:tw-decoration-white"
                                            >
                                                {{ company.email }}
                                            </a>
                                            <span
                                                v-else
                                                class="tw-text-white/60"
                                                >—</span
                                            >
                                        </dd>
                                    </div>
                                </div>

                                <!-- Telefon -->
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <svg
                                        class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6v2a10 10 0 0010 10h2v-1a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-1C9.163 24 0 14.837 0 3V2a2 2 0 012-2h2a2 2 0 012 2v1z"
                                        />
                                    </svg>
                                    <div>
                                        <dt
                                            class="tw-text-sm tw-font-semibold tw-text-white"
                                        >
                                            Telefon
                                        </dt>
                                        <dd class="tw-text-sm tw-text-white/85">
                                            <a
                                                v-if="company.phone"
                                                :href="`tel:${company.phone}`"
                                                class="tw-text-white tw-underline tw-decoration-white/50 hover:tw-decoration-white"
                                            >
                                                {{ company.phone }}
                                            </a>
                                            <span
                                                v-else
                                                class="tw-text-white/60"
                                                >—</span
                                            >
                                        </dd>
                                    </div>
                                </div>

                                <!-- Adresă -->
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <svg
                                        class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 22s8-4.5 8-12a8 8 0 10-16 0c0 7.5 8 12 8 12z"
                                        />
                                    </svg>
                                    <div>
                                        <dt
                                            class="tw-text-sm tw-font-semibold tw-text-white"
                                        >
                                            Adresă
                                        </dt>
                                        <dd class="tw-text-sm tw-text-white/85">
                                            {{ company.address || "—" }}
                                        </dd>
                                    </div>
                                </div>

                                <!-- Website -->
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <svg
                                        class="tw-h-5 tw-w-5 tw-text-white/70 tw-mt-0.5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4a8 8 0 018 8h-3a5 5 0 10-5 5v3a8 8 0 010-16z"
                                        />
                                    </svg>
                                    <div>
                                        <dt
                                            class="tw-text-sm tw-font-semibold tw-text-white"
                                        >
                                            Website
                                        </dt>
                                        <dd class="tw-text-sm tw-text-white/85">
                                            <a
                                                v-if="company.website"
                                                :href="
                                                    normalizeUrl(
                                                        company.website
                                                    )
                                                "
                                                target="_blank"
                                                rel="noopener"
                                                class="tw-text-white tw-underline tw-decoration-white/50 hover:tw-decoration-white"
                                            >
                                                {{ prettyUrl(company.website) }}
                                            </a>
                                            <span
                                                v-else
                                                class="tw-text-white/60"
                                                >—</span
                                            >
                                        </dd>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                </Transition>

                <!-- Harta + carte adresă -->
                <Transition
                    appear
                    enter-active-class="tw-duration-500 tw-ease-out"
                    enter-from-class="tw-opacity-0 tw-translate-y-2"
                    enter-to-class="tw-opacity-100 tw-translate-y-0"
                >
                    <div
                        class="tw-mt-10 tw-grid tw-grid-cols-1 lg:tw-grid-cols-3 tw-gap-6"
                    >
                        <div class="lg:tw-col-span-2 tw-ml-4 tw-mr-4">
                            <div
                                class="tw-overflow-hidden tw-rounded-2xl tw-ring-1 ring-black/5"
                            >
                                <MapboxMap
                                    v-if="mapCenter"
                                    :center="mapCenter"
                                    :zoom="14"
                                    :interactive="false"
                                    :markers="[{ id: 'company', position: mapCenter, label: company?.name }]"
                                    class="tw-w-full tw-h-[360px]"
                                />
                                <div
                                    v-else
                                    class="tw-flex tw-h-[360px] tw-items-center tw-justify-center tw-bg-slate-50 tw-text-slate-500"
                                >
                                    Locația nu este disponibilă.
                                </div>
                            </div>
                        </div>
                        <div
                            class="tw-flex tw-flex-col tw-justify-between tw-rounded-2xl tw-bg-white tw-p-6 tw-shadow-sm tw-ring-1 ring-black/5"
                        >
                            <div>
                                <h3
                                    class="tw-text-lg tw-font-semibold tw-text-slate-900"
                                >
                                    Locație companie
                                </h3>
                                <p class="tw-mt-2 tw-text-sm tw-text-slate-600">
                                    {{
                                        company.address ||
                                        "Adresă indisponibilă"
                                    }}
                                </p>
                            </div>
                            <div class="tw-mt-4">
                                <a
                                    :href="mapExternalLink"
                                    target="_blank"
                                    rel="noopener"
                                    class="tw-inline-flex tw-items-center tw-gap-2 tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-slate-700 hover:tw-bg-slate-50 tw-transition"
                                >
                                    Deschide în Google Maps
                                    <svg
                                        class="tw-h-4 tw-w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M13 7h4m0 0v4m0-4l-6 6"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </Transition>

                <!-- Galerie / Carousel -->
                <Transition
                    appear
                    enter-active-class="tw-duration-500 tw-ease-out"
                    enter-from-class="tw-opacity-0 tw-translate-y-2"
                    enter-to-class="tw-opacity-100 tw-translate-y-0"
                >
                    <div class="tw-mt-12 tw-ml-4 tw-mr-4">
                        <div
                            class="tw-flex tw-items-center tw-justify-between tw-gap-4 tw-mb-4"
                        >
                            <h2
                                class="tw-text-xl sm:tw-text-2xl tw-font-semibold tw-text-slate-900"
                            >
                                Galerie foto
                            </h2>
                            <div
                                v-if="slides.length"
                                class="tw-flex tw-items-center tw-gap-2"
                            >
                                <button
                                    @click="prev"
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-white/90 hover:tw-bg-white tw-shadow tw-p-2 tw-ring-1 ring-black/5"
                                    :disabled="!slides.length"
                                    aria-label="Imaginea anterioară"
                                >
                                    <svg
                                        class="tw-h-5 tw-w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 19l-7-7 7-7"
                                        />
                                    </svg>
                                </button>
                                <button
                                    @click="next"
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-white/90 hover:tw-bg-white tw-shadow tw-p-2 tw-ring-1 ring-black/5"
                                    :disabled="!slides.length"
                                    aria-label="Următoarea imagine"
                                >
                                    <svg
                                        class="tw-h-5 tw-w-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div v-if="slides.length" class="tw-relative">
                            <!-- Viewport -->
                            <div
                                class="tw-overflow-hidden tw-rounded-2xl tw-ring-1 ring-black/5"
                                @mouseenter="pause = true"
                                @mouseleave="pause = false"
                            >
                                <!-- Track -->
                                <!-- Track: fără gap -->
                                <div
                                    class="tw-flex tw-duration-500 tw-ease-[cubic-bezier(.22,1,.36,1)]"
                                    :style="{
                                        transform: `translate3d(-${
                                            currentIndex * (100 / perView)
                                        }%, 0, 0)`,
                                    }"
                                    style="will-change: transform"
                                >
                                    <!-- Slide -->
                                    <div
                                        v-for="(src, idx) in slides"
                                        :key="idx"
                                        :style="{
                                            flex: `0 0 ${100 / perView}%`,
                                        }"
                                        class="tw-relative tw-shrink-0 tw-w-full tw-box-border tw-p-2"
                                    >
                                        <!-- Cutia imaginii: fundal alb, contur fin, colțuri rotunde -->
                                        <div
                                            class="tw-h-[360px] tw-w-full tw-rounded-xl tw-bg-white tw-ring-1 ring-black/5 tw-flex tw-items-center tw-justify-center tw-overflow-hidden"
                                        >
                                            <img
                                                :src="imageFromAwsPublic(src)"
                                                :alt="`${company.name} photo ${
                                                    idx + 1
                                                }`"
                                                class="tw-max-h-full tw-max-w-full tw-object-contain"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dots -->
                            <div
                                class="tw-mt-3 tw-flex tw-justify-center tw-gap-2"
                            >
                                <button
                                    v-for="i in totalPages"
                                    :key="i"
                                    @click="goPage(i - 1)"
                                    :aria-label="`Pagina ${i}`"
                                    class="tw-h-2.5 tw-w-2.5 tw-rounded-full tw-transition"
                                    :class="
                                        i - 1 === currentPage
                                            ? 'tw-bg-blue-600'
                                            : 'tw-bg-slate-300 hover:tw-bg-slate-400'
                                    "
                                />
                            </div>
                        </div>

                        <div
                            v-else
                            class="tw-rounded-2xl tw-border tw-border-dashed tw-border-slate-200 tw-p-10 tw-text-center tw-text-slate-500"
                        >
                            Nu există imagini în galerie.
                        </div>
                    </div>
                </Transition>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import MapboxMap from "@/Components/Map/MapboxMap.vue";
import { computed, ref, watch, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
    company: { type: Object, required: true },
});

// Slides
const slides = computed(() => {
    const c = props.company;
    return Array.isArray(c?.gallery_images) ? c.gallery_images : [];
});

const currentIndex = ref(0);
const pause = ref(false);
let timer = null;

const perView = ref(1);
function updatePerView() {
    const w = window.innerWidth;
    if (w >= 1024) perView.value = 3; // lg
    else if (w >= 768) perView.value = 2; // md
    else perView.value = 1; // sm
}

const totalPages = computed(() =>
    Math.max(1, Math.ceil(slides.value.length / perView.value))
);
const currentPage = computed(() =>
    Math.floor(currentIndex.value / perView.value)
);

function startAutoplay() {
    stopAutoplay();
    timer = setInterval(() => {
        if (pause.value || !slides.value.length) return;
        const lastStart = Math.max(0, slides.value.length - perView.value);
        currentIndex.value =
            currentIndex.value >= lastStart
                ? 0
                : currentIndex.value + perView.value;
    }, 3500);
}
function stopAutoplay() {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
}

onMounted(() => {
    updatePerView();
    window.addEventListener("resize", updatePerView);
    startAutoplay();
});
onBeforeUnmount(() => {
    window.removeEventListener("resize", updatePerView);
    stopAutoplay();
});

watch([slides, perView], () => {
    const lastStart = Math.max(0, slides.value.length - perView.value);
    if (currentIndex.value > lastStart) currentIndex.value = 0;
});

function prev() {
    if (!slides.value.length) return;
    currentIndex.value = Math.max(0, currentIndex.value - perView.value);
}

function next() {
    if (!slides.value.length) return;
    const lastStart = Math.max(0, slides.value.length - perView.value);
    currentIndex.value = Math.min(
        lastStart,
        currentIndex.value + perView.value
    );
}

function goPage(page) {
    const start = page * perView.value;
    const lastStart = Math.max(0, slides.value.length - perView.value);
    currentIndex.value = Math.min(lastStart, start);
}

// Inițiale logo
const initials = computed(() => {
    const n = props.company?.name || "";
    if (!n) return "??";
    return n
        .split(/\s+/)
        .filter(Boolean)
        .map((w) => w[0])
        .slice(0, 2)
        .join("")
        .toUpperCase();
});

// Hartă
const hasCoords = computed(
    () => !!(props.company?.latitude && props.company?.longitude)
);
const hasMap = computed(() => hasCoords.value || !!props.company?.address);

// Mapbox lucreaza cu coordonate, nu cu un URL de embed.
// Daca firma are doar adresa, fara coordonate, harta nu se afiseaza —
// coordonatele se completeaza prin geocodare cand se salveaza profilul.
const mapCenter = computed(() => {
    const lat = parseFloat(props.company?.latitude);
    const lng = parseFloat(props.company?.longitude);
    if (Number.isFinite(lat) && Number.isFinite(lng)) return { lat, lng };
    return null;
});

const mapExternalLink = computed(() => {
    if (hasCoords.value) {
        return `https://www.google.com/maps/search/?api=1&query=${props.company.latitude},${props.company.longitude}`;
    }
    if (props.company?.address) {
        return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
            props.company.address
        )}`;
    }
    return "#";
});

function normalizeUrl(url) {
    if (!url) return "#";
    return /^https?:\/\//i.test(url) ? url : `https://${url}`;
}
function prettyUrl(url) {
    if (!url) return "";
    const u = url.replace(/^https?:\/\//i, "");
    return u.replace(/\/$/, "");
}
</script>

<style scoped></style>
