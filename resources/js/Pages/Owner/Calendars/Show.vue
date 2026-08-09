<template>
    <OwnerDashboardLayout>
        <h1
            class="tw-flex tw-items-center tw-gap-3 tw-text-lg tw-font-bold tw-text-gray-900 tw-mb-6"
        >
            <span
                class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-blue-100 tw-text-blue-600"
            >
                📅
            </span>
            Administrarea rezervărilor și tarifelor
        </h1>

        <p class="tw-text-gray-500 tw-mb-8 tw-ml-14">
            Vizualizează calendarul zilnic, gestionează rezervările și ajustează
            prețurile dinamice
        </p>

        <div class="tw-flex tw-flex-col md:tw-flex-row tw-m-2 tw-gap-24">
            <div>
                <Flatpickr
                    v-model="internalRange"
                    :config="pickerConfig"
                    :key="JSON.stringify(disabledRanges)"
                    class="tw-w-full tw-mb-8 tw-block tw-mt-2 tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2.5 tw-px-3 tw-text-black focus:tw-border-blue-500 focus:tw-ring-2 focus:tw-ring-blue-500"
                />
                <!-- legenda calendar -->
                <div class="tw-flex tw-flex-col tw-items-center tw-gap-2">
                    <div
                        class="calendar-legend tw-mt-4 tw-flex tw-flex-wrap tw-gap-4 tw-justify-center tw-text-sm"
                    >
                        <div
                            class="legend-item tw-flex tw-items-center tw-gap-2"
                        >
                            <span
                                class="legend-box tw-bg-white tw-border tw-border-gray-500 tw-border-solid"
                            ></span>
                            Rezervabilă
                        </div>

                        <div
                            class="legend-item tw-flex tw-items-center tw-gap-2"
                        >
                            <span
                                class="legend-box tw-bg-red-600 tw-border tw-border-red-700"
                            ></span>
                            Rezervată
                        </div>

                        <div
                            class="legend-item tw-flex tw-items-center tw-gap-2"
                        >
                            <span
                                class="legend-box tw-bg-white tw-border tw-border-gray-500 tw-border-solid tw-relative"
                            >
                                <span class="legend-line"></span>
                            </span>
                            Închisă
                        </div>
                    </div>
                    <div class="legend-item tw-flex tw-items-center tw-gap-2">
                        <span
                            class="tw-flex tw-items-center tw-justify-center tw-w-5 tw-h-5 tw-rounded-full tw-bg-yellow-100 tw-text-yellow-600 tw-text-sm"
                        >
                            ★
                        </span>
                        <span class="tw-text-sm tw-text-gray-700"
                            >Prima zi a unei rezervări</span
                        >
                    </div>
                </div>
            </div>
            <div>
                <!-- Pus imediat sub calendar -->
                <div
                    v-if="
                        !dayReservations.length &&
                        !mixedBookedState &&
                        panelOpen
                    "
                    class="tw-mt-4"
                >
                    <div
                        class="tw-mx-auto tw-w-full tw-max-w-5xl tw-rounded-2xl tw-border tw-border-gray-200 tw-bg-[#F2F4F5] tw-overflow-hidden"
                    >
                        <!-- header mic cu perioada + close -->
                        <div
                            class="tw-flex tw-items-center tw-justify-between tw-px-5 tw-pt-4"
                        >
                            <div
                                class="tw-inline-flex tw-items-center tw-gap-2"
                            >
                                <span
                                    class="tw-text-xs tw-font-medium tw-text-gray-500"
                                    >Perioadă</span
                                >
                                <span
                                    class="tw-inline-flex tw-items-center tw-justify-center tw-rounded-full tw-bg-black tw-text-white tw-text-xs tw-px-3 tw-py-1"
                                >
                                    {{ rangeLabel }}
                                </span>
                            </div>
                            <button
                                class="tw-h-9 tw-w-9 tw-inline-flex tw-items-center tw-justify-center tw-rounded-full hover:tw-bg-gray-100"
                                @click="closePanel"
                                aria-label="Închide secțiunea"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- CONȚINUT: coloană pe mobil, rând pe md+ -->
                        <div
                            class="tw-flex tw-flex-row md:tw-flex-col tw-gap-4 md:tw-gap-3 lg:tw-gap-4 tw-m-4"
                        >
                            <!-- STÂNGA: Disponibilitate -->
                            <div
                                class="tw-flex-1 tw-rounded-2xl tw-bg-gray-900 tw-text-white tw-p-4 tw-shadow-lg tw-border tw-border-white/10 md:tw-p-6 lg:tw-p-10 <!-- padding MAI MARE doar desktop --> lg:tw-rounded-3xl <!-- colțuri mai rotunde doar lg --> md:tw-min-w-[380px] lg:tw-min-w-[460px] xl:tw-min-w-[520px]"
                            >
                                <div
                                    class="tw-text-sm tw-font-semibold tw-mb-4"
                                >
                                    Disponibilitate
                                </div>
                                <div
                                    class="tw-mb-3 tw-text-sm tw-text-white/80"
                                >
                                    {{ availabilityLabel }}
                                </div>

                                <!-- Segmented control -->
                                <div
                                    class="tw-flex tw-bg-white/10 tw-rounded-full tw-p-1 tw-gap-1 tw-shadow-inner"
                                    role="tablist"
                                    aria-label="Disponibilitate"
                                >
                                    <button
                                        type="button"
                                        role="tab"
                                        :aria-selected="availability === false"
                                        @click="setAvailability(false)"
                                        class="tw-flex-1 tw-h-12 tw-rounded-full tw-font-medium tw-transition tw-flex tw-items-center tw-justify-center"
                                        :class="[
                                            availability === false
                                                ? 'tw-bg-red-600 tw-text-white tw-shadow'
                                                : availability === null
                                                ? 'tw-text-white/90 tw-border tw-border-red-400 hover:tw-bg-red-500 hover:tw-text-white'
                                                : 'tw-text-white/75 hover:tw-bg-red-500 hover:tw-text-white',
                                        ]"
                                    >
                                        ✕
                                    </button>

                                    <button
                                        type="button"
                                        role="tab"
                                        :aria-selected="availability === true"
                                        @click="setAvailability(true)"
                                        class="tw-flex-1 tw-h-12 tw-rounded-full tw-font-medium tw-transition tw-flex tw-items-center tw-justify-center"
                                        :class="[
                                            availability === true
                                                ? 'tw-bg-green-600 tw-text-white tw-shadow'
                                                : availability === null
                                                ? 'tw-text-white/90 tw-border tw-border-green-400 hover:tw-bg-green-500 hover:tw-text-white'
                                                : 'tw-text-white/75 hover:tw-bg-green-500 hover:tw-text-white',
                                        ]"
                                    >
                                        ✓
                                    </button>
                                </div>
                            </div>

                            <!-- DREAPTA: Preț -->
                            <inertia-link
                                :href="
                                    route('user.calendar.prices.show', {
                                        vehicleSlug: vehicle.slug,
                                        start: selStart
                                            ? ymdLocal(selStart)
                                            : undefined,
                                        end: selEnd
                                            ? ymdLocal(selEnd)
                                            : ymdLocal(selStart),
                                    })
                                "
                                class="tw-flex-[1] tw-rounded-2xl tw-bg-gray-900 tw-text-white tw-p-4 tw-space-y-4"
                                @click="openPriceModal"
                            >
                                <div class="tw-text-sm tw-font-semibold">
                                    Tarifare inteligentă
                                </div>
                                <div class="tw-text-xl tw-font-extrabold">
                                    {{ smartSummary }} {{ currency }}
                                </div>
                            </inertia-link>
                        </div>
                    </div>
                </div>

                <div
                    v-if="dayReservations.length && !mixedBookedState"
                    class="tw-mx-4 tw-mt-2 tw-space-y-3"
                >
                    <div
                        v-for="r in dayReservations"
                        :key="r.id"
                        class="tw-bg-gray-900 tw-text-white tw-rounded-2xl tw-p-4 tw-space-y-3"
                    >
                        <!-- Badge status -->
                        <div class="tw-flex tw-justify-between tw-items-center">
                            <span
                                class="tw-inline-flex tw-items-center tw-rounded-full tw-bg-green-100 tw-text-green-700 tw-text-xs tw-font-medium tw-px-2 tw-py-1"
                            >
                                ✔ Accepted
                            </span>
                        </div>

                        <!-- Nume oaspete -->
                        <div class="tw-font-semibold tw-text-lg">
                            {{ r.guest_name }}
                        </div>

                        <!-- Info suplimentare (nopți, persoane) -->
                        <div class="tw-text-sm tw-text-gray-300">
                            {{ r.days }} zile
                        </div>

                        <!-- Check-in / Check-out -->
                        <div
                            class="tw-grid tw-grid-cols-2 tw-gap-4 tw-text-sm tw-mt-2"
                        >
                            <div>
                                <div class="tw-text-gray-400">Check-in</div>
                                <div class="tw-font-medium">
                                    {{ formatDateTime(r.start_at) }}
                                </div>
                            </div>
                            <div>
                                <div class="tw-text-gray-400">Check-out</div>
                                <div class="tw-font-medium">
                                    {{ formatDateTime(r.end_at) }}
                                </div>
                            </div>
                        </div>

                        <!-- Link detalii rezervare -->
                        <Link
                            :href="
                                route('user.calendar.bookings.show', {
                                    vehicleSlug: vehicle.slug,
                                    bookingId: r.id,
                                })
                            "
                            class="tw-text-blue-400 hover:tw-underline tw-text-sm tw-block tw-mt-3"
                            preserve-scroll
                        >
                            Vezi detaliile rezervării →
                        </Link>
                    </div>
                </div>

                <div
                    v-if="mixedBookedState"
                    class="tw-bg-red-100 tw-text-red-700 tw-rounded-lg tw-px-4 tw-py-2 tw-m-4 tw-font-semibold"
                >
                    ⚠️ Operațiile sunt interzise deoarece intervalul selectat
                    conține zile rezervate.
                </div>
            </div>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted, nextTick } from "vue";
import Flatpickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { Link, router, useRemember } from "@inertiajs/vue3";

const props = defineProps({
    disabledRanges: { type: Array, default: () => [] },
    bookedRanges: { type: Array, default: () => [] },
    modelValue: {
        type: Object,
        default: () => ({ pickupDate: "", dropoffDate: "" }),
    },
    vehicle: {
        type: Object,
    },
    priceByDate: { type: Object, default: () => ({}) },
    blockedDates: { type: Array, default: () => [] },
    reservations: {
        type: Array,
        default: () => [],
    },
});
const selectedDay = ref(null);

const localBlockedDates = ref([...props.blockedDates]);
const blockedSet = computed(() => new Set(localBlockedDates.value));

const emit = defineEmits(["update:modelValue"]);

// Zilele de început ale intervalelor rezervate, ca YYYY-MM-DD
const bookedStartSet = computed(() => {
    return new Set(
        (props.bookedRanges || []).map((r) => r?.from).filter(Boolean)
    );
});

function formatDateTime(isoStr) {
    const d = new Date(isoStr);
    return new Intl.DateTimeFormat("ro-RO", {
        weekday: "short",
        day: "2-digit",
        month: "short",
        hour: "2-digit",
        minute: "2-digit",
        timeZone: "Europe/Bucharest",
    }).format(d);
}

