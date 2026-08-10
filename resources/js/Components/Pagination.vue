<template>
    <div>
        <component :is="link.url ? 'inertia-link' : 'span'" v-for="link in links" :href="link.url" v-html="link.label"
            class="tw-p-4 tw-text-sm tw-cursor-pointer" :key="link.label"
            :class="{ 'tw-text-gray-500': !link.url, 'tw-font-bold': link.active }" preserve-scroll />
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
