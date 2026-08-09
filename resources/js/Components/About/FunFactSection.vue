<template>
    <section
      class="funfact-section section-padding fix bg-cover mt-0"
      style="background-image: url('/assets/img/funfact-bg.jpg')"
    >
      <div class="container">
        <div class="funfact-wrapper mt-0">
          <div class="row g-4 align-items-center">
            <div class="col-lg-6">
              <div class="section-title mb-0">
                <img src="/assets/img/sub-icon-2.png" alt="icon-img" class="wow fadeInUp" />
                <span class="wow fadeInUp" data-wow-delay=".2s">fun facts</span>
                <h2 class="text-white wow fadeInUp" data-wow-delay=".4s">
                  Save Time & Money <br />
                  with Top Car Rent <br />
                  Services
                </h2>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="funfact-counter-area">
                <div
                  v-for="(item, index) in funFacts"
                  :key="index"
                  class="funfact-items wow fadeInUp"
                  :data-wow-delay="item.delay"
                >
                  <div class="icon">
                    <img :src="item.icon" alt="img" />
                  </div>
                  <h2><span class="count">{{ item.count }}</span></h2>
                  <p v-html="item.label"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const funFacts = [
    {
      icon: '/assets/img/icon/car.png',
      count: 990,
      label: 'Cars <br> rentouts',
      delay: '.3s',
    },
    {
      icon: '/assets/img/icon/center.png',
      count: 230,
      label: 'Center <br> solutions',
      delay: '.5s',
    },
    {
      icon: '/assets/img/icon/like.png',
      count: 660,
      label: 'happy <br> customers',
      delay: '.7s',
    },
  ];
  </script>
  