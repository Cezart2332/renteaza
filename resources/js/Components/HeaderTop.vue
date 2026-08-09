<template>
  <!-- tw-hidden < lg, bg + tw-border theme2, paddings responsive -->
  <div
    class="tw-hidden lg:tw-block tw-relative z-[9] tw-bg-[var(--theme2)] tw-border-t-[3px] tw-border-t-[var(--theme2)] tw-py-[10px] tw-px-5 xl:px-[60px]">
    <!-- „Pană” albă (înlocuiește ::before) – doar pe ecrane foarte late -->
    <div
      class="tw-absolute tw-inset-y-0 tw-left-0 tw-hidden 2xl:tw-block tw-w-[285px] tw-bg-white [clip-path:polygon(0_0,90%_0,100%_100%,0_100%)]"
      aria-hidden="true"></div>

    <div class="tw-relative tw-z-10 tw-max-w-screen-2xl tw-ml-64 tw-flex tw-items-center tw-justify-between tw-gap-6">
      <!-- stânga -->
      <ul class="tw-flex tw-items-center tw-gap-3 tw-text-white tw-text-sm">
        <li class="tw-flex tw-items-center tw-gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
          </svg>
          <a href="mailto:renteaza@gmail.com" class="tw-text-white">renteaza@gmail.com</a>
        </li>
      </ul>

      <!-- dreapta -->
      <div class="tw-flex tw-items-center tw-gap-6">
        <ul class="tw-flex tw-items-center tw-gap-2 text-white/80 tw-text-sm">
          <li><a href="/contact" class="text-white/80 hover:tw-text-white">Ajutor</a></li>
          <li class="tw-opacity-50">/</li>
          <li><a href="/contact" class="text-white/80 hover:tw-text-white">Suport</a></li>
          <li class="tw-opacity-50">/</li>
          <li><a href="/contact" class="text-white/80 hover:tw-text-white">Contact</a></li>
        </ul>

        <div class="tw-flex tw-items-center tw-gap-3">
          <a href="#" class="text-white/80 hover:tw-text-white"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-white/80 hover:tw-text-white"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="text-white/80 hover:tw-text-white"><i class="fa-brands fa-instagram"></i></a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// no JS needed
</script>
