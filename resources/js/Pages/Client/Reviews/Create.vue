<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-4xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8">
            <!-- Header vehicul -->
            <div class="tw-mt-6 tw-rounded-2xl tw-overflow-hidden tw-shadow tw-bg-white">
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3">
                    <!-- Imagine vehicul -->
                    <div
                        class="tw-relative tw-col-span-1 tw-bg-gray-100 tw-aspect-[16/9] md:tw-aspect-auto md:tw-h-full">
                        <img :src="imageFromAwsPublic(vehicle.cover)" :alt="vehicleTitle"
                            class="tw-h-full tw-w-full tw-object-cover" />
                        <!-- badge peste imagine cu brand + model -->
                        <div
                            class="tw-absolute tw-bottom-3 tw-left-3 tw-rounded-full tw-backdrop-blur tw-bg-black/40 tw-text-white tw-text-xs tw-font-medium tw-px-3 tw-py-1">
                            {{ vehicle.brand }} {{ vehicle.model }}
                        </div>
                    </div>

                    <!-- Text + meta -->
                    <div class="tw-col-span-2 tw-p-6 md:tw-p-7 tw-flex tw-flex-col tw-gap-3">
                        <div class="tw-flex tw-items-start tw-justify-between tw-gap-3">
                            <h1 class="tw-text-2xl tw-font-semibold tw-text-gray-900">
                                Scrie un review
                            </h1>
                            <!-- indicativ pași (opțional) -->
                            <span
                                class="tw-hidden md:tw-inline-flex tw-items-center tw-rounded-full tw-bg-indigo-50 tw-text-indigo-700 tw-text-xs tw-font-medium tw-px-3 tw-py-1">
                                Pasul 1/1
                            </span>
                        </div>

                        <!-- Badge-uri info -->
                        <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-2">
                            <span
                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-bg-gray-100 tw-text-gray-700 tw-text-xs tw-font-medium tw-px-3 tw-py-1">
                                <!-- car icon -->
                                <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2 12h20M5 12l2-5h10l2 5M6 16h.01M18 16h.01M6 16a2 2 0 1 0 4 0M14 16a2 2 0 1 0 4 0" />
                                </svg>
                                Vehicul
                            </span>

                            <span v-if="vehicle.owner"
                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-rounded-full tw-bg-indigo-50 tw-text-indigo-700 tw-text-xs tw-font-medium tw-px-3 tw-py-1">
                                <!-- user icon -->
                                <svg class="tw-h-4 tw-w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A7 7 0 0 1 12 14a7 7 0 0 1 6.879 3.804M15 10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                Proprietar: {{ vehicle.owner }}
                            </span>
                        </div>

                        <!-- Titlul mașinii, evidențiat -->
                        <div class="tw-mt-1">
                            <div class="tw-text-base tw-text-gray-500">Pentru</div>
                            <div class="tw-text-xl tw-font-bold tw-tracking-tight tw-bg-clip-text tw-text-transparent"
                                style="background-image: linear-gradient(90deg, #111827, #4f46e5);">
                                {{ vehicle.brand }} — {{ vehicle.model }}
                            </div>
                        </div>

                        <!-- Mic îndemn -->
                        <p class="tw-mt-1.5 tw-text-sm tw-text-gray-600">
                            O recenzie onestă îi ajută pe alți clienți să ia decizii mai bune. Îți mulțumim!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formular -->
            <form @submit.prevent="submit" class="tw-mt-6 tw-space-y-6">
                <!-- Rating (jumătăți) -->
                <div class="tw-rounded-2xl tw-bg-white tw-shadow tw-p-5">
                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Rating</label>

                    <div class="tw-mt-3 tw-flex tw-items-center tw-gap-1">
                        <!-- 5 stele, fiecare cu 2 zone clicabile (jumătăți) -->
                        <div v-for="s in 5" :key="s" class="tw-relative tw-h-10 tw-w-10 tw-cursor-pointer" role="radio"
                            :aria-checked="form.rating >= s - 0.5" aria-label="Stea">
                            <i :class="starClass(s)" class="tw-text-3xl tw-transition-colors"></i>

                            <!-- jumătatea stângă -->
                            <button type="button" class="tw-absolute tw-inset-y-0 tw-left-0 tw-w-1/2 tw-bg-transparent"
                                @click="setRating(s - 0.5)" :aria-label="`Setează ${s - 0.5} stele`"></button>

                            <!-- jumătatea dreaptă -->
                            <button type="button" class="tw-absolute tw-inset-y-0 tw-right-0 tw-w-1/2 tw-bg-transparent"
                                @click="setRating(s)" :aria-label="`Setează ${s} stele`"></button>
                        </div>

                        <span class="tw-ml-3 tw-text-sm tw-text-gray-600">
                            {{ ratingLabel }}
                        </span>

                    </div>

                    <p v-if="form.errors.rating" class="tw-mt-2 tw-text-xs tw-text-red-600">
                        {{ form.errors.rating }}
                    </p>
                </div>

                <!-- Titlu -->
                <div class="tw-rounded-2xl tw-bg-white tw-shadow tw-p-5">
                    <label for="title" class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Titlu</label>
                    <input id="title" v-model.trim="form.title" type="text" placeholder="Ex: Mașină foarte îngrijită"
                        class="tw-mt-2 tw-text-black tw-block tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-gray-900 focus:tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                    <p v-if="form.errors.title" class="tw-mt-2 tw-text-xs tw-text-red-600">
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- Descriere -->
                <div class="tw-rounded-2xl tw-bg-white tw-shadow tw-p-5">
                    <label for="description"
                        class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Descriere</label>
                    <textarea id="description" v-model.trim="form.description" rows="5"
                        placeholder="Povestește experiența ta..."
                        class="tw-mt-2 tw-block tw-text-black tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white focus:tw-text-black tw-py-2 tw-px-3 tw-text-gray-900 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"></textarea>
                    <p v-if="form.errors.description" class="tw-mt-2 tw-text-xs tw-text-red-600">
                        {{ form.errors.description }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="tw-flex tw-justify-end tw-gap-3">
                    <inertia-link :href="route('user.client_reviews.index')"
                        class="tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50">
                        Anulează
                    </inertia-link>

                    <button type="submit"
                        class="tw-rounded-lg tw-bg-indigo-600 tw-px-4 tw-py-2 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700 tw-disabled:tw-opacity-60 tw-disabled:tw-cursor-not-allowed"
                        :disabled="form.processing || !isReady"
                        :class="{ 'tw-cursor-not-allowed tw-bg-red-700 hover:tw-bg-red-700': !isReady }">
                        Trimite review
                    </button>

                </div>
            </form>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { ref, computed, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    vehicle: { type: Object, required: true },
});

