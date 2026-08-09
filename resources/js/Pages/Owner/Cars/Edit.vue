<!-- resources/js/Pages/Owner/Cars/Edit.vue -->
<template>
    <OwnerDashboardLayout>
        <div
            class="tw-max-w-4xl tw-mx-auto tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-8"
        >
            <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
                <h1 class="tw-text-2xl tw-font-semibold tw-text-gray-900">
                    Editează mașină
                </h1>
                <inertia-link
                    :href="route('user.cars.index')"
                    class="tw-text-sm tw-text-gray-600 hover:tw-text-gray-900"
                >
                    Înapoi la listă
                </inertia-link>
            </div>

            <form
                @submit.prevent="submit"
                class="tw-space-y-6"
                enctype="multipart/form-data"
            >
                <!-- Card: Info de bază -->
                <div
                    class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-4"
                >
                    <div
                        class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4"
                    >
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Tip vehicul *</label
                            >
                            <select
                                v-model="form.vehicle_type_id"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            >
                                <option disabled value="">Selectează</option>
                                <option
                                    v-for="v in vehicleTypes"
                                    :key="v.id"
                                    :value="v.id"
                                >
                                    {{ v.name }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.vehicle_type_id"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.vehicle_type_id }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Car Type *</label
                            >
                            <select
                                v-model="form.car_type"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            >
                                <option disabled value="">Selectează</option>
                                <option
                                    v-for="t in carTypes"
                                    :key="t.value"
                                    :value="t.value"
                                >
                                    {{ t.label }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.car_type"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.car_type }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Brand *</label
                            >
                            <input
                                v-model.trim="form.brand"
                                type="text"
                                class="tw-mt-1 focus:tw-text-black tw-text-black tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.brand"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.brand }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Model *</label
                            >
                            <input
                                v-model.trim="form.model"
                                type="text"
                                class="tw-mt-1 focus:tw-text-black tw-text-black tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.model"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.model }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >An fabricație *</label
                            >
                            <input
                                v-model.number="form.year"
                                type="number"
                                min="1990"
                                max="2100"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.year"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.year }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Preț / zi (€) *</label
                            >
                            <input
                                v-model.number="form.price_per_day"
                                type="number"
                                step="0.01"
                                min="0"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.price_per_day"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.price_per_day }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label
                            class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                            >Descriere</label
                        >
                        <textarea
                            v-model.trim="form.description"
                            rows="4"
                            class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                        ></textarea>
                        <p
                            v-if="form.errors.description"
                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                        >
                            {{ form.errors.description }}
                        </p>
                    </div>
                </div>

                <!-- Card: Specificații -->
                <div
                    class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-4"
                >
                    <div
                        class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4"
                    >
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Combustibil *</label
                            >
                            <select
                                v-model="form.fuel_type_id"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            >
                                <option disabled value="">Selectează</option>
                                <option
                                    v-for="f in fuelTypes"
                                    :key="f.id"
                                    :value="f.id"
                                >
                                    {{ f.name }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.fuel_type_id"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.fuel_type_id }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Transmisie *</label
                            >
                            <select
                                v-model="form.transmission_id"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            >
                                <option disabled value="">Selectează</option>
                                <option
                                    v-for="t in transmissions"
                                    :key="t.id"
                                    :value="t.id"
                                >
                                    {{ t.name }}
                                </option>
                            </select>
                            <p
                                v-if="form.errors.transmission_id"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.transmission_id }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Locuri *</label
                            >
                            <input
                                v-model.number="form.seats"
                                type="number"
                                min="1"
                                max="9"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.seats"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.seats }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Uși *</label
                            >
                            <input
                                v-model.number="form.doors"
                                type="number"
                                min="2"
                                max="6"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.doors"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.doors }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Autonomie (km)</label
                            >
                            <input
                                v-model.number="form.autonomy_km"
                                type="number"
                                min="0"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.autonomy_km"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.autonomy_km }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Baterie (kWh)</label
                            >
                            <input
                                v-model.number="form.battery_capacity_kwh"
                                type="number"
                                min="0"
                                step="0.1"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.battery_capacity_kwh"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.battery_capacity_kwh }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Viteză max (km/h)</label
                            >
                            <input
                                v-model.number="form.max_speed_kph"
                                type="number"
                                min="0"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500"
                            />
                            <p
                                v-if="form.errors.max_speed_kph"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.max_speed_kph }}
                            </p>
                        </div>

                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Număr înmatriculare</label
                            >
                            <input
                                v-model="form.license_plate"
                                @input="onPlateInput"
                                type="text"
                                maxlength="12"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border focus:tw-text-black tw-text-black tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500 tw-uppercase tw-tracking-wider"
                            />
                            <small class="tw-text-xs tw-text-gray-500"
                                >Doar litere, cifre, spațiu și „-”.</small
                            >
                            <p
                                v-if="form.errors.license_plate"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.license_plate }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Card: Relații & locații -->
                <div
                    class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-4"
                >
                    <div>
                        <label
                            class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                            >Tipuri de închiriere</label
                        >
                        <div class="tw-mt-2 tw-flex tw-flex-wrap tw-gap-3">
                            <label
                                v-for="r in rentalTypes"
                                :key="r.id"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm"
                            >
                                <input
                                    type="checkbox"
                                    :value="r.id"
                                    v-model="form.rental_type_ids"
                                    class="tw-h-4 tw-w-4 tw-text-indigo-600 tw-border-gray-300 focus:tw-ring-indigo-500"
                                />
                                <span>{{ r.label }}</span>
                            </label>
                        </div>
                        <p
                            v-if="form.errors.rental_type_ids"
                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                        >
                            {{ form.errors.rental_type_ids }}
                        </p>
                    </div>

                    <!-- Locații inline -->
                    <div>
                        <label
                            class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                            >Locații de ridicare</label
                        >

                        <div class="tw-mt-3 tw-space-y-4">
                            <div
                                v-for="(loc, i) in form.locations"
                                :key="i"
                                class="tw-rounded-xl tw-border tw-border-gray-200 tw-p-4 tw-bg-white"
                            >
                                <div
                                    class="tw-flex tw-justify-between tw-items-center tw-mb-3"
                                >
                                    <h4
                                        class="tw-text-sm tw-font-semibold tw-text-gray-800"
                                    >
                                        Locație #{{ i + 1 }}
                                    </h4>
                                    <button
                                        type="button"
                                        class="tw-text-xs tw-text-red-600 hover:tw-underline"
                                        @click="removeLocation(i)"
                                        v-if="form.locations.length > 1"
                                    >
                                        Șterge
                                    </button>
                                </div>

                                <div
                                    class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4"
                                >
                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700"
                                            >Nume</label
                                        >
                                        <input
                                            v-model="form.locations[i].name"
                                            type="text"
                                            class="tw-mt-1 tw-block focus:tw-text-black tw-text-black tw-w-full tw-rounded-md tw-border-gray-300 focus:tw-border-indigo-600"
                                        />
                                        <p
                                            v-if="errorFor(i, 'name')"
                                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                                        >
                                            {{ errorFor(i, "name") }}
                                        </p>
                                    </div>

                                    <div class="md:tw-col-span-2">
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700"
                                            >Adresă</label
                                        >
                                        <input
                                            v-model="form.locations[i].address"
                                            type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md focus:tw-text-black tw-text-black tw-border-gray-300 focus:tw-ring-indigo-600 focus:tw-border-indigo-600"
                                        />
                                        <p
                                            v-if="errorFor(i, 'address')"
                                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                                        >
                                            {{ errorFor(i, "address") }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700"
                                            >Oraș</label
                                        >
                                        <input
                                            v-model="form.locations[i].city"
                                            type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md focus:tw-text-black tw-text-black tw-border-gray-300 focus:tw-ring-indigo-600 focus:tw-border-indigo-600"
                                        />
                                        <p
                                            v-if="errorFor(i, 'city')"
                                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                                        >
                                            {{ errorFor(i, "city") }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700"
                                            >Cod poștal</label
                                        >
                                        <input
                                            v-model="
                                                form.locations[i].postal_code
                                            "
                                            type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md focus:tw-text-black tw-text-black tw-border-gray-300 focus:tw-ring-indigo-600 focus:tw-border-indigo-600"
                                        />
                                        <p
                                            v-if="errorFor(i, 'postal_code')"
                                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                                        >
                                            {{ errorFor(i, "postal_code") }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700"
                                            >Țară</label
                                        >
                                        <input
                                            v-model="form.locations[i].country"
                                            type="text"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md focus:tw-text-black tw-text-black tw-border-gray-300 focus:tw-ring-indigo-600 focus:tw-border-indigo-600"
                                        />
                                        <p
                                            v-if="errorFor(i, 'country')"
                                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                                        >
                                            {{ errorFor(i, "country") }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700"
                                            >Latitudine</label
                                        >
                                        <input
                                            v-model.number="
                                                form.locations[i].latitude
                                            "
                                            type="number"
                                            step="any"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md focus:tw-text-black tw-text-black tw-border-gray-300 focus:tw-ring-indigo-600 focus:tw-border-indigo-600"
                                        />
                                        <p
                                            v-if="errorFor(i, 'latitude')"
                                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                                        >
                                            {{ errorFor(i, "latitude") }}
                                        </p>
                                    </div>

                                    <div>
                                        <label
                                            class="tw-block tw-text-xs tw-font-medium tw-text-gray-700"
                                            >Longitudine</label
                                        >
                                        <input
                                            v-model.number="
                                                form.locations[i].longitude
                                            "
                                            type="number"
                                            step="any"
                                            class="tw-mt-1 tw-block tw-w-full tw-rounded-md focus:tw-text-black tw-text-black tw-border-gray-300 focus:tw-ring-indigo-600 focus:tw-border-indigo-600"
                                        />
                                        <p
                                            v-if="errorFor(i, 'longitude')"
                                            class="tw-text-xs tw-text-red-600 tw-mt-1"
                                        >
                                            {{ errorFor(i, "longitude") }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tw-mt-4">
                            <button
                                type="button"
                                class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-indigo-600 tw-px-3 tw-py-2 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700"
                                @click="addLocation"
                            >
                                + Adaugă locație
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: Imagini -->
                <div
                    class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-5 tw-space-y-6"
                >
                    <!-- COVER -->
                    <div
                        class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-4"
                    >
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Imagine cover</label
                            >

                            <input
                                type="file"
                                accept="image/*"
                                @change="onCoverChange"
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3"
                            />

                            <div
                                class="tw-flex tw-items-center tw-gap-3 tw-mt-2"
                                v-if="currentCoverUrl || coverPreview"
                            >
                                <img
                                    :src="
                                        coverPreview ||
                                        imageFromAwsPublic(currentCoverUrl)
                                    "
                                    class="tw-w-24"
                                    alt="img"
                                />
                            </div>

                            <p
                                v-if="form.errors.cover_image"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.cover_image }}
                            </p>
                        </div>

                        <!-- GALERIE -->
                        <div>
                            <label
                                class="tw-block tw-text-sm tw-font-medium tw-text-gray-700"
                                >Galerie (multiple)</label
                            >

                            <input
                                type="file"
                                multiple
                                accept="image/*"
                                @change="onGalleryChange"
                                :disabled="
                                    newGalleryItems.length +
                                        remainingExistingGalleryCount >=
                                    GALLERY_MAX
                                "
                                class="tw-mt-1 tw-w-full tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-px-3"
                            />

                            <small class="tw-text-xs tw-text-gray-500">
                                {{
                                    newGalleryItems.length +
                                    remainingExistingGalleryCount
                                }}/{{ GALLERY_MAX }} selectate
                            </small>

                            <p
                                v-if="form.errors.new_images_to_save"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors.new_images_to_save }}
                            </p>
                            <p
                                v-if="form.errors['new_images_to_save.0']"
                                class="tw-text-xs tw-text-red-600 tw-mt-1"
                            >
                                {{ form.errors["new_images_to_save.0"] }}
                            </p>

                            <!-- EXISTENTE -->
                            <div
                                v-if="existingGallery.length"
                                class="tw-mt-3 tw-flex tw-flex-wrap tw-gap-3"
                            >
                                <div
                                    v-for="path in existingGallery"
                                    :key="path"
                                    v-show="!removedExistingGallery.has(path)"
                                    class="tw-relative tw-h-24 tw-w-32 tw-overflow-hidden tw-rounded-md tw-border tw-bg-gray-50"
                                >
                                    <img
                                        :src="imageFromAwsPublic(path)"
                                        alt="gallery"
                                        class="tw-h-full tw-w-full tw-object-cover"
                                    />
                                    <button
                                        type="button"
                                        @click="toggleRemoveExisting(path)"
                                        class="tw-absolute tw-top-1 tw-right-1 tw-inline-flex tw-h-6 tw-w-6 tw-items-center tw-justify-center tw-rounded-full tw-bg-black/60 tw-text-white hover:tw-bg-black/80"
                                        title="Șterge"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </div>

                            <!-- NOI -->
                            <div
                                v-if="newGalleryItems.length"
                                class="tw-mt-3 tw-flex tw-flex-wrap tw-gap-3"
                            >
                                <div
                                    v-for="item in newGalleryItems"
                                    :key="item.id"
                                    class="tw-relative tw-h-24 tw-w-32 tw-overflow-hidden tw-rounded-md tw-border tw-bg-gray-50"
                                >
                                    <img
                                        :src="item.url"
                                        :alt="item.name"
                                        class="tw-h-full tw-w-full tw-object-cover"
                                    />
                                    <button
                                        type="button"
                                        @click="removeNewGalleryItem(item.id)"
                                        class="tw-absolute tw-top-1 tw-right-1 tw-inline-flex tw-h-6 tw-w-6 tw-items-center tw-justify-center tw-rounded-full tw-bg-black/60 tw-text-white hover:tw-bg-black/80"
                                        title="Șterge"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="tw-flex tw-justify-end tw-gap-3">
                    <inertia-link
                        :href="route('user.cars.index')"
                        class="tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-gray-700 hover:tw-bg-gray-50"
                    >
                        Anulează
                    </inertia-link>

                    <button
                        type="submit"
                        class="tw-rounded-lg tw-bg-indigo-600 tw-px-4 tw-py-2 tw-text-sm tw-font-semibold tw-text-white hover:tw-bg-indigo-700 tw-disabled:tw-opacity-60 tw-disabled:tw-cursor-not-allowed"
                        :disabled="form.processing || !isReady"
                    >
                        Salvează
                    </button>
                </div>
            </form>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { computed, onBeforeUnmount, ref } from "vue";

const GALLERY_MAX = 5;

const props = defineProps({
    vehicle: { type: Object, required: true },
    fuelTypes: Array,
    transmissions: Array,
    vehicleTypes: Array,
    rentalTypes: Array,
    carTypes: Array,
});

const emptyLocation = () => ({
    id: null,
    name: "",
    address: "",
    city: "",
    postal_code: "",
    country: "",
    latitude: "",
    longitude: "",
});
const normalizeLocation = (l = {}) => ({ ...emptyLocation(), ...l });

// ---- initiale din vehicle
const initialRentalTypeIds =
    props.vehicle?.rental_types?.map((r) => r.id) ??
    props.vehicle?.rental_type_ids ??
    [];
const initialLocations = props.vehicle?.locations?.length
    ? props.vehicle.locations.map(normalizeLocation)
    : [emptyLocation()];

// ---- FORM
const form = useForm({
    vehicle_type_id: props.vehicle.vehicle_type_id ?? "",
    brand: props.vehicle.brand ?? "",
    model: props.vehicle.model ?? "",
    year: props.vehicle.year ?? new Date().getFullYear(),
    description: props.vehicle.description ?? "",
    fuel_type_id: props.vehicle.fuel_type_id ?? "",
    transmission_id: props.vehicle.transmission_id ?? "",
    autonomy_km: props.vehicle.autonomy_km ?? null,
    battery_capacity_kwh: props.vehicle.battery_capacity_kwh ?? null,
    max_speed_kph: props.vehicle.max_speed_kph ?? null,
    seats: props.vehicle.seats ?? 5,
    doors: props.vehicle.doors ?? 4,
    cargo_volume_liters: props.vehicle.cargo_volume_liters ?? null,
    license_plate: props.vehicle.license_plate ?? "",
    price_per_day: props.vehicle.price_per_day ?? 0,
    car_type: props.vehicle.car_type ?? "",
    // imagini
    cover_image: null, // fișier nou
    new_images_to_save: [], // fișiere noi
    images_to_remove: [], 
    // relații
    rental_type_ids: initialRentalTypeIds,
    locations: initialLocations,
});

// ---- Locations helpers
function addLocation() {
    form.locations.push(emptyLocation());
}
function removeLocation(i) {
    form.locations.splice(i, 1);
}
function errorFor(i, field) {
    return form.errors?.[`locations.${i}.${field}`] ?? null;
}

// ---- COVER
const currentCoverUrl = props.vehicle.cover_image;
const coverPreview = ref(null);

function onCoverChange(e) {
    const file = e.target.files?.[0];
    form.cover_image = file || null;
    coverPreview.value = file ? URL.createObjectURL(file) : null;
}

// ---- GALLERY (existente + noi, max 5 total)
const existingGallery = ref(
    Array.isArray(props.vehicle.gallery_images)
        ? [...props.vehicle.gallery_images]
        : []
);
const removedExistingGallery = ref(new Set());

function toggleRemoveExisting(path) {
    if (removedExistingGallery.value.has(path)) {
        removedExistingGallery.value.delete(path);
    } else {
        removedExistingGallery.value.add(path);
    }
}
const remainingExistingGalleryCount = computed(
    () =>
        existingGallery.value.filter(
            (p) => !removedExistingGallery.value.has(p)
        ).length
);

// noi
const newGalleryItems = ref([]);
function onGalleryChange(e) {
    const files = Array.from(e.target.files || []);
    const remainingSlots = Math.max(
        0,
        GALLERY_MAX -
            remainingExistingGalleryCount.value -
            newGalleryItems.value.length
    );
    const toAdd = files.slice(0, remainingSlots);

    const unique = toAdd.filter(
        (f) =>
            !newGalleryItems.value.some(
                (g) =>
                    g.file.name === f.name &&
                    g.file.size === f.size &&
                    g.file.lastModified === f.lastModified
            )
    );

    const items = unique.map((f, i) => ({
        id: `${f.name}-${f.lastModified}-${i}-${Math.random()
            .toString(36)
            .slice(2)}`,
        file: f,
        url: URL.createObjectURL(f),
        name: f.name,
    }));

    newGalleryItems.value.push(...items);
    form.new_images_to_save = newGalleryItems.value.map((x) => x.file);
    e.target.value = "";
}
function removeNewGalleryItem(id) {
    const idx = newGalleryItems.value.findIndex((x) => x.id === id);
    if (idx !== -1) {
        URL.revokeObjectURL(newGalleryItems.value[idx].url);
        newGalleryItems.value.splice(idx, 1);
        form.new_images_to_save = newGalleryItems.value.map((x) => x.file);
    }
}

onBeforeUnmount(() => {
    newGalleryItems.value.forEach((i) => URL.revokeObjectURL(i.url));
});

// ---- Misc
function onPlateInput(e) {
    form.license_plate = e.target.value
        .replace(/[^A-Z0-9 \-]/gi, "")
        .toUpperCase();
}

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

// ---- SUBMIT
function submit() {
    form.images_to_remove = Array.from(removedExistingGallery.value);
    form.transform((data) => ({ ...data, _method: "PUT" })).post(
        route("user.cars.update", props.vehicle.slug),
        {
            forceFormData: true,
        }
    );
}
</script>
