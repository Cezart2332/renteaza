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
import Preloader from '@/Components/Preloader.vue';
import ScrollUp from '@/Components/ScrollUp.vue';
import SearchWrap from '@/Components/SearchWrap.vue';
import { ref } from 'vue'

  const showSearch = ref(false)

  function handleSearch(query) {
    console.log('Search triggered with:', query)
  }
  </script>
  