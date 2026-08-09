<template>
    <AppLayout>
        <section class="faq-section-2 fix section-padding">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-4">
                        <div class="faq-sidebar">
                            <div class="search-widget">
                                <!-- <form action="#">
                                    <input type="text" placeholder="Search" />
                                    <button type="submit">
                                        <i class="fa-regular fa-magnifying-glass"></i>
                                    </button>
                                </form> -->
                            </div>
                            <h3>Încă ai întrebări</h3><br />
                            <h3>Sună acum!</h3>
                            <div class="icon-items">
                                <div class="icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="content">
                                    <p>Sună oricând</p>
                                    <h6>
                                        <span>+40 750 477 517</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="faq-content">
                            <div class="section-title">
                                <img src="/assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp" />
                                <span class="wow fadeInUp" data-wow-delay=".2s">Întrebări frecvente</span>
                                <h2 class="wow fadeInUp" data-wow-delay=".4s">Întrebări & Răspunsuri</h2>
                            </div>

                            <div class="faq-accordion mt-4 mt-md-0">
                                <div class="accordion" id="accordion">
                                    <div v-for="(faq, index) in faqs" :key="index"
                                        class="accordion-item mb-4 wow fadeInUp" :data-wow-delay="faq.delay">
                                        <h5 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                :data-bs-target="`#faq${index}`" data-bs-toggle="collapse"
                                                :aria-controls="`faq${index}`">
                                                {{ faq.question }}
                                            </button>
                                        </h5>
                                        <div :id="`faq${index}`" class="accordion-collapse collapse"
                                            data-bs-parent="#accordion">
                                            <div class="accordion-body">{{ faq.answer }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta-cheap-rental-section">
            <div class="container">
                <div class="cta-cheap-rental">
                    <div class="cta-cheap-rental-left wow fadeInUp" data-wow-delay=".3s">
                        <div class="logo-thumb">
                            <a href="/">
                                <img src="/assets/img/logo/white-logo.svg" alt="logo-img" />
                            </a>
                        </div>
                        <h4 class="text-white">Save big with our cheap car rental</h4>
                    </div>
                    <div class="social-icon d-flex align-items-center wow fadeInUp" data-wow-delay=".5s">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    faqCategories: Array, // List of FAQ categories with their FAQs
});

const faqs = props.faqCategories.flatMap(category => category.faqs.map(faq => ({
    question: faq.question,
    answer: faq.answer,
    delay: '.3s',
})));
</script>

<style scoped>
.accordion-button {
    cursor: pointer;
}
</style>