<!-- resources/js/Components/ToggleWithLabel.vue -->
<template>
  <SwitchGroup as="div" class="tw-flex tw-items-center tw-font-semibold">
    <SwitchLabel as="span" class="tw-ml-3 tw-text-sm tw-mr-2">
      <span :class="enabled ? 'tw-text-gray-500' : 'tw-text-slate-50'">Client</span>
    </SwitchLabel>

    <Switch
      v-model="enabled"
      :class="[
        'tw-relative tw-inline-flex tw-h-6 tw-w-11 tw-cursor-pointer tw-rounded-full tw-p-0.5 tw-transition-colors tw-duration-200 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-indigo-600 focus:tw-ring-offset-2',
        enabled ? 'tw-bg-indigo-600' : 'tw-bg-gray-600'
      ]"
    >
      <span class="tw-sr-only">Schimbă rolul</span>

      <!-- Knob absolut + transform fallback -->
      <span
        aria-hidden="true"
        :class="[
          'tw-absolute tw-top-0.5 tw-left-0.5 tw-h-5 tw-w-5 tw-rounded-full tw-bg-white tw-shadow tw-ring-0 tw-transition tw-duration-200 tw-transform',
          enabled ? 'tw-translate-x-5' : 'tw-translate-x-0'
        ]"
        :style="{ transform: enabled ? 'translateX(1.25rem)' : 'translateX(0)' }"
      />
    </Switch>

    <SwitchLabel as="span" class="tw-text-sm tw-ml-3">
      <span :class="enabled ? 'tw-text-slate-50' : 'tw-text-gray-500'">Proprietar</span>
    </SwitchLabel>
  </SwitchGroup>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue'

const props = defineProps({ role: { type: String, default: 'Client' } })
const emit = defineEmits(['update:role'])

const enabled = ref(props.role === 'Proprietar')

watch(() => props.role, (val) => { enabled.value = (val === 'Proprietar') })
watch(enabled, (val) => { emit('update:role', val ? 'Proprietar' : 'Client') })
</script>