const vehicleTitle = computed(() => `${props.vehicle.brand} ${props.vehicle.model}`);

// Form
const form = useForm({
    vehicle_id: props.vehicle.id, 
    rating: 0,                   
    title: "",
    description: "",
});

const errorMessage = ref("");

const ratingLabel = computed(() => (form.rating > 0 ? `${form.rating} / 5` : "Alege un rating"));

const starClass = (s) => {
    const full = form.rating >= s;
    const half = form.rating >= s - 0.5 && form.rating < s;
    if (full) return "fa-solid fa-star tw-text-yellow-500";
    if (half) return "fa-solid fa-star-half-stroke tw-text-yellow-500";
    return "fa-regular fa-star tw-text-gray-300";
};

const setRating = (val) => {
    const rounded = Math.max(0, Math.min(5, Math.round(val * 2) / 2));
    form.rating = rounded;
};

const isReady = ref(false);
watch(
    [() => form.rating, () => form.title, () => form.description],
    () => {
        if (errorMessage.value) errorMessage.value = "";
        isReady.value = form.rating > 0 && form.title && form.description;
    },
    { immediate: true }
);

// Submit
const submit = () => {

    form.post(route("user.client_reviews.store"), {
        preserveScroll: true,
        onSuccess: () => {
            errorMessage.value = "";
            router.visit(route("user.client_reviews.index"), { replace: true });
        },
        onError: (errors) => {
            errorMessage.value =
                (errors && Object.values(errors)[0]) ||
                "A apărut o eroare. Încearcă din nou.";
        },
    });
};
</script>
