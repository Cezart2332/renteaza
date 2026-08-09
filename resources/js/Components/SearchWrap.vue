<template>
    <div class="search-wrap" v-show="isOpen">
      <div class="search-inner">
        <i class="fas fa-times search-close" @click="closeSearch"></i>
        <div class="search-cell">
          <form @submit.prevent="submitSearch">
            <div class="search-field-holder">
              <input
                type="search"
                class="main-search-input"
                placeholder="Search..."
                v-model="searchQuery"
              />
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const props = defineProps({
    isOpen: {
      type: Boolean,
      default: false
    }
  })
  
  const emits = defineEmits(['close', 'search'])
  
  const searchQuery = ref('')
  
  function closeSearch() {
    emits('close')
  }
  
  function submitSearch() {
    emits('search', searchQuery.value)
  }
  </script>
  
  <style scoped>
  /* Adaugă stiluri custom dacă e nevoie */
  </style>
  