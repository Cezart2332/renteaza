<template>
  <section class="popular-car-section fix section-padding">
    <div class="container">
      <div class="section-title text-center">
        <!-- <img src="/assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp" />
        <span class="wow fadeInUp" data-wow-delay=".2s">select car types</span> -->
        <h2 class="wow fadeInUp" data-wow-delay=".4s">
          Pas cu pas spre <br />
          RENTează
        </h2>
      </div>
      <div class="row g-4 mt-30">
        <div v-for="(step, index) in steps" :key="index" class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp"
          :data-wow-delay="step.delay">
          <div
            class="tw-flex tw-flex-col tw-gap-2 tw-items-center tw-justify-center tw-bg-white tw-rounded-lg tw-shadow-xl tw-border tw-py-3">
            <component :is="step.icon" class="tw-w-14 tw-text-[#FF3726]" />
            <p class="tw-font-semibold tw-text-[24px]">{{ step.title }}</p>
            <p>{{ step.detail }}</p>
          </div>
        </div>
      </div>
      <div class="popular-car-text wow fadeInUp" data-wow-delay=".4s">
        <h6>
          Începe să conduci sau să câștigi bani cu mașina ta
        </h6>
        <a href="car-details.php" class="theme-btn">
          Începe acum
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
import CarIcon from '@/Components/CarIcon.vue';
import { CalendarIcon, FingerPrintIcon, ShieldCheckIcon, UserIcon, UserPlusIcon } from '@heroicons/vue/24/outline';
import CalendarCheckIcon from './CalendarCheckIcon.vue';
import DigitalContractIcon from './DigitalContractIcon.vue';
import WalletIcon from './WalletIcon.vue';

const steps = [
  {
    title: 'Creează cont gratuit',
    detail: 'Înscriete-te pe platformă cu emailul tău',
    delay: '.3s',
    icon: UserPlusIcon,
  },
  {
    title: 'Verifică-ți identitatea',
    detail: 'Incarcă documentele necesare',
    delay: '.5s',
    icon: FingerPrintIcon
  },
  {
    detail: 'Fie închiriezi, fie listezi o mașină în platformă',
    title: 'Caută sau listează o mașină',
    delay: '.7s',
    icon: CarIcon
  },
  {
    detail: 'Clientul face rezervare, proprietarul o aprobă',
    title: 'Rezervă sau acceptă cererea',
    delay: '.3s',
    icon: CalendarCheckIcon
  },
  {
    title: 'Semnează digital documentele',
    detail: 'Automat in platformă. fără printare',
    icon: DigitalContractIcon,
    delay: '.5s',
  },
  {
    title: 'Conducti sau câștigi',
    detail: 'Fie conduci mașina, fie câștigi bani',
    icon: WalletIcon,
    delay: '.7s',
  },
];
</script>

<style scoped>
/* Add scoped styles if needed */
</style>