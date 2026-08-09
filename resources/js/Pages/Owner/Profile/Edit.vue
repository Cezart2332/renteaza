<template>
    <OwnerDashboardLayout>
        <div class="tw-min-h-screen tw-py-10 tw-bg-[#F2F4F5]">
            <div class="tw-max-w-4xl tw-mx-auto tw-bg-white tw-shadow-md tw-p-8">
                <!-- Header -->
                <h1 class="tw-text-3xl tw-font-bold tw-mb-8 tw-text-center tw-text-gray-800">
                    Editează Profil
                </h1>

                <!-- Form -->
                <form @submit.prevent="submit">
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
                        <!-- Name -->
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Nume</label>
                            <input v-model="form.name" type="text"
                                class="tw-w-full tw-rounded-md focus:tw-bg-gray-500 tw-border-gray-300 focus:tw-border-orange-400 focus:ring-orange-400 tw-text-black tw-shadow-sm"
                                placeholder="Your full name" required />
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Email</label>
                            <input v-model="form.email" type="email"
                                class="tw-w-full tw-rounded-md focus:tw-bg-gray-500 tw-border-gray-300 focus:tw-border-orange-400 tw-text-black focus:ring-orange-400 tw-shadow-sm"
                                placeholder="your@email.com" required />
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Telefon</label>
                            <input v-model="form.phone" type="text"
                                class="tw-w-full tw-rounded-md focus:tw-bg-gray-500 tw-border-gray-300 focus:tw-border-orange-400 tw-text-black focus:ring-orange-400 tw-shadow-sm"
                                placeholder="+40712345678" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-1">Parola
                                (optional)</label>
                            <input v-model="form.password" type="password"
                                class="tw-w-full tw-rounded-md focus:tw-bg-gray-500 tw-border-gray-300 focus:tw-border-orange-400 focus:ring-orange-400 tw-shadow-sm"
                                placeholder="Lasă necompletat pentru a păstra parola actuală" />
                        </div>
                    </div>

                    <!-- Link către setarea IBAN pentru payout -->
                    <div
                        class="tw-mt-8 tw-flex tw-items-center tw-justify-between tw-border tw-border-gray-200 tw-rounded-xl tw-bg-white tw-p-4">
                        <div>
                            <h2 class="tw-text-base tw-font-semibold tw-text-gray-800">Detalii încasări</h2>
                            <p class="tw-text-sm tw-text-gray-600">Adaugă/editează IBAN pentru a primi plățile în contul
                                bancar.</p>
                        </div>
                        <inertia-link :href="route('user.payments.bank.show')"
                            class="tw-inline-flex tw-items-center tw-gap-2 tw-px-4 tw-py-2 tw-rounded-full tw-border tw-bg-white hover:tw-bg-gray-50 tw-text-sm tw-font-medium">
                        Configurează IBAN
                        </inertia-link>
                    </div>

                    <!-- Submit Button -->
                    <div v-if="form.isDirty" class="tw-mt-8 tw-flex tw-justify-end">
                        <button type="submit"
                            class="tw-px-6 tw-py-3 tw-bg-orange-500 hover:tw-bg-orange-600 tw-text-white tw-font-bold tw-rounded-full tw-shadow tw-transition tw-duration-300">
                            Salvează
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </OwnerDashboardLayout>
</template>
<script setup>
import OwnerDashboardLayout from "@/Layouts/OwnerDashboardLayout.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone ?? "",
    password: "",
});
function submit() {
    form.put(route("user.profile.update"));
}
</script>
