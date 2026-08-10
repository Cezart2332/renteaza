<script setup>
/**
 * Cautare de adrese prin Mapbox Geocoding, inlocuind Google Places Autocomplete.
 *
 * Contractul de emit e neschimbat, ca sa nu fie nevoie de modificari in
 * Owner/Cars/Create.vue: 'selected' primeste acelasi obiect cu
 * location / locality / administrative_area_level_1 / postal_code / country /
 * place_id / lat / lng, plus 'description' (textul complet al adresei).
 */
import { onBeforeUnmount, reactive, ref, watch } from "vue";

const props = defineProps({
    token: { type: String, default: import.meta.env.VITE_MAPBOX_TOKEN || "" },
    // 'ro' sau ['ro','bg'] — acelasi format ca inainte
    restrictCountry: { type: [String, Array], default: undefined },
    label: { type: String, default: "Caută și selectează adresa" },
});

const emit = defineEmits(["selected"]);

const query = ref("");
const results = ref([]);
const open = ref(false);
const loading = ref(false);
const errorMsg = ref("");
const activeIndex = ref(-1);

const form = reactive({
    location: "",
    apt: "",
    locality: "",
    administrative_area_level_1: "",
    postal_code: "",
    country: "",
    place_id: "",
    description: "",
    lat: null,
    lng: null,
});

let debounceTimer = null;
let controller = null;

function countryParam() {
    if (!props.restrictCountry) return "";
    const list = Array.isArray(props.restrictCountry)
        ? props.restrictCountry
        : [props.restrictCountry];
    return `&country=${list.join(",")}`;
}

async function search(text) {
    if (!props.token) {
        errorMsg.value = "Lipsește VITE_MAPBOX_TOKEN.";
        return;
    }
    if (text.trim().length < 3) {
        results.value = [];
        open.value = false;
        return;
    }

    controller?.abort();
    controller = new AbortController();
    loading.value = true;
    errorMsg.value = "";

    try {
        const url =
            `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(text)}.json` +
            `?access_token=${props.token}` +
            `&types=address,poi,place&limit=6&language=ro${countryParam()}`;

        const res = await fetch(url, { signal: controller.signal });
        if (!res.ok) throw new Error(`Mapbox ${res.status}`);

        const data = await res.json();
        results.value = data.features ?? [];
        open.value = results.value.length > 0;
        activeIndex.value = -1;
    } catch (e) {
        if (e.name !== "AbortError") {
            errorMsg.value = "Căutarea adresei a eșuat.";
            results.value = [];
            open.value = false;
        }
    } finally {
        loading.value = false;
    }
}

watch(query, (val) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => search(val), 300);
});

/** Mapbox intoarce ierarhia in feature.context, cu id-uri prefixate. */
function contextValue(feature, prefix) {
    const hit = (feature.context ?? []).find((c) =>
        String(c.id).startsWith(`${prefix}.`)
    );
    return hit?.text ?? "";
}

function choose(feature) {
    const [lng, lat] = feature.center ?? [null, null];

    // adresa pe o linie: strada + numar (Mapbox pune numarul in address)
    const street = feature.text ?? "";
    const number = feature.address ?? "";
    form.location = number ? `${street} ${number}` : street;

    form.locality =
        contextValue(feature, "place") || contextValue(feature, "locality");
    form.administrative_area_level_1 = contextValue(feature, "region");
    form.postal_code = contextValue(feature, "postcode");
    form.country = contextValue(feature, "country");
    form.place_id = feature.id ?? "";
    form.description = feature.place_name ?? "";
    form.lat = lat;
    form.lng = lng;

    query.value = feature.place_name ?? "";
    open.value = false;
    results.value = [];

    emit("selected", { ...form });
}

function onKeydown(e) {
    if (!open.value || !results.value.length) return;
    if (e.key === "ArrowDown") {
        e.preventDefault();
        activeIndex.value = (activeIndex.value + 1) % results.value.length;
    } else if (e.key === "ArrowUp") {
        e.preventDefault();
        activeIndex.value =
            (activeIndex.value - 1 + results.value.length) % results.value.length;
    } else if (e.key === "Enter" && activeIndex.value >= 0) {
        e.preventDefault();
        choose(results.value[activeIndex.value]);
    } else if (e.key === "Escape") {
        open.value = false;
    }
}

onBeforeUnmount(() => {
    clearTimeout(debounceTimer);
    controller?.abort();
});
</script>

<template>
    <div class="tw-relative tw-w-full">
        <label class="tw-sr-only">{{ label }}</label>

        <input
            v-model="query"
            type="text"
            autocomplete="off"
            placeholder="Ex: Calea Victoriei 12, București"
            class="tw-block tw-w-full tw-rounded-md tw-border-gray-300 tw-text-black focus:tw-border-[var(--theme2)] focus:tw-ring-[var(--theme2)]"
            @keydown="onKeydown"
            @focus="open = results.length > 0"
        />

        <span
            v-if="loading"
            class="tw-absolute tw-right-3 tw-top-2.5 tw-text-xs tw-text-gray-400"
        >
            caut…
        </span>

        <ul
            v-if="open"
            class="tw-absolute tw-z-20 tw-mt-1 tw-max-h-64 tw-w-full tw-overflow-auto tw-rounded-lg tw-border tw-border-gray-200 tw-bg-white tw-py-1 tw-shadow-lg"
        >
            <li v-for="(f, idx) in results" :key="f.id">
                <button
                    type="button"
                    :class="[
                        'tw-block tw-w-full tw-px-3 tw-py-2 tw-text-left tw-text-sm',
                        idx === activeIndex
                            ? 'tw-bg-gray-100 tw-text-gray-900'
                            : 'tw-text-gray-700 hover:tw-bg-gray-50',
                    ]"
                    @click="choose(f)"
                >
                    {{ f.place_name }}
                </button>
            </li>
        </ul>

        <p v-if="errorMsg" class="tw-mt-1 tw-text-xs tw-text-[var(--theme)]">
            {{ errorMsg }}
        </p>
    </div>
</template>
