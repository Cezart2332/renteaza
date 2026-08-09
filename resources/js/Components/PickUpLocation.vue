<template>
  <div class="pickup-loaction-area bg-cover" style="background-image: url('/assets/img/brand-bg.png')">
    <div class="container">
      <div class="pickup-wrapper wow fadeInUp tw-p-4" data-wow-delay=".4s">
        <!-- Tip închiriere -->
        <div class="pickup-items">
          <label class="field-label tw-mr-8">Tip închiriere</label>

          <!-- păstrezi clasa pentru stil, dar limităm layout-ul -->
          <div class="tw-inline-block tw-w-fit">
            <select name="cate" v-model="rentType"
              class="category tw-inline-block !tw-w-56 tw-flex-none tw-truncate tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-pl-3 tw-pr-8 tw-text-gray-900 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500">
              <option disabled value="">Selectează un tip</option>
              <option v-for="type in rentalTypes" :key="type.id" :value="type.id">
                {{ type.label }}
              </option>
            </select>
          </div>
        </div>

        <!-- Loc de ridicare -->
        <div class="pickup-items">
          <label class="field-label tw-mr-4">Loc de ridicare</label>
          <div class=" tw-inline-block tw-w-fit">
            <select v-model="pickupLocation"
              class="tw-inline-block !tw-w-56 tw-flex-none tw-truncate tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-pl-3 tw-pr-8 tw-text-gray-900 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500">
              <option disabled value="">Selectează</option>
              <option v-for="loc in pickupLocations" :key="loc.id" :value="loc.id">
                {{ loc.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Data ridicare -->
        <div class="pickup-items">
          <label class="field-label">Data ridicare</label>
          <div class="input-group date">
            <input class="form-control" type="date" v-model="pickupDate">
            <span class="input-group-addon">
            </span>
          </div>
        </div>

        <!-- Data returnare -->
        <div class="pickup-items">
          <label class="field-label">Data returnare</label>
          <div class="input-group date">
            <input class="form-control" type="date" v-model="dropoffDate">
            <span class="input-group-addon">
            </span>
          </div>
        </div>

        <!-- Tip mașină -->
        <div class="pickup-items">
          <label class="field-label tw-mr-4">Tip mașină</label>

          <div class="tw-inline-block tw-w-fit">
            <select v-model.trim="carType"
              class="category tw-inline-block lg:tw-w-48 tw-flex-none tw-truncate tw-rounded-lg tw-border tw-border-gray-300 tw-bg-white tw-py-2 tw-pl-3 tw-pr-8 tw-text-gray-900 focus:tw-border-indigo-500 focus:tw-ring-2 focus:tw-ring-indigo-500">
              <option disabled value="">Selectează un tip</option>
              <option v-for="car in carTypes" :key="car" :value="car" :title="car">
                {{ car }}
              </option>
            </select>
          </div>
        </div>


        <!-- Buton Caută -->
        <div class="pickup-items tw-ml-4">
          <label class="field-label style-2"> </label>
          <button class="pickup-btn" type="button" @click="submitForm">
            Caută
          </button>
        </div>
      </div>

      <!-- Branduri -->
      <div class="brand-wrapper pt-24 pb-24">
        <div class="array-button">
          <button class="array-prev-2"><i class="far fa-chevron-left"></i></button>
          <button class="array-next-2"><i class="far fa-chevron-right"></i></button>
        </div>
        <swiper class="brand-slider" :modules="[Autoplay]" :loop="true" :slides-per-view="3"
          :autoplay="{ delay: 2000 }">
          <swiper-slide v-for="(img, index) in brandImages" :key="index">
            <div class="brand-image">
              <img :src="img" alt="brand" />
            </div>
          </swiper-slide>
        </swiper>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Autoplay } from 'swiper/modules'
import 'swiper/css'

// Props from parent
const props = defineProps({
  rentalTypes: Array,         // [{ id: 1, name: 'Peer-to-peer' }, ...]
  pickupLocations: Array,     // [{ id: 1, name: 'București' }, ...]
  carTypes: Array             // ['Jeep', 'Sedan', ...]
})

// Form values
const rentType = ref('')
const pickupLocation = ref('')
const pickupDate = ref('')
const dropoffDate = ref('')
const carType = ref('')

// Brand images
const brandImages = [
  '/assets/img/brand/01.png',
  '/assets/img/brand/02.png',
  '/assets/img/brand/03.png',
  '/assets/img/brand/04.png',
  '/assets/img/brand/05.png',
  '/assets/img/brand/06.png'
]

// Submit form
function submitForm() {
  router.get(route('car.index'), {
    rentType: rentType.value,
    pickupLocation: pickupLocation.value,
    pickupDate: pickupDate.value,
    dropoffDate: dropoffDate.value,
    carType: carType.value
  })
}
</script>
