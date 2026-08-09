<template>
    <AdminDashboardLayout>
        <div class="tw-p-4 sm:tw-p-6 tw-max-w-6xl tw-mx-auto">
            <h1 class="tw-text-2xl tw-font-semibold tw-mb-4">Check-ins pending</h1>

            <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4">
                <div v-for="s in submissions.data" :key="s.id" class="tw-border tw-rounded-xl tw-p-3 tw-bg-white">
                    <div class="tw-text-sm tw-text-gray-600 tw-mb-2">
                        <div>Submission #{{ s.id }}</div>
                        <div>Booking: {{ s.booking.id }}</div>
                        <div>Client: {{ s.booking.client ?? '—' }}</div>
                        <div>Sent: {{ s.submitted_at }}</div>
                    </div>

                    <div class="tw-grid tw-grid-cols-4 tw-gap-2 tw-mb-3">
                        <img v-for="p in s.photos" :key="p.position" :src="p.url"
                            class="aspect-square tw-w-full tw-object-cover tw-rounded-lg tw-border" />
                    </div>

                    <div class="tw-flex tw-gap-2">
                        <button class="tw-px-3 tw-py-2 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                            @click="approve(s.id)">Aprobă</button>

                        <button class="tw-px-3 tw-py-2 tw-rounded-lg tw-border hover:tw-bg-gray-50"
                            @click="reject(s.id)">Respinge</button>
                    </div>
                </div>
            </div>

            <div class="tw-mt-6 tw-flex tw-gap-2" v-if="submissions.links?.length">
                <a v-for="l in submissions.links" :key="l.url + l.label" :href="l.url || '#'" v-html="l.label"
                    class="tw-px-3 tw-py-1 tw-rounded tw-border"
                    :class="[{ 'tw-bg-gray-900 tw-text-white': l.active }, { 'tw-opacity-50 tw-pointer-events-none': !l.url }]" />
            </div>
        </div>
    </AdminDashboardLayout>
</template>

<script setup>
import AdminDashboardLayout from '@/Layouts/AdminDashboardLayout.vue'
import { router } from '@inertiajs/vue3'
const props = defineProps({ submissions: Object })

const approve = (id) => {
    router.post(route('admin.checkins.approve', { submission: id }))
}
const reject = (id) => {
    const reason = window.prompt('Motiv respingere?')
    if (!reason) return
    router.post(route('admin.checkins.reject', { submission: id }), { reason })
}
</script>