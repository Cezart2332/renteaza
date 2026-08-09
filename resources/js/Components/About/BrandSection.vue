<template>
    <div class="brand-wrapper style-2 pb-80">
        <div class="array-button">
            <button class="array-prev-2" @click="slidePrev">
                <i class="far fa-chevron-left"></i>
            </button>
            <button class="array-next-2" @click="slideNext">
                <i class="far fa-chevron-right"></i>
            </button>
        </div>
        <div class="container">
            <swiper
                :slides-per-view="5"
                :space-between="30"
                :navigation="{
                    nextEl: '.array-next-2',
                    prevEl: '.array-prev-2',
                }"
                @swiper="(swiper) => (swiperInstance = swiper)"
                class="brand-slider"
            >
                <swiper-slide v-for="(img, index) in images" :key="index">
                    <div class="brand-image style-color">
                        <img :src="img" alt="brand-img" />
                    </div>
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>
<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue'
import SwiperCore from 'swiper'

// Activează Navigation manual
import { Navigation } from 'swiper/modules'
SwiperCore.use([Navigation])

// Stiluri necesare
import 'swiper/css'
import 'swiper/css/navigation'

const images = [
  '/assets/img/brand/01.png',
  '/assets/img/brand/02.png',
  '/assets/img/brand/03.png',
  '/assets/img/brand/04.png',
  '/assets/img/brand/05.png',
  '/assets/img/brand/06.png'
]

let swiperInstance = null

function slidePrev() {
  if (swiperInstance) swiperInstance.slidePrev()
}

function slideNext() {
  if (swiperInstance) swiperInstance.slideNext()
}

</script>