const mixedBookedState = computed(() => {
    if (!selStart.value) return false;
    const days = selectedDaysYMD.value;
    if (!days.length) return false;

    let hasBooked = false;
    let hasFree = false;

    for (const d of days) {
        const isBooked = (props.bookedRanges || []).some(
            (r) => d >= r.from && d <= r.to
        );
        if (isBooked) hasBooked = true;
        else hasFree = true;
    }

    return hasBooked && hasFree; // doar dacă există și rezervabile și booked
});

/* Helpers */
function roundUpTo(step = 15) {
    const d = new Date();
    d.setSeconds(0, 0);
    const r = d.getMinutes() % step;
    if (r !== 0) d.setMinutes(d.getMinutes() + (step - r));
    return d;
}
function fmtHM(d) {
    const hh = String(d.getHours()).padStart(2, "0");
    const mm = String(d.getMinutes()).padStart(2, "0");
    return `${hh}:${mm}`;
}

//  un key stabil per vehicul, ca să nu se amestece calendarele
const remember = useRemember(
    {
        dates: [], // selecția (array de Date ISO strings)
        view: { year: null, month: null }, // luna/anul vizibil
    },
    `cal:${props.vehicle?.slug || "veh"}`
);

// în loc de internalRange separat, folosește remember.dates
const internalRange = ref((remember.value.dates || []).map((s) => new Date(s)));

