<template>
    <section class="news-section section-padding fix">
      <div class="container">
        <div class="section-title text-center">
          <img src="/assets/img/sub-icon.png" alt="icon-img" class="wow fadeInUp" />
          <span class="wow fadeInUp" data-wow-delay=".2s">From the Blog</span>
          <h2 class="wow fadeInUp" data-wow-delay=".4s">
            Latest News & <br /> Articles From the Blog
          </h2>
        </div>
        <div class="row">
          <div
            class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp"
            v-for="(post, index) in posts"
            :key="index"
            :data-wow-delay="post.delay"
          >
            <div class="news-card-items">
              <div class="news-image">
                <img :src="post.image" alt="news-img" />
                <div class="post-date">
                  <h6>
                    {{ post.date }} <br /> {{ post.month }}
                  </h6>
                </div>
              </div>
              <div class="news-content">
                <div class="post-client">
                  <img src="/assets/img/news/client.png" alt="img" />
                </div>
                <div class="news-cont">
                  <span>by {{ post.author }}</span>
                  <h3><a :href="post.link">{{ post.title }}</a></h3>
                  <p>{{ post.description }}</p>
                </div>
                <ul>
                  <li>
                    <i class="fa-solid fa-comments"></i>
                    {{ post.comments }} Comments
                  </li>
                  <li>
                    <a :href="post.link">
                      <i class="fa-solid fa-arrow-right-long"></i>
                      More
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  const posts = [
    {
      image: '/assets/img/news/01.jpg',
      date: '20',
      month: 'Mar',
      author: 'Mike Hardson',
      title: 'The best fastest and most powerful road car',
      description: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem…',
      comments: 2,
      link: 'news-details.php',
      delay: '.3s',
    },
    {
      image: '/assets/img/news/02.jpg',
      date: '26',
      month: 'Mar',
      author: 'Mike Hardson',
      title: 'The best fastest and most powerful road car',
      description: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem…',
      comments: 2,
      link: 'news-details.php',
      delay: '.5s',
    },
    {
      image: '/assets/img/news/03.jpg',
      date: '29',
      month: 'Mar',
      author: 'Mike Hardson',
      title: 'The best fastest and most powerful road car',
      description: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem…',
      comments: 2,
      link: 'news-details.php',
      delay: '.7s',
    },
  ]
  </script>
  