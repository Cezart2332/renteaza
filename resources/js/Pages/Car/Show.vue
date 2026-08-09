<template>
    <AppLayout>
        <!-- Car Details Section Start -->
        <section class="car-details fix section-padding">
            <div class="container">
                <div class="car-details-wrapper">
                    <div class="row g-5">
                        <div class="col-lg-8">
                            <div class="car-details-items">
                                <div class="car-image">
                                    <img
                                        :src="
                                            imageFromAwsPublic(
                                                vehicle.cover_image
                                            )
                                        "
                                        alt="img"
                                    />
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
                                                    Math.floor(average_rating),
                                                'fa-star-half-alt':
                                                    i ===
                                                        Math.ceil(
                                                            average_rating
                                                        ) &&
                                                    !Number.isInteger(
                                                        average_rating
                                                    ),
                                                'fa-star-o':
                                                    i >
                                                    Math.ceil(average_rating),
                                            }"
                                        ></i>
                                        <span> {{ reviews_nr }} Recenzii</span>
                                    </div>

                                    <h3>
                                        {{ vehicle.brand }} {{ vehicle.model }}
                                    </h3>
                                    <h6>
                                        €{{ vehicle.price_per_day }}
                                        <span>/ Zi</span>
                                    </h6>

                                    <div class="icon-details-area">
                                        <h4>Caracteristici</h4>
                                        <div class="icon-details-main-items">
                                            <div
                                                class="icon-items"
                                                v-for="(
                                                    item, index
                                                ) in keyFeaturesLeft"
                                                :key="index"
                                            >
                                                <div
                                                    class="icon flex items-center justify-center"
                                                >
                                                    <img
                                                        :src="
                                                            imagePath(item.icon)
                                                        "
                                                        alt="img"
                                                    />
                                                </div>
                                                <div class="content">
                                                    <h6>{{ item.label }}</h6>
                                                    <p>{{ item.value }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="icon-details-main-items">
                                            <div
                                                class="icon-items"
                                                v-for="(
                                                    item, index
                                                ) in keyFeaturesRight"
                                                :key="index"
                                            >
                                                <div
                                                    class="icon flex items-center justify-center"
                                                >
                                                    <img
                                                        :src="
                                                            imagePath(item.icon)
                                                        "
                                                        alt="img"
                                                    />
                                                </div>
                                                <div class="content">
                                                    <h6>{{ item.label }}</h6>
                                                    <p>{{ item.value }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="price-table-area">
                                        <h6>
                                            Prețuri
                                            <span>( pe zi a săptămânii )</span>
                                        </h6>
                                        <div
                                            v-for="(price, index) in prices"
                                            :key="index"
                                            :class="[
                                                'price-table-items',
                                                index % 2 === 0
                                                    ? 'section-bg'
                                                    : '',
                                            ]"
                                        >
                                            <p>{{ price.day }}</p>
                                            <p>€{{ price.amount }}</p>
                                        </div>
                                    </div>

                                    <!-- <div class="car-video">
                                        <img src="/assets/img/car/car-details-2.jpg" alt="img" />
                                        <div class="video-box">
                                            <a href="https://www.youtube.com/watch?v=Cn4G2lZ_g2I"
                                                class="video-btn ripple video-popup" target="_blank">
                                                <i class="fa-solid fa-play"></i>
                                            </a>
                                        </div>
                                    </div> -->
                                </div>
                            </div>

                            <!-- Booking Request Form -->
                            <!-- Acest formular poate fi legat mai târziu cu backend -->
                            <!-- <div class="car-booking-items">
                                <div class="booking-header">
                                    <h3>Request for Booking</h3>
                                    <p>Send your requirement to us. We will check email and contact you soon.</p>
                                </div>
                                <form action="#" method="POST" class="contact-form-items">
                                    <div class="row g-4">
                                        <div class="col-lg-6" v-for="field in bookingFields" :key="field.id">
                                            <div class="form-clt">
                                                <label class="label-text">{{ field.label }}</label>
                                                <input :type="field.type" :name="field.name" :id="field.id"
                                                    :placeholder="field.placeholder" />
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-clt">
                                                <label class="label-text">Pick-up Location</label>
                                                <select name="pickup-location" class="category">
                                                    <option>Select Location</option>
                                                    <option>Houston</option>
                                                    <option>Texas</option>
                                                    <option>New York</option>
                                                    <option>Other Location</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-4" v-for="picker in ['Pick-up Date', 'Drop-off Date']"
                                            :key="picker">
                                            <div class="form-clt">
                                                <label class="label-text">{{ picker }}</label>
                                                <div class="input-group date">
                                                    <input class="form-control" type="text" placeholder="Check in"
                                                        readonly />
                                                    <span class="input-group-addon">
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-5">
                                            <div class="input-save-items-area">
                                                <div class="input-save-items">
                                                    <div class="input-save d-flex align-items-center mb-3">
                                                        <input type="checkbox" id="saveForNext1" />
                                                        <label for="saveForNext1">Driver</label>
                                                    </div>
                                                    <div class="input-save d-flex align-items-center">
                                                        <input type="checkbox" id="saveForNext2" />
                                                        <label for="saveForNext2">Baby Seat</label>
                                                    </div>
                                                </div>
                                                <div class="input-save-items">
                                                    <div class="input-save d-flex align-items-center mb-3">
                                                        <label>$10.00 / Day</label>
                                                    </div>
                                                    <div class="input-save d-flex align-items-center">
                                                        <label>$30.00 / Total</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <button class="theme-btn" type="submit">Send Request</button>
                                        </div>
                                    </div>
                                </form>
                            </div> -->

                            <!-- Reviews -->
                            <!-- <div class="comment-reviews">
                                <h3>2 Reviews</h3>
                                <div v-for="(review, index) in reviews" :key="index"
                                    class="car-single-comment d-flex gap-4 pb-5">
                                    <div class="image">
                                        <img :src="review.image" alt="image" />
                                    </div>
                                    <div class="content">
                                        <div
                                            class="head d-flex flex-wrap gap-3 align-items-center justify-content-between">
                                            <div class="con">
                                                <h4>{{ review.name }}</h4>
                                            </div>
                                            <div class="star">
                                                <i class="fa-solid fa-star" v-for="i in 5" :key="i"></i>
                                            </div>
                                        </div>
                                        <p class="mt-4">{{ review.comment }}</p>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <!-- Sidebar Booking Form -->
                        <div class="col-lg-4">
                            <!-- Sidebar Booking Form (Tailwind-only) -->
                            <div class="tw-w-full">
                                <div
                                    class="tw-rounded-2xl tw-overflow-hidden tw-shadow-sm"
                                >
                                    <!-- Header -->
                                    <div
                                        class="tw-bg-blue-700 tw-text-white tw-px-5 tw-py-4 tw-text-lg tw-font-semibold"
                                    >
                                        Formular De Rezervare
                                    </div>

                                    <!-- Body -->
                                    <div
                                        class="tw-bg-gray-100 tw-p-5 tw-space-y-6"
                                    >
                                        <!-- Loc de ridicare -->
                                        <div>
                                            <label
                                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-800"
                                            >
                                                Loc de ridicare
                                            </label>
                                            <select
                                                v-model="form.pickupLocation"
                                                class="tw-mt-2 tw-block tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-3 tw-text-gray-900 focus:tw-border-blue-500 focus:tw-ring-2 focus:tw-ring-blue-500"
                                            >
                                                <option disabled value="">
                                                    Selectează
                                                </option>
                                                <option
                                                    v-for="loc in pickupLocations"
                                                    :key="loc.id"
                                                    :value="loc.id"
                                                >
                                                    {{ loc.name }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Tip de închiriere -->
                                        <div>
                                            <label
                                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-800"
                                            >
                                                Tip de închiriere
                                            </label>
                                            <select
                                                v-model="form.rentalType"
                                                class="tw-mt-2 tw-block tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-3 tw-text-gray-900 focus:tw-border-blue-500 focus:tw-ring-2 focus:tw-ring-blue-500"
                                            >
                                                <option disabled value="">
                                                    Selectează
                                                </option>
                                                <option
                                                    v-for="rent in rentalTypes"
                                                    :key="rent.id"
                                                    :value="rent.id"
                                                >
                                                    {{ rent.label }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Perioadă -->
                                        <div>
                                            <label
                                                class="tw-block tw-text-sm tw-font-semibold tw-text-gray-800"
                                            >
                                                Perioadă
                                            </label>
                                            <Flatpickr
                                                :config="pickerConfig"
                                                :key="
                                                    JSON.stringify(
                                                        disabledRanges
                                                    )
                                                "
                                                class="tw-mt-2 tw-block tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-3 tw-text-black focus:tw-border-blue-500 focus:tw-ring-2 focus:tw-ring-blue-500"
                                            />
                                            <p
                                                class="tw-mt-2 tw-text-xs tw-text-gray-500"
                                            >
                                                Selectează un interval (start →
                                                end). Zilele tăiate sunt deja
                                                rezervate.
                                            </p>
                                        </div>

                                        <!-- Actions / Auth states -->
                                        <div class="tw-pt-1">
                                            <!-- Not logged in -->
                                            <template v-if="!$page.props.user">
                                                <div
                                                    class="tw-flex tw-items-start tw-gap-3 tw-rounded-lg tw-border tw-border-red-200 tw-bg-red-50 tw-p-4"
                                                >
                                                    <!-- info icon -->
                                                    <svg
                                                        class="tw-h-5 tw-w-5 tw-text-red-500 tw-mt-0.5"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M18 10A8 8 0 11.001 10 8 8 0 0118 10zM9 7a1 1 0 102 0 1 1 0 00-2 0zm.25 2.5a.75.75 0 000 1.5H10v4.25a.75.75 0 001.5 0V10A1.5 1.5 0 0010 8.5H9.25z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    <div
                                                        class="tw-text-sm tw-text-red-700"
                                                    >
                                                        Trebuie să fii
                                                        <span
                                                            class="tw-font-semibold"
                                                            >logat</span
                                                        >
                                                        pentru a face o
                                                        rezervare.
                                                    </div>
                                                    <inertia-link
                                                        :href="route('login')"
                                                        class="tw-ml-auto tw-inline-flex tw-items-center tw-justify-center tw-rounded-md tw-bg-red-500 tw-px-3 tw-py-1.5 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-red-600 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-red-500"
                                                    >
                                                        Login
                                                    </inertia-link>
                                                </div>
                                            </template>

                                            <!-- Not verified -->
                                            <template
                                                v-else-if="
                                                    $page.props.user.status !==
                                                    'accepted'
                                                "
                                            >
                                                <div
                                                    class="tw-flex tw-items-start tw-gap-3 tw-rounded-lg tw-border tw-border-yellow-200 tw-bg-yellow-50 tw-p-4"
                                                >
                                                    <svg
                                                        class="tw-h-5 tw-w-5 tw-text-yellow-500 tw-mt-0.5"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.518 11.591c.76 1.351-.207 3.06-1.742 3.06H3.48c-1.535 0-2.502-1.71-1.742-3.06L8.257 3.1zM9 13a1 1 0 102 0 1 1 0 00-2 0zm1-6a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </svg>
                                                    <div
                                                        class="tw-text-sm tw-text-yellow-800"
                                                    >
                                                        Contul tău nu este
                                                        <span
                                                            class="tw-font-semibold"
                                                            >verificat</span
                                                        >. Te rugăm să îți
                                                        verifici statusul
                                                        contului și documentele
                                                        înainte de a rezerva.
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Submit -->
                                            <template v-else>
                                                <button
                                                    type="submit"
                                                    @click.prevent="submit"
                                                    :disabled="
                                                        form.processing ||
                                                        !isFormComplete
                                                    "
                                                    class="tw-inline-flex tw-w-full tw-items-center tw-justify-center tw-gap-2 tw-rounded-lg tw-bg-blue-600 tw-px-4 tw-py-2.5 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-blue-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 disabled:tw-cursor-not-allowed disabled:tw-opacity-50"
                                                >
                                                    <!-- calendar icon -->
                                                    <svg
                                                        class="tw-h-5 tw-w-5"
                                                        viewBox="0 0 20 20"
                                                        fill="currentColor"
                                                    >
                                                        <path
                                                            d="M6 2a1 1 0 011 1v1h6V3a1 1 0 112 0v1h1a2 2 0 012 2v2H3V6a2 2 0 012-2h1V3a1 1 0 011-1z"
                                                        />
                                                        <path
                                                            d="M3 9h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                                        />
                                                    </svg>
                                                    Rezervă
                                                </button>

                                                <p
                                                    v-if="errorMessage"
                                                    class="tw-mt-2 tw-text-sm tw-text-red-600"
                                                >
                                                    {{ errorMessage }}
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Similar Cars -->
        <section class="car-rentals-section-2 section-padding fix pt-0">
            <div class="container">
                <div
                    class="section-title text-center flex flex-col items-center justify-center pt-80"
                >
                    <img src="/assets/img/sub-icon.png" alt="icon-img" />
                    <!-- in romana -->
                    <span>Mașinile noastre noi</span>
                    <h2>Mașini similare disponibile</h2>
                </div>
                <div class="row">
                    <div
                        class="col-xl-4 col-lg-6 col-md-6"
                        v-for="(car, index) in similarVehicles"
                        :key="index"
                    >
                        <div class="car-rentals-items">
                            <div class="car-image">
                                <img
                                    :src="imageFromAwsPublic(car.cover_image)"
                                    alt="img"
                                />
                            </div>
                            <div class="car-content">
                                <div class="post-cat">{{ car.year }} Model</div>
                                <div class="star">
                                    <i
                                        v-for="i in 5"
                                        :key="i"
                                        class="fa-solid"
                                        :class="{
                                            'fa-star':
                                                i <=
                                                Math.floor(car.average_rating),
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
                                                Math.ceil(car.average_rating),
                                        }"
                                    ></i>
                                    <span> {{ car.reviews_nr }} Recenzii</span>
                                </div>
                                <h4>
                                    <inertia-link
                                        :href="route('car.show', car.slug)"
                                        >{{ car.brand }}
                                        {{ car.model }}</inertia-link
                                    >
                                </h4>
                                <h6>
                                    {{ car.price_per_day }}€ <span>/ Zi</span>
                                </h6>
                                <div class="icon-items">
                                    <ul>
                                        <li>
                                            <img :src="imagePath('seat.svg')" />
                                            {{ car.seats }}
                                        </li>
                                        <li>
                                            <img :src="imagePath('door.svg')" />
                                            {{ car.doors }}
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            <img
                                                :src="
                                                    imagePath('automatic.svg')
                                                "
                                            />
                                            Automatic
                                        </li>
                                        <li>
                                            <img
                                                :src="imagePath('petrol.svg')"
                                            />
                                            Petrol
                                        </li>
                                    </ul>
                                </div>
                                <inertia-link
                                    :href="route('car.show', car.slug)"
                                    class="theme-btn bg-color w-100 text-center"
                                >
                                    Rezervă
                                    <i class="fa-solid fa-arrow-right ps-1"></i>
                                </inertia-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-cheap-rental-section">
            <div class="container">
                <div class="cta-cheap-rental">
                    <div class="cta-cheap-rental-left">
                        <div class="logo-thumb">
                            <a href="index.php"
                                ><img
                                    src="assets/img/logo/white-logo.svg"
                                    alt="logo-img"
                            /></a>
                        </div>
                        <h4 class="text-white">
                            Save big with our cheap car rental
                        </h4>
                    </div>
                    <div class="social-icon d-flex align-items-center">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </section>
        <Notification />
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import Flatpickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { computed, ref, watch } from "vue";
import Notification from "@/Components/Notification.vue";

const page = usePage();

const props = defineProps({
    vehicle: {
        type: Object,
        required: true,
    },
    similarVehicles: {
        type: Array,
        required: true,
    },
    pickupLocations: {
        type: Array,
        required: true,
    },
    bookedRanges: { type: Array, required: true },
    reviews_nr: {
        type: Number,
    },
    average_rating: {
        type: Number,
    },
    rentalTypes: {
        type: Array,
        required: true,
    },
});

const keyFeaturesLeft = [
    { label: "Tip:", value: props.vehicle.car_type, icon: "07.png" },
    { label: "Kilometraj:", value: props.vehicle.mileage, icon: "07.png" },
    { label: "An:", value: props.vehicle.year, icon: "07.png" },
    { label: "Motor:", value: props.vehicle.engine, icon: "07.png" },
];
const keyFeaturesRight = [
    { label: "Pasageri:", value: props.vehicle.seats, icon: "door.svg" },
    { label: "Locuri:", value: props.vehicle.seats, icon: "seat.svg" },
    {
        label: "Transmisie:",
        value: props.vehicle.transmission.name,
        icon: "automatic.svg",
    },
    {
        label: "Combustibil:",
        value: props.vehicle.fuel_type.name,
        icon: "petrol.svg",
    },
];
const prices = [
    { day: "Luni", amount: props.vehicle.price_per_day },
    { day: "Marti", amount: props.vehicle.price_per_day },
    { day: "Miercuri", amount: props.vehicle.price_per_day },
    { day: "Joi", amount: props.vehicle.price_per_day },
    { day: "Vineri", amount: props.vehicle.price_per_day },
    { day: "Sambata", amount: props.vehicle.price_per_day },
    { day: "Duminica", amount: props.vehicle.price_per_day },
];

const form = useForm({
    pickupLocation: "",
    pickupDate: "",
    dropoffDate: "",
    rentalType: "",
});

const disabledRanges = computed(() =>
    (props.bookedRanges || []).map((r) => ({
        from: r.from,
        to: r.to,
    }))
);

const pickerConfig = computed(() => ({
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "today",
    disable: disabledRanges.value,

    onDayCreate: (_dObj, _dStr, fp, dayElem) => {
        const y = fp.formatDate(dayElem.dateObj, "Y-m-d"); // local-safe
        if ((props.bookedRanges || []).some((r) => y >= r.from && y <= r.to)) {
            dayElem.classList.add("is-booked-day");
        }
    },

    onChange: (selectedDates, _dateStr, fp) => {
        form.pickupDate = selectedDates[0]
            ? fp.formatDate(selectedDates[0], "Y-m-d")
            : "";
        form.dropoffDate = selectedDates[1]
            ? fp.formatDate(selectedDates[1], "Y-m-d")
            : "";
    },
}));

const errorMessage = ref("");

watch(
    [
        () => form.pickupLocation,
        () => form.rentalType,
        () => form.pickupDate,
        () => form.dropoffDate,
    ],
    () => {
        if (errorMessage.value) errorMessage.value = "";
    }
);

const isFormComplete = computed(
    () =>
        !!form.pickupLocation &&
        !!form.rentalType &&
        !!form.pickupDate &&
        !!form.dropoffDate
);

const submit = () => {
    errorMessage.value = "";

    if (!isFormComplete.value) {
        errorMessage.value = "Completează toate datele din formular!";
        return;
    }

    form.post(route("car.book", props.vehicle.id), {
        onSuccess: () => {
            errorMessage.value = "";
            form.reset();
        },
        onError: (errors) => {
            const first = Object.values(errors || {})[0];
            errorMessage.value =
                typeof first === "string"
                    ? first
                    : "A apărut o eroare. Verifică datele.";
            console.error(errors);
        },
    });
};
</script>
<style>
.flatpickr-day.flatpickr-disabled {
    position: relative;
    color: #a0aec0;
}

.flatpickr-day.flatpickr-disabled::after {
    content: "";
    position: absolute;
    left: 12%;
    right: 12%;
    top: 50%;
    height: 2px;
    background: #ef4444;
    transform: translateY(-50%) rotate(-18deg);
    opacity: 0.65;
    pointer-events: none;
}

.is-booked-day {
    background: #fee2e2;
}

.theme-btn[disabled] {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
