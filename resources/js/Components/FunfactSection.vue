<template>
  <section class="relative funfact-section section-padding bg-cover bg-center"
    style="background-image: url('/images/car-banner.jpeg')">
    <div class="absolute inset-0 backdrop-blur-[3px] bg-black/50 z-0"></div>
    <div class="relative z-10">
      <div class="container">
        <div class="funfact-wrapper">
          <div class="row g-4 align-items-center">
            <!-- Left Title -->
            <div class="col-lg-6">
              <div class="section-title mb-0">
                <!-- <img src="/assets/img/sub-icon-2.png" alt="icon-img" class="wow fadeInUp" />
              <span class="wow fadeInUp" data-wow-delay=".2s">fun facts</span> -->
                <h2 class="text-white wow fadeInUp" data-wow-delay=".4s">
                  Totul automat <br />
                  Totul securizat <br />
                  Totul RENTează
                </h2>
              </div>
            </div>

            <!-- Right Funfacts -->
            <div class="col-lg-6">
              <div class="funfact-counter-area">
                <div class="funfact-items wow fadeInUp" data-wow-delay=".3s">
                  <div class="icon flex items-center justify-center ">
                    <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                      <circle cx="12" cy="12" r="7.25" stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5"></circle>
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15.25 12C15.25 16.5 13.2426 19.25 12 19.25C10.7574 19.25 8.75 16.5 8.75 12C8.75 7.5 10.7574 4.75 12 4.75C13.2426 4.75 15.25 7.5 15.25 12Z">
                      </path>
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M5 12H12H19"></path>
                    </svg>

                  </div>
                  <h2><span class="count">100%</span></h2>
                  <p class="text-[#FF3726] font-semibold">Proces<br />digital</p>
                </div>
                <div class="funfact-items wow fadeInUp" data-wow-delay=".5s">
                  <div class="icon flex items-center justify-center">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24">
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M7.75 19.25H16.25C17.3546 19.25 18.25 18.3546 18.25 17.25V9L14 4.75H7.75C6.64543 4.75 5.75 5.64543 5.75 6.75V17.25C5.75 18.3546 6.64543 19.25 7.75 19.25Z">
                      </path>
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M18 9.25H13.75V5"></path>
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.75 15.25H14.25"></path>
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9.75 12.25H14.25"></path>
                    </svg>

                  </div>
                  <h2><span class="count">100%</span></h2>
                  <p class="text-[#FF3726] font-semibold">Contracte digitale automate</p>
                </div>
                <div class="funfact-items wow fadeInUp" data-wow-delay=".7s">
                  <div class="icon flex items-center justify-center">
                    <!-- <img src="/assets/img/icon/like.png" alt="img" /> -->
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24">
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M5.78168 19.25H13.2183C13.7828 19.25 14.227 18.7817 14.1145 18.2285C13.804 16.7012 12.7897 14 9.5 14C6.21031 14 5.19605 16.7012 4.88549 18.2285C4.773 18.7817 5.21718 19.25 5.78168 19.25Z">
                      </path>
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M15.75 14C17.8288 14 18.6802 16.1479 19.0239 17.696C19.2095 18.532 18.5333 19.25 17.6769 19.25H16.75">
                      </path>
                      <circle cx="9.5" cy="7.5" r="2.75" stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5"></circle>
                      <path stroke="#FF3726" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M14.75 10.25C16.2688 10.25 17.25 9.01878 17.25 7.5C17.25 5.98122 16.2688 4.75 14.75 4.75">
                      </path>
                    </svg>

                  </div>
                  <h2><span class="count">100%</span></h2>
                  <p class="text-[#FF3726] font-semibold">Utilizatori <br />verificați</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Dacă vrei să adaugi un counter animat, putem integra un plugin sau `IntersectionObserver` cu `ref` și `onMounted`.
</script>

<style scoped>
/* opțional: adaugi stiluri specifice */
</style>