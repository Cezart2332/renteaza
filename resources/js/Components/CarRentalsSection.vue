<template>
  <!-- Car Rentals Section Start -->
  <section class="car-rentals-section section-padding fix">
    <div class="container">
      <div class="section-title text-center">
        <img src="assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp">
        <span class="wow fadeInUp" data-wow-delay=".2s">
          Închiriază o mașină
        </span>
        <h2 class="wow fadeInUp" data-wow-delay=".4s">
          Închiriază o mașină <br />
          cu RENTează
        </h2>
      </div>
    </div>
    <div class="car-rentals-wrapper">
      <div class="array-button">
        <button class="array-prev"><i class="far fa-chevron-left"></i></button>
        <button class="array-next"><i class="far fa-chevron-right"></i></button>
      </div>
      <div class="swiper car-rentals-slider">
        <div class="swiper-wrapper">
          <div v-for="vehicle in vehicles" class="swiper-slide">
            <div class="car-rentals-items">
              <div class="car-image">
                <img :src="imageFromAwsPublic(vehicle.cover_image)" />
              </div>
              <div class="car-content">
                <div class="post-cat">
                  2024 Model
                </div>
                <div class="star">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <span>2 Reviews</span>
                </div>
                <h4><a href="car-details.php">{{ vehicle.brand }} {{ vehicle.model }}</a></h4>
                <h6>€{{ vehicle.price_per_day }} <span>/ Zi</span></h6>
                <div class="icon-items">
                  <ul>
                    <li>
                      <img src="assets/img/car/seat.svg" alt="img" class="me-1">
                      {{ vehicle.seats }} Locuri
                    </li>
                    <li>
                      <img src="assets/img/car/door.svg" alt="img" class="me-1">
                      {{ vehicle.doors }} Uși
                    </li>
                  </ul>
                  <ul>
                    <li>
                      <img src="assets/img/car/automatic.svg" alt="img" class="me-1">
                      {{ vehicle.transmission.name }}
                    </li>
                    <li>
                      <img src="assets/img/car/petrol.svg" alt="img" class="me-1">
                      {{ vehicle.fuel_type.name }}
                    </li>
                  </ul>
                </div>
                <inertia-link :href="route('car.show', vehicle.slug)" class="theme-btn bg-color w-100 text-center">
                  Închiriază
                  <i class="fa-solid fa-arrow-right ps-1"></i></inertia-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>

const props = defineProps({
  vehicles: Array,
});
</script>

<style scoped>
/* Include your scoped CSS here or rely on the global CSS */
</style>