/* Init: dacă nu vin valori din props, selectăm acum (rotunjit) + 15 min */
onMounted(async() => {
    if (internalRange.value.length) return;

     // dacă nu există selecție, setează azi ca selecție
  if (!internalRange.value?.length) {
    const today = new Date()
    internalRange.value = [today]
  }

  // așteaptă ca Flatpickr să-și ia defaultDate
  await nextTick()

  // pre-deschide panoul cu selecția curentă
  const dates = internalRange.value
  selStart.value = dates[0] || null
  selEnd.value   = dates[1] || null
  panelOpen.value = true


    if (!props.modelValue?.pickupDate) {
        const start = roundUpTo(15);
        const end = new Date(start);
        end.setMinutes(end.getMinutes() + 15);
        internalRange.value = [start, end];

        // emite imediat ca să apară în “Rezumat”
        emit("update:modelValue", {
            pickupDate: flatFmt(start),
            dropoffDate: flatFmt(end),
        });
    } else {
        // dacă vin presetate, sincronizează-le
        const s = new Date(props.modelValue.pickupDate.replace(" ", "T"));
        const e = props.modelValue.dropoffDate
            ? new Date(props.modelValue.dropoffDate.replace(" ", "T"))
            : null;
        internalRange.value = e ? [s, e] : [s];
    }
});

function flatFmt(d) {
    const Y = d.getFullYear();
    const M = String(d.getMonth() + 1).padStart(2, "0");
    const D = String(d.getDate()).padStart(2, "0");
    const h = String(d.getHours()).padStart(2, "0");
    const m = String(d.getMinutes()).padStart(2, "0");
    return `${Y}-${M}-${D} ${h}:${m}`;
}

