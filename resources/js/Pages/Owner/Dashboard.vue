<template>
    <OwnerDashboardLayout :selected-role="dashboardRole" />
</template>

<script setup>
import OwnerDashboardLayout from '@/Layouts/OwnerDashboardLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    selectedRole: { type: String, default: 'Client' },
})

const dashboardRole = ref(props.selectedRole)

function handleRoleChange(role) {
    dashboardRole.value = role
    router.visit(route('owner.dashboard'), {
        method: 'get',
        data: { selectedRole: dashboardRole.value },
        replace: true,
        preserveState: true,
        preserveScroll: true,
    })
}

</script>