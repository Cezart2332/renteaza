<template>
    <div id="preloader" class="preloader">
      <div class="animation-preloader">
        <div class="spinner"></div>
        <div class="txt-loading">
          <span data-text-preloader="R" class="letters-loading">R</span>
          <span data-text-preloader="E" class="letters-loading">E</span>
          <span data-text-preloader="M" class="letters-loading">M</span>
          <span data-text-preloader="O" class="letters-loading">O</span>
          <span data-text-preloader="N" class="letters-loading">N</span>
          <span data-text-preloader="S" class="letters-loading">S</span>
        </div>
        <p class="text-center">Loading</p>
      </div>
      <div class="loader">
        <div class="row">
          <div class="col-3 loader-section section-left">
            <div class="bg"></div>
          </div>
          <div class="col-3 loader-section section-left">
            <div class="bg"></div>
          </div>
          <div class="col-3 loader-section section-right">
            <div class="bg"></div>
          </div>
          <div class="col-3 loader-section section-right">
            <div class="bg"></div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  // nimic special aici, doar template static
  </script>
  
  <style scoped>
  /* Dacă ai avut CSS asociat cu preloaderul, pune-l aici */
  </style>
  