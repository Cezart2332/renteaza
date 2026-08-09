<template>
    <section class="testimonial-section fix section-padding">
      <div class="testimonial-bg-shape">
        <img src="/assets/img/testimonial/testimonial-bg.jpg" alt="shape-img" />
      </div>
      <div class="container">
        <div class="section-title-area">
          <div class="section-title">
            <img src="/assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp" />
            <span class="wow fadeInUp" data-wow-delay=".2s">our testimonials</span>
            <h2 class="wow fadeInUp" data-wow-delay=".4s">
              What They’re Talking <br />
              About Remons
            </h2>
          </div>
          <p class="wow fadeInUp" data-wow-delay=".5s">
            Lorem ipsum dolor sit amet nsectetur cing elituspe ndisse suscipit <br />
            sagitis leo sit.
          </p>
        </div>
        <div class="swiper testimonial-slider">
          <div class="swiper-wrapper">
            <div class="swiper-slide" v-for="(testimonial, index) in testimonials" :key="index">
              <div class="testimonial-card-items">
                <div class="testimoni-bg-shape">
                  <div class="testimonial-items-top">
                    <div class="icon">
                      <i class="fa-solid fa-quote-left"></i>
                    </div>
                    <p>{{ testimonial.text }}</p>
                    <div class="star">
                      <i v-for="i in 5" :key="i" class="fa-solid fa-star"></i>
                      <span>{{ testimonial.reviews }} Reviews</span>
                    </div>
                  </div>
                </div>
                <div class="client-info-items d-flex align-items-center gap-3">
                  <div
                    class="client-img bg-cover"
                    :style="{ backgroundImage: `url(${testimonial.image})` }"
                  ></div>
                  <div class="content">
                    <h5>{{ testimonial.name }}</h5>
                    <span>{{ testimonial.role }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: 'TestimonialSection',
    data() {
      return {
        testimonials: [
          {
            name: 'Jessica Brown',
            role: 'Customer',
            image: '/assets/img/testimonial/client-1.png',
            reviews: 2,
            text:
              'I was very impressed by the remons service lorem ipsum is simply free text used by copy typing refreshing. Neque porro est qui dolorem ipsum quia.',
          },
          {
            name: 'Kevin Martin',
            role: 'Customer',
            image: '/assets/img/testimonial/client-2.png',
            reviews: 2,
            text:
              'I was very impressed by the remons service lorem ipsum is simply free text used by copy typing refreshing. Neque porro est qui dolorem ipsum quia.',
          },
          {
            name: 'Jessica Brown',
            role: 'Customer',
            image: '/assets/img/testimonial/client-3.png',
            reviews: 2,
            text:
              'I was very impressed by the remons service lorem ipsum is simply free text used by copy typing refreshing. Neque porro est qui dolorem ipsum quia.',
          },
        ],
      };
    },
  };
  </script>