/* Config */
const pickerConfig = computed(() => ({
    mode: "range",
    enableTime: false,
    time_24hr: true,
    minuteIncrement: 15,
    dateFormat: "Y-m-d H:i",
    inline: true,
    hideInput: true,
    clickOpens: false,
    allowInput: false,
    minDate: "today",
    // important: pornește cu valorile curente
    defaultDate: internalRange.value,

    onDayCreate: (_dObj, _dStr, fp, dayElem) => {
        const y = fp.formatDate(dayElem.dateObj, "Y-m-d");
        const isBooked = (props.bookedRanges || []).some(
            (r) => y >= r.from && y <= r.to
        );
        const isBlockedSingle = blockedSet.value.has(y);

        if (isBooked) dayElem.classList.add("is-booked-day");
        if (isBlockedSingle) dayElem.classList.add("is-blocked-day");

        // Steluță pe prima zi din intervalul rezervat
        if (bookedStartSet.value.has(y)) {
            const star = document.createElement("span");
            star.classList.add("fp-star");
            star.textContent = "★";
            star.title = "Început rezervare";
            dayElem.appendChild(star);
        }

        // UI (prețul zilnic)
        const dailyPrice = priceMap.value[y] ?? props.vehicle.price_per_day;
        const dayNum = dayElem.textContent.trim();

        const wrapper = document.createElement("div");
        wrapper.classList.add("fp-cell");

        const main = document.createElement("div");
        main.classList.add("fp-day");
        main.textContent = dayNum;

        const extraWrapper = document.createElement("div");
        extraWrapper.classList.add("fp-extra");

        const extra = document.createElement("span");
        extra.classList.add("fp-price");
        extra.textContent = formatPrice(dailyPrice);

        const currency = document.createElement("span");
        currency.classList.add("fp-currency");
        currency.textContent = " RON";

        extraWrapper.appendChild(extra);
        extraWrapper.appendChild(currency);
        wrapper.appendChild(main);
        wrapper.appendChild(extraWrapper);

        dayElem.innerHTML = "";
        dayElem.appendChild(wrapper);
    },

    onReady: (_sel, _str, fp) => {
        // sari la luna memorată
        const v = remember.value.view;
        if (v?.year != null && v?.month != null) {
            fp.jumpToDate(new Date(v.year, v.month, 1), true);
        }
        // setează minTime pentru azi când se montează
        const nowRounded = roundUpTo(15);
        const todayStr = fp.formatDate(new Date(), "Y-m-d");
        const startStr = internalRange.value[0]
            ? fp.formatDate(internalRange.value[0], "Y-m-d")
            : null;
        fp.set("minTime", startStr === todayStr ? fmtHM(nowRounded) : "00:00");
    },

    onOpen: (selectedDates, _str, fp) => {
        const nowRounded = roundUpTo(15);
        const todayStr = fp.formatDate(new Date(), "Y-m-d");
        const startStr = selectedDates[0]
            ? fp.formatDate(selectedDates[0], "Y-m-d")
            : null;
        fp.set("minTime", startStr === todayStr ? fmtHM(nowRounded) : "00:00");
    },

    onChange: (selectedDates, _dateStr, fp) => {
        const nowRounded = roundUpTo(15);
        const todayStr = fp.formatDate(new Date(), "Y-m-d");
        const startStr = selectedDates[0]
            ? fp.formatDate(selectedDates[0], "Y-m-d")
            : null;

        fp.set("minTime", startStr === todayStr ? fmtHM(nowRounded) : "00:00");

        if (selectedDates?.length) {
            selectedDay.value = fp.formatDate(selectedDates[0], "Y-m-d");
            openPanel(selectedDates);
        }

        // dacă aceeași zi și end < start -> end = start + 15 min
        if (selectedDates[0] && selectedDates[1]) {
            const s = selectedDates[0],
                e = selectedDates[1];
            if (
                fp.formatDate(s, "Y-m-d") === fp.formatDate(e, "Y-m-d") &&
                e < s
            ) {
                const fix = new Date(s);
                fix.setMinutes(fix.getMinutes() + 15);
                selectedDates[1] = fix;
                fp.setDate(selectedDates, true);
            }
        }

        emit("update:modelValue", {
            pickupDate: selectedDates[0]
                ? fp.formatDate(selectedDates[0], "Y-m-d H:i")
                : "",
            dropoffDate: selectedDates[1]
                ? fp.formatDate(selectedDates[1], "Y-m-d H:i")
                : "",
        });
        remember.value.dates = (fp.selectedDates || []).map((d) => ymdLocal(d));
    },
    onMonthChange: (_sel, _str, fp) => {
        remember.value.view = { year: fp.currentYear, month: fp.currentMonth };
    },
    onYearChange: (_sel, _str, fp) => {
        remember.value.view = { year: fp.currentYear, month: fp.currentMonth };
    },
    onClose: (_sel, _str, fp) => {
        remember.value.view = { year: fp.currentYear, month: fp.currentMonth };
    },
}));

watch(
    () => props.modelValue,
    (val) => {
        const next = [];
        if (val?.pickupDate)
            next[0] = new Date(val.pickupDate.replace(" ", "T"));
        if (val?.dropoffDate)
            next[1] = new Date(val.dropoffDate.replace(" ", "T"));
        internalRange.value = next;
    },
    { deep: true }
);

function formatPrice(value) {
    return parseFloat(value)
        .toFixed(2)
        .replace(/\.?0+$/, "");
}

//edit
const panelOpen = ref(false);
const selStart = ref(null); // Date
const selEnd = ref(null); // Date
const availability = ref(true);
const basePrice = computed(() => Number(props.vehicle?.price_per_day ?? 0));
const priceMap = computed(() => props.priceByDate || {});
const currency = computed(() => props.vehicle?.currency ?? "RON");

function ymd(d) {
    const z = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    const Y = z.getFullYear();
    const M = String(z.getMonth() + 1).padStart(2, "0");
    const D = String(z.getDate()).padStart(2, "0");
    return `${Y}-${M}-${D}`;
}

