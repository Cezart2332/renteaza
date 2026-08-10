<template>
  <footer class="footer-section fix">
    <div class="container">
      <div class="footer-widgets-wrapper">
        <div class="row justify-content-between">
          <!-- Contact Info -->
          <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
            <div class="single-footer-widget shape-map">
              <div class="widget-head">
                <h4>Contact</h4>
              </div>
              <div class="footer-content">
                <p>RENTeaza Studio București Romania</p>
                <ul class="contact-info">
                  <li>
                    <i class="fa-regular fa-envelope"></i>
                    <a href="mailto:needhelp@company.com">renteaza@gmail.com</a>
                  </li>
                  <li>
                    <i class="fa-solid fa-phone-volume"></i>
                    <a href="tel:+40750477517">+40 750 477 517</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Links -->
          <div class="col-xl-2 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".4s">
            <div class="single-footer-widget">
              <div class="widget-head">
                <h4>Contact</h4>
              </div>
              <ul class="list-items">
                <!-- <li><inertia-link :href="route('about')">Despre</inertia-link></li> -->
                <li><inertia-link :href="route('car.index')">Mașini noi</inertia-link></li>
                <!-- <li><inertia-link :href="route('news-details')">Știri recente</inertia-link></li>
                <li><inertia-link :href="route('gallery')">Galerie</inertia-link></li> -->
                <li><inertia-link :href="route('contact')">Contact</inertia-link></li>
              </ul>
            </div>
          </div>

          <!-- Gallery -->
          <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".6s">
            <div class="single-footer-widget">
              <div class="widget-head">
                <h4>Gallery</h4>
              </div>
              <div class="footer-gallery">
                <div class="gallery-wrap">
                  <div class="gallery-item" v-for="i in 2" :key="'gallery-col-' + i">
                    <div class="thumb" v-for="j in 3" :key="'gallery-img-' + (i - 1) * 3 + j">
                      <a :href="`/assets/img/footer/gallery-${(i - 1) * 3 + j}.jpg`" class="img-popup">
                        <img :src="`assets/img/footer/gallery-${(i - 1) * 3 + j}.jpg`" alt="gallery-img" />
                        <div class="icon"><i class="far fa-plus"></i></div>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Newsletter -->
          <!-- <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".8s">
            <div class="single-footer-widget">
              <div class="widget-head">
                <h4>Newsletter</h4>
              </div>
              <div class="footer-content">
                <p>Subscribe our newsletter to get our latest update & news</p>
                <div class="footer-input">
                  <input type="email" placeholder="Email address" v-model="email" />
                  <button class="newsletter-btn" @click="submitNewsletter">
                    <i class="fa-regular fa-paper-plane"></i>
                  </button>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      </div>

      <!-- Bottom -->
      <div class="footer-bottom">
        <div class="footer-wrapper">
          <p class="wow fadeInUp" data-wow-delay=".4s">
            © Copyright 2025 by <a href="/">renteaza.ro</a>
          </p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>

</script>