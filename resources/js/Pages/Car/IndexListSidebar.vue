<script setup>
import CtaCheapRentalSection from "@/Components/CtaCheapRentalSection.vue";
import MapboxMap from "@/Components/Map/MapboxMap.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { reactive, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    cars: { type: Object, required: true }, // paginator object
    filters: { type: Object, required: true }, // { rentType, pickupLocation, pickupDate, dropoffDate, carType }
    rentalTypes: Array,
    locations: Array,
    carTypes: Array,
});

// copie reactivă pentru v-model
const form = reactive({
    rentType: props.filters?.rentType ?? "",
    pickupLocation: props.filters?.pickupLocation ?? "",
    pickupDate: props.filters?.pickupDate ?? "",
    dropoffDate: props.filters?.dropoffDate ?? "",
    carType: props.filters?.carType ?? "",
});

// dacă backendul întoarce filtre noi după un GET, sincronizează local
watch(
    () => props.filters,
    (f) => {
        form.rentType = f?.rentType ?? "";
        form.pickupLocation = f?.pickupLocation ?? "";
        form.pickupDate = f?.pickupDate ?? "";
        form.dropoffDate = f?.dropoffDate ?? "";
        form.carType = f?.carType ?? "";
    },
    { deep: true }
);

import { computed } from "vue";

// util: data de azi în format pentru <input type="date">, în fusul local
function todayLocal() {
    const tz = new Date().getTimezoneOffset() * 60000;
    return new Date(Date.now() - tz).toISOString().slice(0, 10);
}

const today = todayLocal();

// ai deja `form` în componentă; doar adaugă asta:
const minDropoff = computed(() =>
    form.pickupDate && form.pickupDate > today ? form.pickupDate : today
);

// menține corectitudinea: dacă userul schimbă pickup înaintea dropoff
watch(
    () => form.pickupDate,
    (val) => {
        if (form.dropoffDate && form.dropoffDate < val) {
            form.dropoffDate = val;
        }
    }
);

function submit() {
    router.get(
        route("car.index"),
        { ...form },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ["cars", "filters"],
        }
    );
}

const center = ref();

const markers = computed(() => {
    const out = [];
    const cars = Array.isArray(props.cars?.data) ? props.cars.data : [];

    for (const car of cars) {
        if (Array.isArray(car.locations) && car.locations.length) {
            for (const loc of car.locations) {
                const lat = Number(loc.latitude ?? loc.lat);
                const lng = Number(loc.longitude ?? loc.lng);
                if (Number.isFinite(lat) && Number.isFinite(lng)) {
                    out.push({
                        id: `${car.id}-${loc.id ?? "0"}`,
                        position: { lat, lng },
                    });
                }
            }
        } else {
            const lat = Number(car.latitude ?? car.lat);
            const lng = Number(car.longitude ?? car.lng);
            if (Number.isFinite(lat) && Number.isFinite(lng)) {
                out.push({ id: String(car.id), position: { lat, lng } });
            }
        }
    }
    return out;
});

// setează centrul pe primul marker când vin datele
watch(
    markers,
    (ms) => {
        if (ms.length) center.value = { ...ms[0].position, ...ms[1].position };
    },
    { immediate: true }
);
</script>