function daysBetweenInclusive(start, end) {
    const out = [];
    if (!start) return out;
    const s = new Date(start.getFullYear(), start.getMonth(), start.getDate());
    const e = end
        ? new Date(end.getFullYear(), end.getMonth(), end.getDate())
        : s;
    for (
        let d = s;
        d <= e;
        d = new Date(d.getFullYear(), d.getMonth(), d.getDate() + 1)
    ) {
        out.push(new Date(d));
    }
    return out;
}

const rangeLabel = computed(() => {
    const fmt = (d) =>
        d?.toLocaleDateString("ro-RO", {
            day: "2-digit",
            month: "short",
        });

    if (!selStart.value) return "—";

    // dacă există și start și end
    if (selEnd.value) {
        const sameDay =
            selStart.value.toDateString() === selEnd.value.toDateString();

        if (sameDay) {
            return `Ziua ${fmt(selStart.value)}`;
        } else {
            return `${fmt(selStart.value)} – ${fmt(selEnd.value)}`;
        }
    } else {
        return `Ziua ${fmt(selStart.value)}`;
    }
});

//pret

const smartSummary = computed(() => {
    if (!selStart.value) return { text: `— ${currency.value}` };

    const days = daysBetweenInclusive(selStart.value, selEnd.value);

    const prices = days.map((d) => {
        const k = ymd(d);
        return Number(priceMap.value[k] ?? basePrice.value);
    });

    // interval
    const min = Math.min(...prices);
    const max = Math.max(...prices);

    if (prices.length === 1 || min === max) {
        // o singură zi
        return formatPrice(prices[0]);
    }
    return `${formatPrice(min)}–${formatPrice(max)}`;
});

// --- helpers pentru selecție curentă
const selectedDaysYMD = computed(() => {
    if (!selStart.value) return [];
    const days = daysBetweenInclusive(selStart.value, selEnd.value);
    return days.map((d) => ymd(d));
});

// 'available' | 'blocked' | 'mixed' | 'none'
const availabilityState = computed(() => {
    if (!selStart.value) return "none";
    const days = selectedDaysYMD.value;
    if (!days.length) return "none";
    const blockedCount = days.filter((d) => blockedSet.value.has(d)).length;
    if (blockedCount === 0) return "available";
    if (blockedCount === days.length) return "blocked";
    return "mixed";
});

// label pentru UI
const availabilityLabel = computed(() => {
    if (availabilityState.value === "available") return "✅ Rezervabilă";
    if (availabilityState.value === "blocked") return "❌ Blocată";
    if (availabilityState.value === "mixed") return "🟡 Perioadă mixtă";
    return "—";
});

watch(availabilityState, (st) => {
    if (st === "available") availability.value = true;
    else if (st === "blocked") availability.value = false;
    else availability.value = null; // mixed
});

function openPanel(selectedDates) {
    selStart.value = selectedDates[0] || null;
    selEnd.value = selectedDates[1] || null;
    panelOpen.value = true;
    const st = availabilityState.value;
    availability.value =
        st === "available" ? true : st === "blocked" ? false : null;
}

function closePanel() {
    panelOpen.value = false;
}

//availability
function ymdLocal(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return `${y}-${m}-${day}`;
}

function setAvailability(value) {
    availability.value = value;

    router.post(
        route("user.calendar.availability.set", {
            vehicleSlug: props.vehicle.slug,
        }),
        {
            start: selStart.value ? ymdLocal(selStart.value) : null,
            end: selEnd.value
                ? ymdLocal(selEnd.value)
                : ymdLocal(selStart.value),
            is_blocked: !value,
        },
        {
            preserveScroll: true,
            onSuccess: () => window.location.reload(),
        }
    );
}

//
const dayReservations = computed(() => {
    if (!selStart.value) return [];

    const start = new Date(
        selStart.value.getFullYear(),
        selStart.value.getMonth(),
        selStart.value.getDate(),
        0,
        0,
        0,
        0
    );

    const end = selEnd.value
        ? new Date(
              selEnd.value.getFullYear(),
              selEnd.value.getMonth(),
              selEnd.value.getDate(),
              23,
              59,
              59,
              999
          )
        : new Date(
              selStart.value.getFullYear(),
              selStart.value.getMonth(),
              selStart.value.getDate(),
              23,
              59,
              59,
              999
          );

    return (props.reservations || [])
        .filter((r) => new Date(r.start_at) < end && new Date(r.end_at) > start)
        .sort((a, b) => new Date(a.start_at) - new Date(b.start_at));
});
</script>

