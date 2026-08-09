<template>
    <div class="gallery-section fix">
      <div class="gallery-wrapper">
        <div class="row g-4">
          <!-- First Column -->
          <div class="col-xxl-4 col-xl-5 col-lg-5">
            <div class="row g-4">
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="row g-4">
                  <div class="col-md-12" v-for="n in 2" :key="`left-small-${n}`">
                    <div class="gallery-image">
                      <img :src="`/assets/img/gallery/g-${n}.jpg`" alt="img">
                      <div class="icon-box">
                        <a :href="`/assets/img/gallery/g-${n}.jpg`" class="icon img-popup-2">
                          <i class="fa-solid fa-plus"></i>
                        </a>
                      </div>
                      <div class="mask"></div>
                      <div class="mask-second"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="gallery-image">
                  <img src="/assets/img/gallery/g-3.jpg" alt="img">
                  <div class="icon-box">
                    <a href="/assets/img/gallery/g-3.jpg" class="icon img-popup-2 style-two">
                      <i class="fa-solid fa-plus"></i>
                    </a>
                  </div>
                  <div class="mask"></div>
                  <div class="mask-second"></div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Second Column -->
          <div class="col-xxl-6 col-xl-7 col-lg-7">
            <div class="row g-4">
              <div
                class="col-md-4 col-sm-6"
                v-for="n in 5"
                :key="`middle-${n}`"
                v-if="n <= 4"
              >
                <div class="gallery-image">
                  <img :src="`/assets/img/gallery/g-${n + 3}.jpg`" alt="img">
                  <div class="icon-box">
                    <a :href="`/assets/img/gallery/g-${n + 3}.jpg`" class="icon img-popup-2">
                      <i class="fa-solid fa-plus"></i>
                    </a>
                  </div>
                  <div class="mask"></div>
                  <div class="mask-second"></div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="gallery-image">
                  <img src="/assets/img/gallery/g-8.jpg" alt="img">
                  <div class="icon-box">
                    <a href="/assets/img/gallery/g-8.jpg" class="icon img-popup-2">
                      <i class="fa-solid fa-plus"></i>
                    </a>
                  </div>
                  <div class="mask"></div>
                  <div class="mask-second"></div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Third Column -->
          <div class="col-xxl-2 col-xl-4 col-lg-4 col-md-6 col-sm-6">
            <div class="gallery-image style-2">
              <img src="/assets/img/gallery/g-9.jpg" alt="img">
              <div class="icon-box">
                <a href="/assets/img/gallery/g-9.jpg" class="icon img-popup-2 style-two">
                  <i class="fa-solid fa-plus"></i>
                </a>
              </div>
              <div class="mask"></div>
              <div class="mask-second"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  // No interactivity required for now.
  </script>
  