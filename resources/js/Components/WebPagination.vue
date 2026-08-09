<template>
    <div class="tw-flex tw-flex-row tw-justify-center tw-items-center">
        <component :is="link.url ? 'inertia-link' : 'span'" v-for="link in links.slice(1, -1)" :href="link.url"
            v-html="link.label" class="tw-px-3 tw-py-1 tw-cursor-pointer tw-text-lg" :key="link.label"
            :class="{ 'text-slate-500': !link.url, 'tw-text-white tw-rounded-full tw-bg-[#FA902F]': link.active }"
            preserve-scroll />
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
