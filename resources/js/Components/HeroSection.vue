<template>
  <section class="hero-section hero-1 fix">
    <div class="array-button">
      <button @click="swiper.slidePrev()" class="image-array-left">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button @click="swiper.slideNext()" class="image-array-right">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </div>

    <Swiper :modules="[Navigation, Autoplay]" class="hero-slider" :loop="true" :autoplay="{ delay: 5000 }"
      :onSwiper="setSwiper">
      <SwiperSlide v-for="(slide, index) in slides" :key="index">
        <div class="hero-image bg-cover" :style="{ backgroundImage: `url(${slide.bg})` }">
          <div class="overlay-shape">
            <img :src="slide.overlay" alt="img" />
          </div>
        </div>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-xl-12">
              <div class="hero-content text-center">
                <h4 class="text-white" data-animation="fadeInUp" data-delay="1.3s">{{ slide.subtitle }}</h4>
                <h1 class="text-white text-[64px]" data-animation="fadeInUp" data-delay="1.3s" style="animation-delay: 1.3s;" v-html="slide.title" />
              </div>
            </div>
          </div>
        </div>
      </SwiperSlide>
    </Swiper>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import 'swiper/css/navigation'
import { Navigation, Autoplay } from 'swiper/modules'

const swiper = ref(null)
const setSwiper = (instance) => {
  swiper.value = instance
}

const slides = [
  {
    bg: '/assets/img/hero/hero_image_2.jpg',
    overlay: '/assets/img/hero/overlay.png',
    subtitle: 'Platformă completă de mobilitate digitală',
    title: 'Indiferent de nevoie, <span>RENTează</span>',
  },
  // {
  //   bg: '/assets/img/hero/hero_image_1.jpg',
  //   overlay: '/assets/img/hero/overlay.png',
  //   subtitle: 'Book Any Luxury Car in low price',
  //   title: 'Car <span>Rental</span>',
  // },
  // {
  //   bg: '/assets/img/hero/hero_image_3.jpg',
  //   overlay: '/assets/img/hero/overlay.png',
  //   subtitle: 'Book Any Luxury Car in low price',
  //   title: 'Car <span>Rental</span>',
  // },
  // {
  //   bg: '/assets/img/hero/hero_image_4.jpg',
  //   overlay: '/assets/img/hero/overlay.png',
  //   subtitle: 'Book Any Luxury Car in low price',
  //   title: 'Car <span>Rental</span>',
  // },
]
</script>