<template>
    <AppLayout>
        <!-- Car Rentals Section Start -->
        <div class="tw-flex tw-flex-row tw-w-12/12 tw-px-24 tw-py-12 tw-gap-10">
            <!-- Sidebar -->
            <div class="tw-flex tw-flex-col tw-w-6/12">
                <!-- Card: Caută Mașină -->
                <div
                    class="tw-rounded-2xl tw-overflow-hidden tw-border tw-border-gray-200"
                >
                    <!-- Header albastru -->
                    <div
                        class="tw-bg-[#0D5DB8] tw-text-white tw-px-6 tw-py-4 tw-font-semibold tw-text-lg"
                    >
                        Caută Mașină
                    </div>

                    <!-- Body gri + formular -->
                    <form
                        @submit.prevent="submit"
                        class="tw-bg-[#EDF2F6] tw-p-6 tw-space-y-6"
                    >
                        <!-- Tip închiriere -->
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700"
                                >Tip Închiriere</label
                            >
                            <div
                                class="tw-mt-2 tw-rounded-xl tw-bg-white tw-border tw-border-gray-200 tw-shadow-sm tw-p-3"
                            >
                                <select
                                    v-model="form.rentType"
                                    class="tw-block tw-w-full tw-bg-transparent tw-border-0 tw-text-gray-900 focus:tw-ring-0 focus:tw-outline-none"
                                >
                                    <option disabled value="">Selectați</option>
                                    <option
                                        v-for="type in props.rentalTypes"
                                        :key="type.id"
                                        :value="type.id"
                                    >
                                        {{ type.label }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Locație ridicare -->
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700"
                                >Locație Ridicare</label
                            >
                            <div
                                class="tw-mt-2 tw-rounded-xl tw-bg-white tw-border tw-border-gray-200 tw-shadow-sm tw-p-3"
                            >
                                <select
                                    v-model="form.pickupLocation"
                                    class="tw-block tw-w-full tw-bg-transparent tw-border-0 tw-text-gray-900 focus:tw-ring-0 focus:tw-outline-none"
                                >
                                    <option disabled value="">
                                        Select Location
                                    </option>
                                    <option
                                        v-for="location in props.locations"
                                        :key="location.id"
                                        :value="location.id"
                                    >
                                        {{ location.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Data ridicare -->
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700"
                                >Data ridicare</label
                            >
                            <div
                                class="tw-mt-2 tw-relative tw-rounded-xl tw-bg-white tw-border tw-border-gray-200 tw-shadow-sm"
                            >
                                <input
                                    type="date"
                                    v-model="form.pickupDate"
                                    :min="today"
                                    class="tw-block tw-w-full tw-bg-transparent tw-border-0 tw-px-4 tw-py-2.5 tw-text-gray-900 focus:tw-ring-0 focus:tw-outline-none"
                                />
                                <!-- icon calendar -->
                                <svg
                                    class="tw-pointer-events-none tw-absolute tw-right-3 tw-top-1/2 -tw-translate-y-1/2 tw-h-5 tw-w-5 tw-text-gray-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M6 2a1 1 0 012 0v1h4V2a1 1 0 112 0v1h1a2 2 0 012 2v2H3V5a2 2 0 012-2h1V2z"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 9h14v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9zm4 2a1 1 0 100 2h.01a1 1 0 100-2H7z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Data returnare -->
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700"
                                >Data returnare</label
                            >
                            <div
                                class="tw-mt-2 tw-relative tw-rounded-xl tw-bg-white tw-border tw-border-gray-200 tw-shadow-sm"
                            >
                                <input
                                    type="date"
                                    v-model="form.dropoffDate"
                                    :min="minDropoff"
                                    class="tw-block tw-w-full tw-bg-transparent tw-border-0 tw-px-4 tw-py-2.5 tw-text-gray-900 focus:tw-ring-0 focus:tw-outline-none"
                                />
                                <svg
                                    class="tw-pointer-events-none tw-absolute tw-right-3 tw-top-1/2 -tw-translate-y-1/2 tw-h-5 tw-w-5 tw-text-gray-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M6 2a1 1 0 012 0v1h4V2a1 1 0 112 0v1h1a2 2 0 012 2v2H3V5a2 2 0 012-2h1V2z"
                                    />
                                    <path
                                        fill-rule="evenodd"
                                        d="M3 9h14v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9zm4 2a1 1 0 100 2h.01a1 1 0 100-2H7z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Tip mașină -->
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-700"
                                >Tip Mașină</label
                            >
                            <div
                                class="tw-mt-2 tw-rounded-xl tw-bg-white tw-border tw-border-gray-200 tw-shadow-sm tw-p-3"
                            >
                                <select
                                    v-model="form.carType"
                                    class="tw-block tw-w-full tw-bg-transparent tw-border-0 tw-text-gray-900 focus:tw-ring-0 focus:tw-outline-none"
                                >
                                    <option disabled value="">
                                        Selectează tipul de mașină
                                    </option>
                                    <option
                                        v-for="car in props.carTypes"
                                        :key="car"
                                        :value="car"
                                    >
                                        {{ car }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div>
                            <button
                                type="submit"
                                class="tw-mt-2 tw-w-full tw-rounded-xl tw-bg-[#0D5DB8] tw-py-2.5 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-[#0b53a4] focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#0D5DB8]"
                            >
                                Caută
                            </button>
                        </div>
                    </form>
                </div>
                <div class="woocommerce-notices-wrapper">
                    <div class="product-showing">
                        <p>
                            Afișare {{ props.cars.per_page }} din
                            {{ props.cars.total }} rezultate
                        </p>
                    </div>
                    <!-- <div class="woocommerce-right d-flex align-items-center">
                                    <div class="icon-items">
                                        <a href="#"><i class="fas fa-th"></i></a>
                                        <a href="#"><i class="fa-solid fa-list"></i></a>
                                    </div>
                                </div> -->
                </div>

                <div class="row g-4">
                    <div
                        v-for="car in props.cars.data"
                        :key="car.id"
                        class="col-lg-12"
                    >
                        <inertia-link :href="route('car.show', car.slug)">
                            <div class="car-list-items style-2">
                                <div
                                    class="car-image bg-cover"
                                    :style="`background-image: url('${imageFromAwsPublic(
                                        car.cover_image
                                    )}');`"
                                >
                                    <div class="post-cat">
                                        {{ car.year }} Model
                                    </div>
                                </div>
                                <div class="car-content">
                                    <div class="star">
                                        <i
                                            v-for="i in 5"
                                            :key="i"
                                            class="fa-solid"
                                            :class="{
                                                'fa-star':
                                                    i <=
                                                    Math.floor(
                                                        car.average_rating
                                                    ),
                                                'fa-star-half-alt':
                                                    i ===
                                                        Math.ceil(
                                                            car.average_rating
                                                        ) &&
                                                    !Number.isInteger(
                                                        car.average_rating
                                                    ),
                                                'fa-star-o':
                                                    i >
                                                    Math.ceil(
                                                        car.average_rating
                                                    ),
                                            }"
                                            style="color: gold"
                                        ></i>
                                        <span
                                            >—
                                            {{ car.reviews_nr }}
                                            Recenzii</span
                                        >
                                    </div>

                                    <h6 class="price">
                                        €{{ car.price_per_day }}
                                        <span>/ Zi</span>
                                    </h6>
                                    <h3>
                                        <a :href="`/cars/${car.id}`">
                                            {{ car.brand }}
                                            {{ car.model }}
                                        </a>
                                    </h3>
                                    <p>{{ car.description }}</p>
                                    <ul class="icon-items">
                                        <li>
                                            <img
                                                src="/assets/img/car/seat.svg"
                                                alt="seats"
                                                class="me-1"
                                            />
                                            {{ car.autonomy_km }} km
                                        </li>
                                        <li>
                                            <img
                                                src="/assets/img/car/door.svg"
                                                alt="doors"
                                                class="me-1"
                                            />
                                            {{ car.doors }}
                                        </li>
                                        <li>
                                            <img
                                                src="/assets/img/car/automatic.svg"
                                                alt="transmission"
                                                class="me-1"
                                            />
                                            {{ car.transmission.name }}
                                        </li>
                                        <li>
                                            <img
                                                src="/assets/img/car/petrol.svg"
                                                alt="fuel"
                                                class="me-1"
                                            />
                                            {{ car.fuel_type.name }}
                                        </li>
                                    </ul>
                                    <div class="tw-mt-3">
                                        <inertia-link
                                            v-if="
                                                car.company 
                                            "
                                            :href="route('companies.show', car.company.id)"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class=" tw-flex tw-justify-end tw-gap-2 tw-rounded-lg tw-border tw-border-[var(--theme)] tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-[var(--theme)] hover:tw-bg-[var(--theme)] hover:tw-text-white tw-transition"
                                        >
                                            Vezi site firma parteneră
                                            <!-- icon external -->
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="tw-h-4 tw-w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M13 7h4m0 0v4m0-4L10 14"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h6"
                                                />
                                            </svg>
                                        </inertia-link>
                                    </div>
                                </div>
                            </div>
                        </inertia-link>
                    </div>
                </div>
            </div>
            <div
                class="tw-w-6/12 tw-sticky tw-top-24 tw-self-start tw-h-[calc(100vh-6rem)]"
            >
                <MapboxMap
                    :center="center ?? { lat: 44.4268, lng: 26.1025 }"
                    :zoom="12"
                    :markers="markers"
                    class="tw-w-full tw-h-full"
                    @marker-click="(m) => (center = m.position)"
                />
            </div>
        </div>

        <!-- Cta Cheap Rental Section Start -->
        <CtaCheapRentalSection />
    </AppLayout>
</template>
