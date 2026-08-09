<template>
    <!-- Cardul e vizibil doar pe mobil -->
    <div
        class="md:tw-hidden tw-w-full tw-bg-white tw-rounded-xl tw-shadow-sm tw-ring-1 tw-ring-slate-200/60 tw-p-4 tw-space-y-2"
    >
        <h3 class="tw-text-sm tw-font-semibold tw-text-gray-900">
            Rezumat rezervări
        </h3>

        <ul class="tw-divide-y tw-divide-slate-200/70">
            <li
                v-for="item in orderedItems"
                :key="item.title"
                class="tw-flex tw-items-center tw-justify-between tw-py-2"
            >
                <!-- Stânga: punct color + titlu -->
                <div class="tw-flex tw-items-center tw-gap-2 tw-min-w-0">
                    <span
                        class="tw-h-2.5 tw-w-2.5 tw-rounded-full tw-inline-block"
                        :class="dotClass(item.title)"
                    />
                    <span
                        class="tw-text-sm tw-font-medium tw-text-gray-800 tw-truncate"
                    >
                        {{ t(item.title) }}
                    </span>
                </div>

                <!-- Dreapta: valoare + chip procent -->
                <div class="tw-text-right">
                    <div
                        class="tw-text-base tw-font-extrabold tw-text-gray-900"
                    >
                        {{ item.current }}
                    </div>
                    <div
                        class="tw-inline-flex tw-items-center tw-gap-1 tw-text-[11px] tw-font-medium tw-px-2 tw-py-0.5 tw-rounded-full tw-mt-1"
                        :class="
                            item.trend === 'up'
                                ? 'tw-bg-blue-50 tw-text-blue-700'
                                : 'tw-bg-rose-50 tw-text-rose-700'
                        "
                    >
                        <svg
                            v-if="item.trend === 'up'"
                            class="tw-w-3.5 tw-h-3.5"
                            viewBox="0 0 10 10"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M5 1.25c.173 0 .313.14.313.313v6.875a.313.313 0 1 1-.626 0V1.563c0-.173.14-.313.313-.313z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M4.78 1.34a.313.313 0 0 1 .44 0l2.813 2.813a.313.313 0 1 1-.442.442L5 2.004 2.408 4.596a.313.313 0 1 1-.442-.442L4.78 1.34z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="tw-w-3.5 tw-h-3.5"
                            viewBox="0 0 10 10"
                            fill="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M5 8.75a.313.313 0 0 0 .313-.313V1.563a.313.313 0 1 0-.626 0v6.874c0 .173.14.313.313.313z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M5.22 8.658a.313.313 0 0 1-.44 0L1.967 5.846a.313.313 0 1 1 .442-.442L5 7.996l2.592-2.592a.313.313 0 1 1 .442.442L5.22 8.658z"
                            />
                        </svg>
                        <span>{{ signed(item) }}%</span>
                    </div>
                </div>
            </li>
        </ul>

        <!-- notă scurtă -->
        <p class="tw-text-[11px] tw-text-slate-500">vs. săptămâna trecută</p>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    // obiectul întreg { Pending: {...}, Cancelled: {...}, Completed: {...} }
    stats: { type: Object, required: true },
});

const ORDER = ["Pending", "Completed", "Cancelled"];

const titleMap = {
    Pending: "În așteptare",
    Completed: "Finalizate",
    Cancelled: "Anulate",
};
const t = (en) => titleMap[en] ?? en;

// compactăm într-o listă în ordinea dorită, filtrăm ce nu există
const orderedItems = computed(() =>
    ORDER.map((k) => props.stats?.[k]).filter(Boolean)
);

// punct color în funcție de tip
const dotClass = (title) =>
    ({
        Pending: "tw-bg-amber-500",
        Completed: "tw-bg-emerald-500",
        Cancelled: "tw-bg-rose-500",
    }[title] ?? "tw-bg-slate-400");

// semn +/-
const signed = (item) => {
    const p = Number(item?.percent ?? 0);
    const s = item?.trend === "down" && p > 0 ? -p : p;
    const abs = Math.abs(s).toFixed(2).replace(/\.00$/, "");
    return `${s >= 0 ? "+" : "-"} ${abs}`;
};
</script>
