<template>
    <AppLayout>
        <section class="contact-section-1 fix section-padding pb-0">
            <div class="container">
                <div class="contact-wrapper-area">
                    <div class="row g-4">
                        <div class="col-lg-9">
                            <div class="contact-contentr">
                                <div class="section-title">
                                    <img src="/assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp" />
                                    <span class="wow fadeInUp" data-wow-delay=".2s">contact</span>
                                    <p style="font-weight: 600;
                                        font-size: 2.5rem; margin-top: 10px;
                                        color: black;" class="wow fadeInUp" data-wow-delay=".4s">
                                        Contactează-ne
                                    </p>
                                </div>
                                <form @submit.prevent="submitForm" class="contact-form-items mt-5 mt-md-0">
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-clt">
                                                <input v-model="form.name" type="text" placeholder="Numele tău" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-clt">
                                                <input v-model="form.email" type="email" placeholder="Emailul tău" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-clt">
                                                <input v-model="form.subject" type="text" placeholder="Subiect" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-clt">
                                                <input v-model="form.phone" type="text" placeholder="Telefon" />
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-clt">
                                                <textarea v-model="form.message"
                                                    placeholder="Scrie un mesaj"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <button type="submit" class="theme-btn">Trimite un mesaj</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="contact-right-items">
                                <div class="contact-img">
                                    <img :src="imagePath('car-banner_contact_section.png')" alt="img" />
                                </div>

                                <div class="icon-items" v-for="(item, index) in contactInfo" :key="index">
                                    <div class="icon">
                                        <img :src="item.icon" alt="img" />
                                    </div>
                                    <div class="content">
                                        <p>{{ item.label }}</p>
                                        <p v-if="item.link" style="font-weight: 600;">
                                            <a :href="item.link">{{ item.text }}</a>
                                        </p>
                                        <h6 v-else>{{ item.text }}</h6>
                                    </div>
                                </div>

                                <div class="social-icon d-flex align-items-center">
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="#"><i class="fab fa-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="map-section">
            <div class="map-items">
                <div class="googpemap">
                    <!-- coordonatele biroului, extrase din vechiul embed Google -->
                    <MapboxMap
                        :center="{ lat: 44.4292539, lng: 26.1564889 }"
                        :zoom="15"
                        :markers="[{ id: 'studio', position: { lat: 44.4292539, lng: 26.1564889 }, label: 'RENTeaza Studio' }]"
                        class="tw-w-full tw-h-[420px]"
                    />
                </div>
            </div>
        </div>

        <CtaCheapRentalSection />

        <Notification />
    </AppLayout>
</template>

<script setup>
import CtaCheapRentalSection from '@/Components/CtaCheapRentalSection.vue'
import Notification from '@/Components/Notification.vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import MapboxMap from '@/Components/Map/MapboxMap.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    subject: '',
    phone: '',
    message: '',
})

const contactInfo = [
    {
        icon: '/assets/img/question.png',
        label: 'Ai nevoie de ajutor?',
        text: '+40 750 477 517',
        link: 'tel:+40750477517',
    },
    {
        icon: '/assets/img/email.png',
        label: 'Scrie un email',
        text: 'renteaza@gmail.com',
        link: 'mailto:renteaza@gmail.com',
    },
    {
        icon: '/assets/img/location.png',
        label: 'Vizitează biroul',
        text: 'Renteaza STUDIO\nBucurești, România',
        link: null,
    },
]

function submitForm() {
    form.post(route('contact.store'), {
        onSuccess: () => {
            form.reset()
        },
        onError: (errors) => {
        },
    })
}
</script>