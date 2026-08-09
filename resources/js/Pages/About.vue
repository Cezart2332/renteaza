<script setup lang="ts">
import AboutStart from "@/Components/About/AboutStart.vue";
import BrandSection from "@/Components/About/BrandSection.vue";
import CarSaleSection from "@/Components/About/CarSaleSection.vue";
import CtaCheapRentalAbout from "@/Components/About/CtaCheapRentalAbout.vue";
import FunFactSection from "@/Components/About/FunFactSection.vue";
import TeamSection from "@/Components/About/TeamSection.vue";
import TestimonialAboutSection from "@/Components/About/TestimonialAboutSection.vue";

import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout>
        <AboutStart />
        <TeamSection/>
        <FunFactSection/>
        <TestimonialAboutSection/>
        <CarSaleSection/>
        <BrandSection/>
        <CtaCheapRentalAbout/>
    </AppLayout>
</template>
