<template>
    <OwnerDashboardLayout>
        <div class="tw-max-w-4xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-8">
            <!-- Header -->
            <div
                class="tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-3 tw-mb-6">
                <h1 class="tw-text-2xl tw-font-semibold tw-text-gray-900">
                    Adaugă mașină
                </h1>
                <inertia-link :href="route('user.cars.index')"
                    class="tw-text-sm tw-text-gray-600 hover:tw-text-gray-900">
                    Înapoi la listă
                </inertia-link>
            </div>

            <form @submit.prevent="submit" class="tw-space-y-6" enctype="multipart/form-data">
                <!-- Card: Info de bază -->
                <div class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-4">
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Tip vehicul *</label>
                            <select v-model="form.vehicle_type_id"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500">
                                <option disabled value="">Selectează</option>
                                <option v-for="v in vehicleTypes" :key="v.id" :value="v.id">
                                    {{ v.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.vehicle_type_id" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.vehicle_type_id }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Car Type *</label>
                            <select v-model="form.car_type"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500">
                                <option disabled value="">Selectează</option>
                                <option v-for="t in carTypes" :key="t.value" :value="t.value">
                                    {{ t.label }}
                                </option>
                            </select>
                            <p v-if="form.errors.car_type" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.car_type }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Brand *</label>
                            <input v-model.trim="form.brand" type="text" placeholder="Ex: Tesla"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.brand" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.brand }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Model *</label>
                            <input v-model.trim="form.model" type="text" placeholder="Ex: Model 3"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.model" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.model }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">An fabricație *</label>
                            <input v-model.number="form.year" type="number" min="1990" max="2100"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.year" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.year }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Preț / zi (€) *</label>
                            <input v-model.number="form.price_per_day" type="number" step="0.01" min="0"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.price_per_day" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.price_per_day }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Descriere</label>
                        <textarea v-model.trim="form.description" rows="4" placeholder="Detalii utile despre mașină…"
                            class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"></textarea>
                        <p v-if="form.errors.description" class="tw-text-xs tw-text-red-600 tw-mt-1">
                            {{ form.errors.description }}
                        </p>
                    </div>
                </div>

                <!-- Card: Specificații -->
                <div class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-4">
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Combustibil *</label>
                            <select v-model="form.fuel_type_id"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500">
                                <option disabled value="">Selectează</option>
                                <option v-for="f in fuelTypes" :key="f.id" :value="f.id">
                                    {{ f.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.fuel_type_id" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.fuel_type_id }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Transmisie *</label>
                            <select v-model="form.transmission_id"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500">
                                <option disabled value="">Selectează</option>
                                <option v-for="t in transmissions" :key="t.id" :value="t.id">
                                    {{ t.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.transmission_id" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.transmission_id }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Locuri *</label>
                            <input v-model.number="form.seats" type="number" min="1" max="9"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.seats" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.seats }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Uși *</label>
                            <input v-model.number="form.doors" type="number" min="2" max="6"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.doors" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.doors }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Autonomie (km)</label>
                            <input v-model.number="form.autonomy_km" type="number" min="0"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.autonomy_km" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.autonomy_km }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Baterie (kWh)</label>
                            <input v-model.number="form.battery_capacity_kwh" type="number" min="0" step="0.1"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.battery_capacity_kwh" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.battery_capacity_kwh }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Viteză max (km/h)</label>
                            <input v-model.number="form.max_speed_kph" type="number" min="0"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 tw-text-black focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <p v-if="form.errors.max_speed_kph" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.max_speed_kph }}
                            </p>
                        </div>

                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                Număr înmatriculare
                            </label>
                            <input v-model="form.license_plate" @input="onPlateInput" type="text" maxlength="12"
                                placeholder="Ex: B 123 ABC"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 tw-text-black tw-uppercase tw-tracking-wider focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500" />
                            <small class="tw-text-xs tw-text-gray-500">
                                Doar litere, cifre, spațiu și „-”.
                            </small>
                            <p v-if="form.errors.license_plate" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.license_plate }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card: Relații & locații -->
                <div class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-4">
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Tipuri de închiriere</label>
                        <div class="tw-mt-2 tw-flex tw-flex-wrap tw-gap-3">
                            <label v-for="r in rentalTypes" :key="r.id"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm">
                                <input type="checkbox" :value="r.id" v-model="form.rental_type_ids"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 tw-border-gray-300 focus:tw-ring-indigo-500" />
                                <span>{{ r.label }}</span>
                            </label>
                        </div>
                        <p v-if="form.errors.rental_type_ids" class="tw-text-xs tw-text-red-600 tw-mt-1">
                            {{ form.errors.rental_type_ids }}
                        </p>
                    </div>

                    <!-- === Locații de ridicare (cu Google Places Autocomplete) === -->
                    <div>
                        <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Locații de ridicare</label>

                        <div class="tw-mt-3 tw-space-y-4">
                            <div v-for="(loc, i) in form.locations" :key="i"
                                class="tw-rounded-xl tw-border tw-border-gray-200 tw-p-4 tw-bg-white">
                                <div
                                    class="tw-flex tw-flex-col sm:tw-flex-row tw-justify-between tw-items-start sm:tw-items-center tw-gap-2 tw-mb-3">
                                    <h4 class="tw-text-sm tw-font-semibold tw-text-gray-800">
                                        Locație #{{ i + 1 }}
                                    </h4>
                                    <button type="button" class="tw-text-xs tw-text-red-600 hover:tw-underline"
                                        @click="removeLocation(i)" v-if="form.locations.length > 1">
                                        Șterge
                                    </button>
                                </div>

                                <!-- Google Places Autocomplete -->
                                <div class="tw-mb-3">
                                    <label class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">
                                        Caută și selectează adresa (Google)
                                    </label>
                                    <div class="tw-mt-1">
                                        <AddressSelection :restrict-country="'ro'"
                                            @selected="(addr) => applyAddressToLocation(i, addr)" />
                                    </div>
                                    <small class="tw-text-xs tw-text-gray-500">
                                        La selectarea unei sugestii se completează automat câmpurile de mai jos.
                                    </small>
                                </div>

                                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                                    <!-- name -->
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">Nume</label>
                                        <input v-model="form.locations[i].name" type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-indigo-600 focus:tw-ring-indigo-600" />
                                        <p v-if="errorFor(i, 'name')" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                            {{ errorFor(i, 'name') }}
                                        </p>
                                    </div>

                                    <!-- address -->
                                    <div class="md:tw-col-span-2">
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">Adresă</label>
                                        <input v-model="form.locations[i].address" type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-indigo-600 focus:tw-ring-indigo-600" />
                                        <p v-if="errorFor(i, 'address')" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                            {{ errorFor(i, 'address') }}
                                        </p>
                                    </div>

                                    <!-- city -->
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">Oraș</label>
                                        <input v-model="form.locations[i].city" type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-indigo-600 focus:tw-ring-indigo-600" />
                                        <p v-if="errorFor(i, 'city')" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                            {{ errorFor(i, 'city') }}
                                        </p>
                                    </div>

                                    <!-- postal_code -->
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">Cod
                                            poștal</label>
                                        <input v-model="form.locations[i].postal_code" type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-indigo-600 focus:tw-ring-indigo-600" />
                                        <p v-if="errorFor(i, 'postal_code')" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                            {{ errorFor(i, 'postal_code') }}
                                        </p>
                                    </div>

                                    <!-- country -->
                                    <div>
                                        <label class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">Țară</label>
                                        <input v-model="form.locations[i].country" type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-indigo-600 focus:tw-ring-indigo-600" />
                                        <p v-if="errorFor(i, 'country')" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                            {{ errorFor(i, 'country') }}
                                        </p>
                                    </div>

                                    <!-- latitude -->
                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">Latitudine</label>
                                        <input v-model.number="form.locations[i].latitude" type="number" step="any"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-indigo-600 focus:tw-ring-indigo-600" />
                                        <p v-if="errorFor(i, 'latitude')" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                            {{ errorFor(i, 'latitude') }}
                                        </p>
                                    </div>

                                    <!-- longitude -->
                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700">Longitudine</label>
                                        <input v-model.number="form.locations[i].longitude" type="number" step="any"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-indigo-600 focus:tw-ring-indigo-600" />
                                        <p v-if="errorFor(i, 'longitude')" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                            {{ errorFor(i, 'longitude') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="tw-mt-4 tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-gap-3">
                            <button type="button"
                                class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-indigo-600 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-600"
                                @click="addLocation">
                                + Adaugă locație
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: Imagini -->
                <div class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-4">
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4">
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">Imagine cover</label>
                            <input type="file" accept="image/*" @change="onCoverChange"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3" />
                            <p v-if="form.errors.cover_image" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors.cover_image }}
                            </p>

                            <img v-if="coverPreview" :src="coverPreview"
                                class="tw-mt-3 tw-h-32 tw-w-full sm:tw-w-48 tw-object-cover tw-rounded-lg tw-border" />
                        </div>

                        <!-- input + erori -->
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700">
                                Galerie (multiple)
                            </label>
                            <input type="file" multiple accept="image/*" @change="onGalleryChange"
                                :disabled="galleryItems.length >= 5"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3" />
                            <small class="tw-text-xs tw-text-gray-500">
                                {{ galleryItems.length }}/5 selectate
                            </small>
                            <p v-if="form.errors['gallery_images.0']" class="tw-text-xs tw-text-red-600 tw-mt-1">
                                {{ form.errors['gallery_images.0'] }}
                            </p>

                            <!-- PREVIEW + buton X -->
                            <div v-if="galleryItems.length" class="tw-mt-3 tw-flex tw-flex-wrap tw-gap-3">
                                <div v-for="item in galleryItems" :key="item.id"
                                    class="tw-relative tw-h-24 tw-w-32 tw-overflow-hidden tw-rounded-md tw-border tw-bg-gray-50">
                                    <img :src="item.url" :alt="item.name" class="tw-h-full tw-w-full tw-object-cover" />
                                    <button type="button" @click="removeGalleryItem(item.id)"
                                        class="tw-absolute tw-top-1 tw-right-1 tw-inline-flex tw-h-6 tw-w-6 tw-items-center tw-justify-center tw-rounded-full tw-bg-black/60 tw-text-white hover:tw-bg-black/80"
                                        aria-label="Șterge imaginea" title="Șterge">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="tw-h-4 tw-w-4">
                                            <path fill-rule="evenodd"
                                                d="M10 8.586l4.95-4.95 1.414 1.414L11.414 10l4.95 4.95-1.414 1.414L10 11.414l-4.95 4.95-1.414-1.414L8.586 10l-4.95-4.95L5.05 3.636 10 8.586z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="tw-flex tw-flex-col sm:tw-flex-row tw-justify-end tw-gap-3">
                    <inertia-link :href="route('user.cars.index')"
                        class="tw-inline-flex tw-justify-center tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50">
                        Anulează
                    </inertia-link>

                    <button type="submit"
                        class="tw-rounded-lg tw-bg-indigo-600 tw-px-4 tw-py-2 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700 tw-disabled:tw-opacity-60 tw-disabled:tw-cursor-not-allowed"
                        :disabled="form.processing || !isReady">
                        Salvează
                    </button>
                </div>
            </form>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import AddressSelection from "@/Components/AddressSelection.vue";
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { computed, onBeforeUnmount, ref } from "vue";

const props = defineProps({
    fuelTypes: Array,
    transmissions: Array,
    vehicleTypes: Array,
    rentalTypes: Array,
    locations: Array,
    carTypes: Array,
});

const emptyLocation = () => ({
    name: "",
    address: "",
    city: "",
    postal_code: "",
    country: "",
    latitude: "",
    longitude: "",
});

const normalizeLocation = (l = {}) => ({
    ...emptyLocation(),
    ...l,
});

const initialLocations = props.locations?.length
    ? props.locations.map(normalizeLocation)
    : [emptyLocation()];

const form = useForm({
    vehicle_type_id: "",
    brand: "",
    model: "",
    year: new Date().getFullYear(),
    description: "",
    fuel_type_id: "",
    transmission_id: "",
    autonomy_km: null,
    battery_capacity_kwh: null,
    max_speed_kph: null,
    seats: 5,
    doors: 4,
    cargo_volume_liters: null,
    license_plate: "",
    price_per_day: 0,
    car_type: "",
    cover_image: null,
    gallery_images: [],
    rental_type_ids: [],
    locations: initialLocations,
});

function addLocation() {
    form.locations.push(emptyLocation());
}
function removeLocation(i) {
    form.locations.splice(i, 1);
}
function errorFor(i, field) {
    return form.errors?.[`locations.${i}.${field}`] ?? null;
}

// Apply selection from Google Places Autocomplete to a specific location
function applyAddressToLocation(i, addr) {
    const loc = form.locations[i];
    if (!loc) return;

    // Prefer the structured fields coming from AddressSelection
    const line = addr.location || addr.description || "";
    loc.address = line || loc.address;
    loc.city = addr.locality || loc.city;
    loc.postal_code = addr.postal_code || loc.postal_code;
    loc.country = addr.country || loc.country;

    // Coords
    if (addr.lat != null) loc.latitude = addr.lat;
    if (addr.lng != null) loc.longitude = addr.lng;

    // If name is empty, prefill with a readable label
    if (!loc.name) {
        loc.name = addr.description || line || loc.city || "Locație";
    }
}

function onPlateInput(e) {
    // keep only letters, digits, space and '-' ; uppercase it
    const raw = e.target.value || "";
    const cleaned = raw.replace(/[^A-Za-z0-9\\-\\s]/g, "").toUpperCase();
    if (cleaned !== form.license_plate) {
        form.license_plate = cleaned;
    }
}

const coverPreview = ref(null);

function onCoverChange(e) {
    const file = e.target.files?.[0];
    form.cover_image = file || null;
    coverPreview.value = file ? URL.createObjectURL(file) : null;
}

const galleryItems = ref([]);

function onGalleryChange(e) {
    const files = Array.from(e.target.files || []);

    const newOnes = files
        .filter(
            (f) =>
                !galleryItems.value.some(
                    (g) =>
                        g.file.name === f.name &&
                        g.file.size === f.size &&
                        g.file.lastModified === f.lastModified
                )
        )
        .map((f, i) => ({
            id: `${f.name}-${f.lastModified}-${i}-${Math.random().toString(36).slice(2)}`,
            file: f,
            url: URL.createObjectURL(f),
            name: f.name,
        }));

    galleryItems.value.push(...newOnes);
    form.gallery_images = galleryItems.value.map((x) => x.file);

    e.target.value = "";
}

function removeGalleryItem(id) {
    const idx = galleryItems.value.findIndex((x) => x.id === id);
    if (idx !== -1) {
        URL.revokeObjectURL(galleryItems.value[idx].url);
        galleryItems.value.splice(idx, 1);
        form.gallery_images = galleryItems.value.map((x) => x.file);
    }
}

onBeforeUnmount(() => {
    galleryItems.value.forEach((i) => URL.revokeObjectURL(i.url));
});

const isReady = computed(
    () =>
        form.vehicle_type_id &&
        form.car_type &&
        form.brand &&
        form.model &&
        form.year &&
        form.fuel_type_id &&
        form.transmission_id &&
        form.seats &&
        form.doors &&
        form.price_per_day >= 0
);

function submit() {
    form.post(route("user.cars.store"), {
        forceFormData: true,
        onSuccess: () => { },
    });
}
</script>
