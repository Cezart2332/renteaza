<template>
    <div
        class="tw-min-h-screen tw-overflow-hidden tw-bg-[#0c0f14] tw-text-white tw-flex tw-flex-col"
    >
        <!-- Top bar -->
        <div class="tw-flex tw-items-center tw-justify-between tw-px-5 tw-pt-5">
            <!-- dacă avem range -->
            <template v-if="rangeLabel">
                <div class="tw-flex tw-flex-col">
                    <div class="tw-text-sm tw-text-white/70">
                        Perioada selectată:
                    </div>
                    <div class="tw-text-base tw-font-semibold tw-text-white">
                        {{ rangeLabel }}
                    </div>
                    <div
                        v-if="min_price !== max_price"
                        class="tw-text-xs tw-text-white/60"
                    >
                        Interval prețuri: {{ min_price }} – {{ max_price }}
                        {{ currency }}
                    </div>
                    <div v-else class="tw-text-xs tw-text-white/60">
                        Preț pe zi: {{ min_price }}
                        {{ currency }}
                    </div>
                </div>
            </template>

            <!-- dacă e doar o zi -->
            <template v-else-if="dateLabel">
                <div class="tw-flex tw-flex-col">
                    <div class="tw-text-sm tw-text-white/70">
                        Ziua selectată:
                    </div>
                    <div class="tw-text-base tw-font-semibold tw-text-white">
                        {{ dateLabel }}
                    </div>
                </div>
            </template>
        </div>

        <!-- Big price -->
        <div
            class="tw-flex-1 tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-8"
        >
            <div class="tw-flex tw-items-baseline tw-gap-4">
                <input
                    type="text"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    :value="display"
                    @input="onInput"
                    @wheel.prevent
                    @keydown.up.prevent="step(1)"
                    @keydown.down.prevent="step(-1)"
                    :disabled="!smartLocal"
                    class="tw-w-[14ch] tw-text-7xl tw-font-extrabold tw-font-mono tw-tracking-tight tw-bg-transparent tw-text-center tw-outline-none tw-border-b-2 tw-border-white/20 focus:tw-border-emerald-400 focus:tw-shadow-[0_10px_28px_-12px_rgba(16,185,129,0.55)] tw-transition disabled:tw-opacity-50 disabled:tw-cursor-not-allowed"
                />
            </div>
            <span class="tw-text-lg tw-font-medium tw-text-white/70">
                Monedă: RON
            </span>

            <!-- Smart pricing toggle -->
            <div
                class="tw-flex tw-flex-col tw-items-center tw-gap-3 tw-text-white/80"
            >
                <div class="tw-flex tw-flex-row tw-gap-4">
                    <span>Tarifarea inteligentă a fost activată</span>

                    <label
                        class="tw-inline-flex tw-items-center tw-cursor-pointer"
                    >
                        <input
                            type="checkbox"
                            v-model="smartLocal"
                            class="tw-sr-only"
                        />
                        <span
                            class="tw-relative tw-inline-flex tw-h-7 tw-w-12 tw-rounded-full tw-transition-colors tw-duration-200"
                            :class="
                                smartLocal
                                    ? 'tw-bg-emerald-500'
                                    : 'tw-bg-gray-600'
                            "
                        >
                            <!-- knob -->
                            <span
                                class="tw-pointer-events-none tw-absolute tw-inset-y-0 tw-left-0.5 tw-my-auto tw-h-6 tw-w-6 tw-rounded-full tw-bg-white tw-shadow tw-transition-transform tw-duration-200 tw-transform"
                                :class="
                                    smartLocal
                                        ? 'tw-translate-x-5'
                                        : 'tw-translate-x-0'
                                "
                            />
                        </span>
                    </label>
                </div>
                <div
                    v-if="!smartLocal"
                    class="tw-text-sm tw-text-yellow-400 tw-bg-yellow-500/10 tw-border tw-border-yellow-500/30 tw-rounded-md tw-px-12 tw-py-2 tw-mt-4"
                >
                    ⚠ Dacă dezactivezi tarifarea inteligentă, se va aplica
                    <b>prețul de bază</b> setat la vehicul.
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="tw-px-5 tw-pb-6 tw-space-y-2">
            <div class="tw-flex tw-gap-3">
                <!-- Buton Anulează -->
                <inertia-link
                    class="tw-flex-1 tw-flex tw-flex-row tw-justify-center tw-rounded-xl tw-bg-gray-700 tw-text-white tw-font-semibold tw-py-3 hover:tw-bg-gray-600 tw-transition"
                    :href="
                        route('user.calendar.show', {
                            vehicleSlug: vehicleSlug,
                        })
                    "
                >
                    Anulează
                </inertia-link>

                <!-- Buton Salvează -->
                <button
                    class="tw-flex-1 tw-rounded-xl tw-bg-white tw-text-gray-900 tw-font-semibold tw-py-3 hover:tw-bg-gray-100 tw-transition"
                    @click="save"
                >
                    Salvează
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, nextTick } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    dateLabel: String,
    rangeLabel: String,
    start: { type: String, required: true },
    end: { type: String, default: null },
    price: { type: Number, default: 0 },
    min_price: { type: Number, default: 0 },
    max_price: { type: Number, default: 0 },
    currency: { type: String, default: "RON" },
    smart: { type: Boolean, default: true },
    vehicleSlug: { type: String, required: true },
});

let localPrice = 0;

if (props.price > 0) {
    localPrice = props.price;
} else if (props.min_price === props.max_price) {
    localPrice = props.min_price;
}

const priceLocal = ref(localPrice);
const smartLocal = ref(props.smart);

const nf = new Intl.NumberFormat("ro-RO", {
    useGrouping: true,
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
});

const display = computed(() => nf.format(Math.trunc(+priceLocal.value || 0)));

function onInput(e) {
    const raw = e.target.value.replace(/[^\d]/g, "");
    priceLocal.value = raw === "" ? 0 : parseInt(raw, 10);
    nextTick(() => {
        const len = e.target.value.length;
        e.target.setSelectionRange(len, len);
    });
}

function step(d) {
    priceLocal.value = Math.max(0, (priceLocal.value || 0) + d);
}

function save() {
    const payload = {
        start: props.start,
        end: props.end ?? props.start, // dacă nu ai end, salvez doar ziua
        smart: smartLocal.value ? 1 : 0,
        // Dacă smart e ON, trimitem și custom_price; dacă e OFF, îl anulăm
        custom_price: smartLocal.value ? Number(priceLocal.value || 0) : null,
    };

    router.post(
        route("user.calendar.prices.set", { vehicleSlug: props.vehicleSlug }),
        payload,
        { preserveScroll: true }
    );
}
</script>
