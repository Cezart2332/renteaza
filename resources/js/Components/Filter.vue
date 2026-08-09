<template>
    <div>
        <label :for="filter.id" class="tw-block tw-text-sm tw-font-medium text-[#613D7B] font-cocon">{{ filter.label }}</label>
        <template v-if="filter.type === 'text'">
            <input :value="value" @input="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                :placeholder="filter.placeholder" type="text"
                class="tw-mt-1 tw-block tw-w-full tw-shadow-md sm:tw-text-sm tw-rounded-full tw-text-[#613D7B] tw-font-cocon-bold tw-h-10 tw-px-4 tw-bg-[#EBE0F4]">
        </template>

        <!-- type select -->
        <template v-else-if="filter.type === 'select'">
            <select :value="value" @change="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                class="tw-mt-1 tw-block tw-w-full tw-shadow-md sm:tw-text-sm tw-bg-[#EBE0F4] tw-text-[#613D7B] tw-font-cocon-bold tw-h-10 tw-px-4">
                <option value="">{{ filter.placeholder }}</option>
                <option v-for="option in filter.options" :value="option.value" :key="option.value">
                    <span class="font-cocon-bold ">
                        {{ option.label }}
                    </span>
                </option>
            </select>
        </template>

        <!-- type number -->
        <template v-else-if="filter.type === 'number'">
            <input :value="value" @input="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                type="number"
                class="tw-mt-1 focus:tw-ring-indigo-500 focus:tw-border-indigo-500 tw-block tw-w-full tw-shadow-md sm:tw-text-sm tw-border-gray-300 tw-rounded-md">
        </template>

        <!-- price dropdown -->
        <!-- <template v-else-if="filter.type === 'price'">
            <div class="tw-mt-1 tw-relative tw-rounded-md tw-shadow-md">
                <select v-model="value" @change="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                    class="focus:tw-ring-indigo-500 focus:tw-border-indigo-500 tw-block tw-w-full tw-pl-3 tw-pr-12 sm:tw-text-sm tw-border-gray-300 tw-rounded-md">
                    <option value="">Selectează prețul</option>
                    <option v-for="priceRange in priceRanges" :value="priceRange.value" :key="priceRange.value">
                        {{ priceRange.label }}
                    </option>
                </select>
            </div>
        </template> -->

        <!-- type date -->
        <template v-else-if="filter.type === 'date'">
            <input :value="value" @input="updateValue($event.target.value)" :id="filter.id" :name="filter.id"
                type="date"
                class="tw-mt-1 focus:tw-ring-indigo-500 focus:tw-border-indigo-500 tw-block tw-w-full tw-shadow-md sm:tw-text-sm tw-border-gray-300 tw-rounded-md">
        </template>
    </div>
</template>

<script>
export default {
    props: {
        filter: {
            type: Object,
            required: true
        },
        value: {
            type: [String, Number, Array],
            default: ''
        },
    },
    emits: ['update:value'],
    methods: {
        updateValue(newValue) {
            this.$emit('update:value', newValue);
        }
    }
};
</script>