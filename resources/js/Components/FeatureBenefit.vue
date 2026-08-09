<template>
  <section class="feature-benefit section section-padding fix">
    <div class="container">
      <div class="section-title text-center flex flex-col items-center justify-center">
        <!-- <img src="/assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp" />
          <span class="wow fadeInUp" data-wow-delay=".2s">our benefits</span> -->
        <h2 class="wow fadeInUp" data-wow-delay=".4s">
          De ce să alegi <br />
          RENTează.ro
        </h2>
      </div>
      <div class="row">
        <div v-for="(item, index) in features" :key="index" class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp"
          :data-wow-delay="item.delay">
          <div class="feature-benefit-items">
            <div class="icon-box-shape">
              <img :src="item.boxIcon" alt="shape-img" />
            </div>
            <div class="bg-button-shape">
              <img src="/assets/img/feature-benefit/bg-button-iconbox.png" alt="shape-img" />
            </div>
            <div class="feature-content">
              <h4>
                <a href="/car-details">
                  {{ item.titleLine1 }} <br />
                  {{ item.titleLine2 }}
                </a>
              </h4>
              <p>{{ item.description }}</p>
              <div class="icon tw-flex tw-items-center tw-justify-center">
                <component :is="item.icon" class="text-white" />
              </div>
            </div>
            <div class="feature-button">
              <a href="/car-details" class="link-btn">
                {{ item.cta }} <i class="fa-solid fa-arrow-right ps-1"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import LaptopCheckIcon from './LaptopCheckIcon.vue';
import MapPinned from './MapPinned.vue';
import UserLockIcon from './UserLockIcon.vue';

const features = [
  {
    icon: LaptopCheckIcon,
    titleLine1: 'Rezervări rapide',
    titleLine2: '100% online',
    description: 'Închiriezi sau listezi o mașină în câteva minute, fără apeluri sau contracte fizice.',
    delay: '.3s',
    cta: 'Cum funcționează'
  },
  {
    icon: MapPinned,
    titleLine1: 'Flexibilitate totală',
    titleLine2: 'fără sediu',
    description: 'Alegi când, unde și cât timp folosești sau oferi un vehicul. Totul digital.',
    delay: '.5s',
    cta: 'Vezi opțiunile'
  },
  {
    icon: UserLockIcon,
    titleLine1: 'Comunitate sigură',
    titleLine2: '100% verificată',
    description: 'Toți utilizatorii sunt validați. Documente, plăți și contracte protejate de platformă.',
    delay: '.7s',
    cta: 'Siguranță'
  },
];
</script>

<style scoped>
/* Adaugă stiluri personalizate dacă ai nevoie */
</style>