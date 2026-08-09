<template>
    <div>
        <component :is="link.url ? 'inertia-link' : 'span'" v-for="link in links" :href="link.url" v-html="link.label"
            class="p-4 text-sm cursor-pointer" :key="link.label"
            :class="{ 'text-gray-500': !link.url, 'font-bold': link.active }" preserve-scroll />
    </div>
</template>

<script>
export default {
    name: 'Pagination',

    props: {
        links: {
            type: Array,
            required: true,
        },
    },
};
</script>
