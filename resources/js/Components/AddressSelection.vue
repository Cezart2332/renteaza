<script setup>
import { onMounted, reactive, ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'

/**
 * AddressSelection.vue
 * — Vue 3 SFC that replicates the Google Console demo using Places Autocomplete
 * — Tailwind classes use the `tw-` prefix (configure Tailwind accordingly)
 * — Emits full address data on selection; also exposes a simple submit example
 */

// PROPS
const props = defineProps({
    apiKey: { type: String, default: import.meta.env.VITE_GOOGLE_MAPS_API_KEY || '' },
    restrictCountry: { type: [String, Array], default: undefined }, // 'ro' or ['ro','bg']
    submitTo: { type: String, default: '' }, // optional Inertia route name
    method: { type: String, default: 'post' }, // 'get' | 'post' | 'put' | 'patch'
})

// EMITS
const emit = defineEmits(['selected'])

// STATE
const form = reactive({
    location: '', // Address line: street_number + route
    apt: '',
    locality: '',
    administrative_area_level_1: '',
    postal_code: '',
    country: '',
    // Helpful extras
    place_id: '',
    lat: null,
    lng: null,
})

// DOM refs
const locationInputRef = ref(null)

// --- Loader for Google Maps JS API (places) ---
let gmapsPromise = null
function loadGoogleMaps(apiKey) {
    if (typeof window !== 'undefined' && window.google?.maps?.places) {
        return Promise.resolve()
    }
    if (gmapsPromise) return gmapsPromise
    gmapsPromise = new Promise((resolve, reject) => {
        const script = document.createElement('script')
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places`;
        script.async = true
        script.defer = true
        script.onload = () => resolve()
        script.onerror = (e) => reject(e)
        document.head.appendChild(script)
    })
    return gmapsPromise
}

// Helpers for filling the form from a Place result
const SHORT_NAME = new Set(['street_number', 'administrative_area_level_1', 'postal_code'])
function getComponent(place, type) {
    const comp = (place.address_components || []).find(c => c.types?.[0] === type)
    if (!comp) return ''
    return SHORT_NAME.has(type) ? (comp.short_name || '') : (comp.long_name || '')
}

function fillInAddress(place) {
    // Build "location" (street address)
    const streetNumber = getComponent(place, 'street_number')
    const route = getComponent(place, 'route')
    form.location = [streetNumber, route].filter(Boolean).join(' ')

    form.locality = getComponent(place, 'locality')
    form.administrative_area_level_1 = getComponent(place, 'administrative_area_level_1')
    form.postal_code = getComponent(place, 'postal_code')
    form.country = getComponent(place, 'country')

    form.place_id = place.place_id || ''
    const loc = place.geometry?.location
    if (loc) {
        form.lat = typeof loc.lat === 'function' ? loc.lat() : loc.lat
        form.lng = typeof loc.lng === 'function' ? loc.lng() : loc.lng
    }
}

// Init Places Autocomplete on the Address input
let autocomplete = null

onMounted(async () => {
    if (!props.apiKey) {
        console.warn('[AddressSelection] No Google Maps API key provided')
        return
    }
    await loadGoogleMaps(props.apiKey)

    const inputEl = locationInputRef.value
    if (!inputEl) return

    const options = {
        fields: ['address_components', 'geometry', 'name', 'place_id'],
        types: ['address'],
    }
    // Restrict by country if provided
    if (props.restrictCountry) {
        options.componentRestrictions = { country: props.restrictCountry }
    }

    autocomplete = new google.maps.places.Autocomplete(inputEl, options)

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace()
        if (!place || !place.geometry) {
            alert(`No details available for: '${place?.name || form.location}'`)
            return
        }
        fillInAddress(place)
        emit('selected', { ...form })
    })
})

// Simple Inertia submit example (optional)
function submit() {
    if (!props.submitTo) return
    const data = { ...form }
    const m = props.method.toLowerCase()
    if (m === 'get') {
        router.get(route(props.submitTo), data, { preserveState: true })
    } else if (m === 'post') {
        router.post(route(props.submitTo), data)
    } else if (m === 'put') {
        router.put(route(props.submitTo), data)
    } else if (m === 'patch') {
        router.patch(route(props.submitTo), data)
    } else {
        router.post(route(props.submitTo), data)
    }
}
</script>

<template>
    <div class="tw-w-full tw-flex tw-justify-center tw-items-start">
        <div
            class="tw-w-[300px] tw-h-[500px] tw-bg-white tw-rounded-2xl tw-shadow-sm tw-border tw-border-gray-200 tw-p-5 tw-flex tw-flex-col tw-justify-between">
            <!-- Title -->
            <div>
                <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                    <!-- simple icon -->
                    <svg class="tw-w-5 tw-h-5 tw-text-gray-700" viewBox="0 0 24 24" fill="currentColor"
                        aria-hidden="true">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5S13.38 11.5 12 11.5z" />
                    </svg>
                    <span class="tw-font-medium tw-text-gray-800">Address Selection</span>
                </div>
            </div>

            <!-- Inputs -->
            <div class="tw-space-y-4">
                <!-- Address line (autocomplete target) -->
                <input ref="locationInputRef" v-model="form.location" type="text" placeholder="Address"
                    class="tw-w-full tw-bg-transparent tw-border-0 tw-border-b tw-border-gray-800 tw-text-sm tw-pb-1 focus:tw-outline-none focus:tw-ring-0 focus:tw-border-black" />

                <!-- Apt/Suite -->
                <input v-model="form.apt" type="text" placeholder="Apt, Suite, etc (optional)"
                    class="tw-w-full tw-bg-transparent tw-border-0 tw-border-b tw-border-gray-800 tw-text-sm tw-pb-1 focus:tw-outline-none focus:tw-ring-0 focus:tw-border-black" />

                <!-- City -->
                <input v-model="form.locality" type="text" placeholder="City"
                    class="tw-w-full tw-bg-transparent tw-border-0 tw-border-b tw-border-gray-800 tw-text-sm tw-pb-1 focus:tw-outline-none focus:tw-ring-0 focus:tw-border-black" />

                <!-- State + Postal code -->
                <div class="tw-flex tw-gap-4">
                    <input v-model="form.administrative_area_level_1" type="text" placeholder="State/Province"
                        class="tw-w-1/2 tw-bg-transparent tw-border-0 tw-border-b tw-border-gray-800 tw-text-sm tw-pb-1 focus:tw-outline-none focus:tw-ring-0 focus:tw-border-black" />

                    <input v-model="form.postal_code" type="text" placeholder="Zip/Postal code"
                        class="tw-w-1/2 tw-bg-transparent tw-border-0 tw-border-b tw-border-gray-800 tw-text-sm tw-pb-1 focus:tw-outline-none focus:tw-ring-0 focus:tw-border-black" />
                </div>

                <!-- Country -->
                <input v-model="form.country" type="text" placeholder="Country"
                    class="tw-w-full tw-bg-transparent tw-border-0 tw-border-b tw-border-gray-800 tw-text-sm tw-pb-1 focus:tw-outline-none focus:tw-ring-0 focus:tw-border-black" />
            </div>

            <!-- CTA row -->
            <div class="tw-pt-2">
                <button type="button"
                    class="tw-inline-flex tw-items-center tw-justify-center tw-w-full tw-rounded-xl tw-bg-[#0D5DB8] tw-text-white tw-text-sm tw-font-semibold tw-px-4 tw-py-2.5 hover:tw-bg-[#0b53a4] focus:tw-ring-2 focus:tw-ring-offset-2 focus:tw-ring-[#0D5DB8]"
                    @click="submit">
                    Checkout
                </button>

                <!-- tiny debug / extras -->
                <div class="tw-mt-3 tw-text-[11px] tw-text-gray-500">
                    <div>place_id: <span class="tw-text-gray-700">{{ form.place_id || '—' }}</span></div>
                    <div>coords: <span class="tw-text-gray-700">{{ form.lat ?? '—' }}, {{ form.lng ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/***** Optional: mimic the original focus placeholder fade *****/
input:focus::placeholder {
    color: transparent;
}
</style>
