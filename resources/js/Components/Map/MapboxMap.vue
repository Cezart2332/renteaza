<script setup>
/**
 * Harta Mapbox, reutilizabila. Inlocuieste <GMapMap> / <GMapMarker> de la
 * @fawmi/vue-google-maps si iframe-urile cu google.com/maps?output=embed.
 *
 * Tokenul vine din VITE_MAPBOX_TOKEN si se "coace" in bundle la build.
 * Fara token, componenta afiseaza un mesaj in loc sa crape.
 */
import mapboxgl from "mapbox-gl";
import "mapbox-gl/dist/mapbox-gl.css";
import { onBeforeUnmount, onMounted, ref, shallowRef, watch } from "vue";

const props = defineProps({
    // { lat, lng }
    center: { type: Object, default: () => ({ lat: 44.4268, lng: 26.1025 }) }, // București
    zoom: { type: Number, default: 12 },
    // [{ id, position: { lat, lng }, label? }]
    markers: { type: Array, default: () => [] },
    // harta de previzualizare: fara zoom/drag
    interactive: { type: Boolean, default: true },
    styleUrl: { type: String, default: "mapbox://styles/mapbox/streets-v12" },
});

const emit = defineEmits(["marker-click"]);

const token = import.meta.env.VITE_MAPBOX_TOKEN || "";
const container = ref(null);
const map = shallowRef(null);
const markerObjects = shallowRef([]);

function clearMarkers() {
    markerObjects.value.forEach((m) => m.remove());
    markerObjects.value = [];
}

function drawMarkers() {
    if (!map.value) return;
    clearMarkers();

    const created = props.markers
        .filter((m) => m?.position?.lat != null && m?.position?.lng != null)
        .map((m) => {
            const el = document.createElement("button");
            el.type = "button";
            el.setAttribute("aria-label", m.label ?? "Locație");
            el.style.cssText = [
                "width:26px",
                "height:26px",
                "border-radius:9999px",
                "border:3px solid #fff",
                "background:var(--theme, #ff3726)",
                "box-shadow:0 2px 8px rgba(0,0,0,.35)",
                "cursor:pointer",
                "padding:0",
            ].join(";");

            el.addEventListener("click", (e) => {
                e.stopPropagation();
                emit("marker-click", m);
            });

            const marker = new mapboxgl.Marker({ element: el })
                .setLngLat([m.position.lng, m.position.lat])
                .addTo(map.value);

            if (m.label) {
                marker.setPopup(
                    new mapboxgl.Popup({
                        offset: 18,
                        closeButton: false,
                    }).setText(m.label)
                );
            }

            return marker;
        });

    markerObjects.value = created;
}

onMounted(() => {
    if (!token || !container.value) return;

    mapboxgl.accessToken = token;

    map.value = new mapboxgl.Map({
        container: container.value,
        style: props.styleUrl,
        center: [props.center.lng, props.center.lat],
        zoom: props.zoom,
        interactive: props.interactive,
        attributionControl: true,
    });

    if (props.interactive) {
        map.value.addControl(new mapboxgl.NavigationControl(), "top-right");
    }

    map.value.on("load", drawMarkers);
});

watch(
    () => props.markers,
    () => {
        if (map.value?.isStyleLoaded()) drawMarkers();
    },
    { deep: true }
);

watch(
    () => props.center,
    (c) => {
        if (map.value && c?.lat != null && c?.lng != null) {
            map.value.easeTo({ center: [c.lng, c.lat], duration: 600 });
        }
    },
    { deep: true }
);

onBeforeUnmount(() => {
    clearMarkers();
    map.value?.remove();
    map.value = null;
});
</script>

<template>
    <!-- Fara tw-h-full pe radacina: inaltimea o da parintele prin class="...".
         Cu ambele, cele doua reguli de height au aceeasi specificitate si
         castiga ordinea din CSS, adica un rezultat imprevizibil. -->
    <div class="tw-relative tw-w-full tw-overflow-hidden">
        <div v-if="token" ref="container" class="tw-h-full tw-w-full"></div>

        <div
            v-else
            class="tw-flex tw-h-full tw-w-full tw-items-center tw-justify-center tw-bg-gray-100 tw-p-6 tw-text-center"
        >
            <p class="tw-text-sm tw-text-gray-500">
                Harta nu este disponibilă: lipsește
                <code class="tw-font-mono">VITE_MAPBOX_TOKEN</code>.
            </p>
        </div>
    </div>
</template>
