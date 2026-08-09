<template>
    <section class="car-service-section">
      <div class="container">
        <div class="car-service-wrapper shadow-xl">
          <div class="shape-image">
            <img src="/assets/img/car/shape-column.png" alt="shape-img" />
          </div>
          <div class="row align-items-center">
            <!-- Left image and booking -->
            <div class="col-lg-6">
              <div class="service-car-image">
                <img :src="imagePath('car-prototype.jpeg')" alt="img" />
                <div class="color-shape">
                  <img src="/assets/img/car/secondary-shape-color.png" alt="shape-img" />
                </div>
                <div class="booking-content">
                  <p>
                    Închiriază o mașină
                  </p>
                  <h3>
                    <a href="tel:9288009850">+40 750 477 517</a>
                  </h3>
                </div>
              </div>
            </div>
  
            <!-- Right content -->
            <div class="col-lg-6 ">
              <div class="service-car-content">
                <h2 class="wow fadeInUp" data-wow-delay=".3s">
                  Cauți o mașină electrică <br />
                  RENTabilă?
                </h2>
                <h3 class="wow fadeInUp" data-wow-delay=".5s">
                  <span>Începând de la</span> <sup>$</sup> 70
                  <span class="text">/zi</span>
                </h3>
                <a href="/" class="theme-btn wow fadeInUp" data-wow-delay=".7s">
                  Începe acum
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  // No JS logic needed for static section; optional props can be added later
  </script>
  
  <style scoped>
  /* Dacă vrei să personalizezi stiluri local, poți adăuga aici */
  </style>
  