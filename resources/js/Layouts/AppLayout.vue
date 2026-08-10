<template>
    <div>
      <!-- <Preloader /> -->
      <ScrollUp />
      <HeaderTop />
      <Header @open-search="showSearch = true" />
      <SearchWrap
        :isOpen="showSearch"
        @close="showSearch = false"
        @search="handleSearch"
      />
      <slot />
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Footer from '@/Components/Footer.vue';
import Header from '@/Components/Header.vue';
import HeaderTop from '@/Components/HeaderTop.vue';
import ScrollUp from '@/Components/ScrollUp.vue';
import SearchWrap from '@/Components/SearchWrap.vue';
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

  const showSearch = ref(false)

  function handleSearch(query) {
    // Inainte doar scria in consola. Acum duce in catalog cu textul cautat.
    showSearch.value = false
    router.get(route('car.index'), { search: query }, { preserveState: false })
  }
  </script>
  