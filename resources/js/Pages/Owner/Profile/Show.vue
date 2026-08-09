<template>
    <OwnerDashboardLayout>
        <div class="">
            <!-- Cover -->
            <div
                class="tw-relative tw-h-48 sm:tw-h-56 md:tw-h-64 lg:tw-h-72 xl:tw-h-80 tw-overflow-hidden tw-rounded-b-2xl"
            >
                <div
                    class="tw-absolute tw-inset-0 tw-bg-black tw-bg-opacity-30"
                ></div>
            </div>

            <!-- Main Content -->
            <div class="tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-mx-auto">
                <!-- Profile Header -->
                <div
                    class="tw-flex tw-flex-col md:tw-flex-row md:tw-items-center md:tw-gap-6 tw-mt-8"
                >
                    <!-- Avatar -->
                    <div class="tw--mt-32 tw-flex-shrink-0 tw-z-10">
                        <img
                            :src="user.profile_picture || '/images/default-avatar.png'"
                            alt="Avatar"
                            class="tw-w-32 tw-h-32 md:tw-w-48 md:tw-h-48 tw-rounded-full tw-border-4 tw-border-white tw-shadow-lg"
                        />
                    </div>

                    <!-- Name and Role -->
                    <div class="tw-mt-4 md:tw-mt-8">
                        <h1
                            class="tw-text-2xl md:tw-text-3xl lg:tw-text-4xl tw-font-bold tw-flex tw-items-center"
                        >
                            {{ user.name }}
                            <span
                                class="tw-inline-block tw-ml-2 tw-text-xs tw-font-semibold tw-px-2.5 tw-py-0.5 tw-rounded"
                                :class="{
                                    'tw-bg-yellow-100 tw-text-yellow-800':
                                        user.status === 'pending',
                                    'tw-bg-green-100 tw-text-green-800':
                                        user.status === 'accepted',
                                    'tw-bg-red-100 tw-text-red-800':
                                        user.status === 'declined',
                                    'tw-bg-gray-100 tw-text-gray-800': ![
                                        'pending',
                                        'accepted',
                                        'declined',
                                    ].includes(user.status),
                                }"
                            >
                                {{
                                    user.status.charAt(0).toUpperCase() +
                                    user.status.slice(1)
                                }}
                            </span>
                        </h1>
                        <p class="tw-text-sm md:tw-text-base tw-text-gray-600">
                            Proprietar – Închirieri auto
                        </p>
                    </div>

                    <!-- Edit Profile Button -->
                    <div class="tw-mt-6">
                        <inertia-link
                            :href="route('user.profile.edit')"
                            class="tw-mt-4 tw-ml-24 md:tw-mt-0 tw-inline-flex tw-items-center tw-bg-orange-500 hover:tw-bg-orange-600 focus:tw-outline-none focus:tw-ring-2 focus:ring-orange-400 tw-text-white tw-font-semibold tw-px-4 tw-py-2 tw-rounded-lg tw-shadow tw-transition tw-duration-200"
                        >
                            Editează Profil
                        </inertia-link>
                    </div>
                </div>
                <UploadAlert
                    :documents="documents.data"
                    :allowed-types="allowedTypes"
                    :total-documents="totalDocuments"
                    :labels="filteredLabels"
                />

                <!-- Stats -->
                <div
                    class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-3 tw-gap-6 tw-mt-8"
                >
                    <!-- Cars Listed -->
                    <div
                        class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-6 tw-flex tw-flex-col tw-items-center"
                    >
                        <div
                            class="tw-text-3xl tw-font-semibold tw-text-gray-800"
                        >
                            {{ vehicles_nr }}
                        </div>
                        <div class="tw-text-sm tw-text-gray-500 tw-mt-1">
                            Cars Listed
                        </div>
                    </div>

                    <!-- Active Rentals -->
                    <div
                        class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-6 tw-flex tw-flex-col tw-items-center"
                    >
                        <div
                            class="tw-text-3xl tw-font-semibold tw-text-gray-800"
                        >
                            {{ active_rentals }}
                        </div>
                        <div class="tw-text-sm tw-text-gray-500 tw-mt-1">
                            Active Rentals
                        </div>
                    </div>

                    <!-- Rating -->
                    <div
                        class="tw-bg-white tw-shadow tw-rounded-2xl tw-p-6 tw-flex tw-flex-col tw-items-center"
                    >
                        <div class="tw-flex tw-items-center tw-space-x-2">
                            <span
                                class="tw-text-3xl tw-font-semibold tw-text-gray-800"
                                >3.5 / 5</span
                            >
                            <span class="tw-text-yellow-500 tw-text-2xl"
                                >⭐</span
                            >
                        </div>
                        <div class="tw-text-sm tw-text-gray-500 tw-mt-1">
                            Evaluare medie
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div
                    class="tw-mt-10 backdrop-blur-md tw-bg-white tw-rounded-2xl tw-shadow-lg tw-p-8"
                >
                    <div
                        class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-6"
                    >
                        <div class="tw-flex tw-items-center tw-gap-4 tw-p-4">
                            <div
                                class="tw-text-orange-600 tw-p-2 tw-rounded-full"
                            >
                                ✉️
                            </div>
                            <div>
                                <div class="tw-text-sm tw-text-gray-500">
                                    Email
                                </div>
                                <div class="tw-font-semibold tw-text-gray-800">
                                    {{ user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-center tw-gap-4 tw-p-4">
                            <div
                                class="tw-text-blue-600 tw-p-2 tw-rounded-full"
                            >
                                📞
                            </div>
                            <div>
                                <div class="tw-text-sm tw-text-gray-500">
                                    Telefon
                                </div>
                                <div class="tw-font-semibold tw-text-gray-800">
                                    {{ user.phone }}
                                </div>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-center tw-gap-4 tw-p-4">
                            <div
                                class="tw-text-yellow-600 tw-p-2 tw-rounded-full"
                            >
                                🗓️
                            </div>
                            <div>
                                <div class="tw-text-sm tw-text-gray-500">
                                    Membru din
                                </div>
                                <div class="tw-font-semibold tw-text-gray-800">
                                    {{ user.created_at }}
                                </div>
                            </div>
                        </div>

                        <div class="tw-flex tw-items-center tw-gap-4 tw-p-4">
                            <div class="tw-text-red-600 tw-p-2 tw-rounded-full">
                                ✅
                            </div>
                            <div>
                                <div class="tw-text-sm tw-text-gray-500">
                                    Închirieri efectuate
                                </div>
                                <div class="tw-font-semibold tw-text-gray-800">
                                    {{ bookings_nr }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tw-rounded-2xl">
                <ReusableTableStatus
                    class="tw-p-8"
                    title="Documente"
                    description="Aici poți vizualiza toate documentele tale."
                    :columns="[
                        { key: 'type', label: 'Document' },
                        {
                            key: 'expires_at',
                            label: 'Data expirare',
                            sort: true,
                        },
                        { key: 'verified_at', label: 'Data verificare' },
                        { key: 'admin_comment', label: 'Comentariu Admin' },
                        { key: 'status', label: 'Status', status: true },
                    ]"
                    :rows="documents"
                    :searchable="true"
                    :pagination="true"
                    @search-changed="searchTable"
                    @sort-changed="sortTable"
                    :prevSearch="prevSearch"
                />
            </div>
        </div>
    </OwnerDashboardLayout>
</template>

<script setup>
import ReusableTableStatus from "@/Components/ReusableTableStatus.vue";
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import UploadAlert from "./UploadAlert.vue";
import { computed, ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    documents: Array,
    totalDocuments: Number,
    allowedTypes: Array,
    prevSearch: String,
    bookings_nr: Number,
    experience: String,
    vehicles_nr: Number,
    active_rentals: Number,
});

const filteredLabels = computed(() => {
    return props.allowedTypes.reduce((acc, type) => {
        acc[type] = type
            .replace(/_/g, " ")
            .replace(/\b\w/g, (c) => c.toUpperCase());
        return acc;
    }, {});
});

const sort = ref({ order: "desc" });
const search = ref("");

function sortTable({ key, order }) {
    sort.value = { key, order };
    fetch();
}
function searchTable(newSearch) {
    search.value = newSearch;
    fetch();
}

function fetch() {
    router.get(
        route("user.profile.show"),
        {
            key: sort.value.key,
            order: sort.value.order,
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}
</script>