<style scoped>
:deep(.is-booked-day) {
    background-color: #fee2e2;
    color: #991b1b;
    border-radius: 0.5rem;
}

:deep(.flatpickr-calendar) {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 4px;
}

:deep(.flatpickr-calendar.inline) {
    width: 100% !important;
    max-width: 100% !important;
}

:deep(.flatpickr-calendar.inline .flatpickr-months),
:deep(.flatpickr-calendar.inline .flatpickr-days) {
    width: 100%;
    column-gap: 12rem;
}
:deep(.flatpickr-day.prevMonthDay),
:deep(.flatpickr-day.nextMonthDay) {
    visibility: hidden;
    pointer-events: none;
    color: white !important;
}

/* Container timp */
:deep(.flatpickr-time) {
    display: flex;
    justify-content: center;
    align-items: center;
}

:deep(.flatpickr-time .numInputWrapper) {
    width: 50px;
    text-align: center;
    padding-right: 12px;
    padding-left: 12px;
    box-sizing: content-box;
}

/* Repoziționăm săgețile */
:deep(.flatpickr-time .arrowUp),
:deep(.flatpickr-time .arrowDown) {
    right: 2px;
}

:deep(.flatpickr-days .dayContainer) {
    margin: 0;
}

:deep(.flatpickr-day) {
    width: 14.2857143% !important;
    max-width: none !important;
    height: 4.5rem;
    line-height: 1.1;
    padding: 0.85rem 0;
    margin: 0;
    min-height: 4.6rem;
}

:deep(.flatpickr-day .fp-day) {
    font-weight: 600;
}

:deep(.flatpickr-time .numInput),
:deep(.flatpickr-time .flatpickr-am-pm),
:deep(.flatpickr-time .flatpickr-hour),
:deep(.flatpickr-time .flatpickr-minute) {
    cursor: pointer !important;
}

:deep(.flatpickr-time .arrowUp),
:deep(.flatpickr-time .arrowDown) {
    cursor: pointer !important;
}

/* zi blocată: pregătește containerul */
:deep(.flatpickr-day.is-blocked-day) {
    position: relative;
    overflow: visible;
}

:deep(.flatpickr-day.is-blocked-day::after) {
    content: "";
    position: absolute;
    left: 18%;
    top: 28%;
    width: 70%;
    height: 2px;
    background: #ef4444;
    pointer-events: none;
    z-index: 2;
}
:deep(.flatpickr-input) {
    display: none !important;
}
:deep(.flatpickr-day .fp-extra) {
    font-size: 0.6rem;
    gap: 0.2rem;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    opacity: 0.9;
}

:deep(.flatpickr-day .fp-day) {
    font-size: 0.9rem;
}

:deep(.flatpickr-day.is-blocked-day .fp-day),
:deep(.flatpickr-day.is-blocked-day .fp-extra) {
    opacity: 0.7;
}

/* legenda calendar */
.legend-box {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 4px;
    position: relative;
}

.legend-line {
    position: absolute;
    left: 19%;
    top: 45%;
    width: 65%;
    height: 2px;
    background: #ef4444;
}
:deep(.fp-extra) {
    font-size: 0.65rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
:deep(.fp-price) {
    margin-top: 0.4rem;
    font-weight: 600;
}
:deep(.fp-currency) {
    font-size: 0.6rem;
    opacity: 0.8;
}
:deep(.flatpickr-day.flatpickr-disabled) {
    pointer-events: auto;
}
@media (min-width: 1024px) {
    :deep(.flatpickr-calendar.inline) {
        width: 700px !important; /* mai lat */
        max-width: 700px !important;
    }
}
@media (min-width: 1024px) {
    :deep(.flatpickr-day) {
        height: 4.5rem;
    }
}
</style>
