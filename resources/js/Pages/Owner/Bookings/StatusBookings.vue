<template>
    <div v-for="stat in stats">
        <CardStatusBooking :stat="stat" />
    </div>
</template>
<script setup>
import CardStatusBooking from "./CardStatusBooking.vue";
defineProps({
    stats: Array,
});
</script>
