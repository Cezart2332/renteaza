<template>
    <section class="team-section fix section-padding pt-0">
      <div class="container">
        <div class="section-title text-center">
          <img src="/assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp" />
          <span class="wow fadeInUp" data-wow-delay=".2s">Our expert people</span>
          <h2 class="wow fadeInUp" data-wow-delay=".4s">
            Meet Our Professional <br />
            Team Members
          </h2>
        </div>
        <div class="row">
          <div
            class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp"
            v-for="(member, index) in teamMembers"
            :key="index"
            :data-wow-delay="member.delay"
          >
            <div class="team-card-items">
              <div class="border-shape">
                <img src="/assets/img/team/border.png" alt="img" />
              </div>
              <div class="team-image">
                <img :src="member.image" :alt="member.name" />
              </div>
              <div class="team-content">
                <h3><a href="/about">{{ member.name }}</a></h3>
                <p>{{ member.role }}</p>
                <div class="social-icon d-flex align-items-center justify-content-center">
                  <a v-for="(icon, idx) in member.socials" :key="idx" :href="icon.link" target="_blank" rel="noopener">
                    <i :class="icon.iconClass"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const teamMembers = [
    {
      name: 'Mike Hardson',
      role: 'Manager',
      image: '/assets/img/team/team-01.jpg',
      delay: '.3s',
      socials: [
        { iconClass: 'fab fa-twitter', link: '#' },
        { iconClass: 'fab fa-facebook-f', link: '#' },
        { iconClass: 'fab fa-youtube', link: '#' },
        { iconClass: 'fab fa-linkedin-in', link: '#' },
      ],
    },
    {
      name: 'Aleesha Brown',
      role: 'Manager',
      image: '/assets/img/team/team-02.jpg',
      delay: '.5s',
      socials: [
        { iconClass: 'fab fa-twitter', link: '#' },
        { iconClass: 'fab fa-facebook-f', link: '#' },
        { iconClass: 'fab fa-youtube', link: '#' },
        { iconClass: 'fab fa-linkedin-in', link: '#' },
      ],
    },
    {
      name: 'David Jhonson',
      role: 'Manager',
      image: '/assets/img/team/team-03.jpg',
      delay: '.7s',
      socials: [
        { iconClass: 'fab fa-twitter', link: '#' },
        { iconClass: 'fab fa-facebook-f', link: '#' },
        { iconClass: 'fab fa-youtube', link: '#' },
        { iconClass: 'fab fa-linkedin-in', link: '#' },
      ],
    },
  ]
  </script>
  