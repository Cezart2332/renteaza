<template>
    <div>
        <AppLayout>
            <HeroSection />
            <PickUpLocation :rental-types="rentalTypes" :pickup-locations="locations" :carTypes="carTypes" />
            <FeatureBenefit />
            <!-- <AboutSection /> -->
            <CarRentalsSection :vehicles="vehicles" />
            <CarService />
            <FunfactSection />
            <PopularCarSection />
            <!-- <TestimonialSection /> -->
            <!-- <GallerySection /> -->
            <!-- <NewsSection /> -->
            <!-- <CtaRentalSection /> -->
            <CtaCheapRentalSection />
        </AppLayout>
    </div>
</template>

<script setup>
import Preloader from "@/Components/Preloader.vue";
import ScrollUp from "@/Components/ScrollUp.vue";
import HeaderTop from "@/Components/HeaderTop.vue";
import Header from "@/Components/Header.vue";
import SearchWrap from "@/Components/SearchWrap.vue";

import { ref } from "vue";
import Footer from "@/Components/Footer.vue";
import HeroSection from "@/Components/HeroSection.vue";
import PickUpLocation from "@/Components/PickUpLocation.vue";
import FeatureBenefit from "@/Components/FeatureBenefit.vue";
import AboutSection from "@/Components/AboutSection.vue";
import CarRentalsSection from "@/Components/CarRentalsSection.vue";
import CarService from "@/Components/CarService.vue";
import FunfactSection from "@/Components/FunfactSection.vue";
import PopularCarSection from "@/Components/PopularCarSection.vue";
import TestimonialSection from "@/Components/TestimonialSection.vue";
import GallerySection from "@/Components/GallerySection.vue";
import NewsSection from "@/Components/NewsSection.vue";
import CtaRentalSection from "@/Components/CtaRentalSection.vue";
import CtaCheapRentalSection from "@/Components/CtaCheapRentalSection.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    rentalTypes: Array,
    locations: Array,
    carTypes: Array,
    vehicles: Array, // List of vehicles to be displayed
});
</script>
