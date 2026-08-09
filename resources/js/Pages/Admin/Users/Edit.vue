<template>
    <AdminDashboardLayout>
        <div class="tw-flex tw-items-center tw-justify-between tw-mb-6">
            <h2 class="tw-text-2xl tw-text-gray-700 font-cocon-bold">
                Editează utilizator
            </h2>
        </div>

        <form
            @submit.prevent="submit"
            class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6"
        >
            <!-- Coloana stânga -->
            <div
                class="tw-space-y-4 tw-bg-white tw-rounded-lg tw-shadow tw-p-6"
            >
                <!-- Nume + Prenume -->
                <div class="tw-grid tw-grid-cols-2 tw-gap-4">
                    <div>
                        <label
                            class="tw-block tw-text-sm tw-font-medium text-slate-700"
                            >Nume</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Ex: Popescu"
                            class="tw-mt-1 tw-block focus:tw-bg-gray-500 tw-w-full tw-text-black tw-rounded-lg tw-border border-slate-300 tw-px-3 tw-py-2"
                        />
                        <p
                            v-if="errors.name"
                            class="tw-mt-1 tw-text-xs tw-text-red-600"
                        >
                            {{ errors.name }}
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label
                        class="tw-block tw-text-sm tw-font-medium text-slate-700"
                        >Email</label
                    >
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="user@exemplu.ro"
                        class="tw-mt-1 tw-block tw-w-full focus:tw-bg-gray-500 tw-rounded-lg tw-text-black tw-border border-slate-300 tw-px-3 tw-py-2 focus:border-emerald-500 focus:ring-emerald-200"
                    />
                    <p
                        v-if="errors.email"
                        class="tw-mt-1 tw-text-xs tw-text-red-600"
                    >
                        {{ errors.email }}
                    </p>
                </div>

                <!-- Email verificat la -->
                <div>
                    <label
                        class="tw-block tw-text-sm tw-font-medium text-slate-700"
                        >Data email verificat</label
                    >
                    <input
                        type="date"
                        :value="form.email_verified_at"
                        readonly
                        class="tw-mt-1 tw-w-full tw-bg-gray-100 tw-rounded-lg tw-text-black tw-border border-slate-300 tw-px-3 tw-py-2 focus:border-emerald-500 focus:ring-emerald-200"
                    />
                    <p
                        v-if="errors.email_verified_at"
                        class="tw-mt-1 tw-text-xs tw-text-red-600"
                    >
                        {{ errors.email_verified_at }}
                    </p>
                </div>

                <!-- Telefon -->
                <div>
                    <label
                        class="tw-block tw-text-sm tw-font-medium text-slate-700"
                        >Telefon</label
                    >
                    <input
                        v-model="form.phone"
                        type="tel"
                        placeholder="0733xxxxxx"
                        class="tw-mt-1 tw-block focus:tw-bg-gray-500 tw-w-full tw-rounded-lg tw-text-black tw-border border-slate-300 tw-px-3 tw-py-2 focus:border-emerald-500 focus:ring-emerald-200"
                    />
                    <p
                        v-if="errors.phone"
                        class="tw-mt-1 tw-text-xs tw-text-red-600"
                    >
                        {{ errors.phone }}
                    </p>
                </div>
            </div>
            <div
                class="tw-bg-white tw-rounded-lg tw-shadow tw-p-6 tw-space-y-8"
            >
                <!-- Titlu -->
                <h2 class="tw-text-xl tw-font-bold text-slate-800">
                    Statusul Contului
                </h2>

                <!-- Status -->
                <div class="tw-flex tw-justify-start">
                    <span
                        class="tw-inline-block tw-uppercase tw-px-6 tw-py-2 tw-rounded-full tw-text-sm tw-font-bold"
                        :class="{
                            'tw-bg-yellow-100 tw-text-yellow-800':
                                form.status === 'pending',
                            'tw-bg-green-100 tw-text-green-800':
                                form.status === 'accepted',
                            'tw-bg-red-100 tw-text-red-800':
                                form.status === 'declined',
                        }"
                    >
                        STATUSUL CURENT ESTE: {{ form.status.toUpperCase() }}
                    </span>
                </div>

                <!-- Butoane Vezi Documente -->
                <div class="tw-flex tw-flex-col tw-space-y-4 tw-w-72">
                    <inertia-link
                        :href="
                            route('admin.users.personal-documents.show', props.user.id)
                        "
                        class="tw-bg-blue-100 hover:tw-bg-blue-200 tw-text-blue-800 tw-px-8 tw-py-3 tw-rounded-lg tw-text-sm tw-font-medium tw-shadow-md"
                    >
                        Vezi Documentele Personale
                    </inertia-link>
                    <inertia-link
                        :href="
                            route('admin.users.vehicles.show', props.user.id)
                        "
                        class="tw-bg-blue-100 hover:tw-bg-blue-200 tw-text-blue-800 tw-px-8 tw-py-3 tw-rounded-lg tw-text-sm tw-font-medium tw-shadow-md"
                    >
                        Vezi Vehiculele Utilizatorului
                    </inertia-link>
                </div>
            </div>
        </form>

        <button
            @click="submit"
            :disabled="!form.isDirty || form.processing"
            class="tw-inline-flex tw-items-center tw-mt-8 tw-text-white font-cocon tw-px-4 tw-py-2 tw-rounded-lg tw-transition"
            :class="{
                'tw-bg-orange-500 ': form.isDirty && !form.processing,
                'tw-bg-gray-400 tw-cursor-not-allowed':
                    !form.isDirty || form.processing,
            }"
        >
            Salvează
        </button>
    </AdminDashboardLayout>
</template>
<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";

function toDateInput(value) {
    if (!value) return "";
    return value.split("T")[0];
}

const props = defineProps({
    user: Object,
});

const { errors } = usePage().props;

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    email_verified_at: toDateInput(props.user.email_verified_at),
    phone: props.user.phone || "",
    status: props.user.status,
});

// trimite update
function submit() {
    form.put(route("admin.users.update", props.user.id));
}
</script>
