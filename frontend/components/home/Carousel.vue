<script setup>

const isMobile = window === undefined ? false : window.innerWidth < 1024;

onMounted(() => {
  useFlowbite(() => {
    const carouselElement = document.getElementById('carousel-example');

    let items = [
      {
        position: 0,
        el: document.getElementById('carousel-item-1')
      },
      {
        position: 1,
        el: document.getElementById('carousel-item-2')
      }
    ];

    if (isMobile) {
      items = [
        {
          position: 0,
          el: document.getElementById('carousel-item-3')
        },
        {
          position: 1,
          el: document.getElementById('carousel-item-4')
        },
        {
          position: 2,
          el: document.getElementById('carousel-item-5')
        },
        {
          position: 3,
          el: document.getElementById('carousel-item-6')
        }
      ];
    }

    const options = {
      defaultPosition: 0,
      interval: 3000,
      // callback functions
      onNext: () => {},
      onPrev: () => {},
      onChange: () => {}
    };


     const carousel = new Carousel(carouselElement, items, options);
     carousel.cycle()
     // set event listeners for prev and next buttons
     const prevButton = document.getElementById('data-carousel-prev');
     const nextButton = document.getElementById('data-carousel-next');
     prevButton.addEventListener('click', () => {
       carousel.prev();
     });
     nextButton.addEventListener('click', () => {
       carousel.next();
     });
   })

})
</script>

<template>
  <div id="carousel-example" class="relative w-full" data-carousel="slide">
      <!-- Carousel wrapper -->
      <div v-if="!isMobile" class="relative lg:block hidden overflow-hidden rounded-lg h-96">
        <div id="carousel-item-1" class="hidden duration-500 ease-in-out" data-carousel-item>
          <div class="grid grid-cols-2 gap-5">
            <div class="col-span-1">
              <img src="/phu_minh_quang.jpg" class="block rounded-xl" alt="...">
            </div>
            <div class="col-span-1">
              <img src="/phu_minh_quang_2.jpg" class="block rounded-xl" alt="...">
            </div>
          </div>
        </div>

        <div id="carousel-item-2" class="hidden duration-500 ease-in-out" data-carousel-item>
          <div class="grid grid-cols-2 gap-5">
            <div class="col-span-1">
              <img src="/phu_minh_quang.jpg" class="block rounded-xl" alt="...">
            </div>
            <div class="col-span-1">
              <img src="/phu_minh_quang_2.jpg" class="block rounded-xl" alt="...">
            </div>
          </div>
        </div>
      </div>

      <div v-if="isMobile" class="relative md:h-72 h-56 lg:hidden block overflow-hidden rounded-lg">
        <div id="carousel-item-3" class="hidden duration-500 ease-in-out" data-carousel-item>
           <img src="/slide.jpg" class="block rounded-xl" alt="...">
        </div>
        <div id="carousel-item-4" class="hidden duration-500 ease-in-out" data-carousel-item>
          <img src="/slide1.jpg" class="block rounded-xl" alt="...">
        </div>
        <div id="carousel-item-5" class="hidden duration-500 ease-in-out" data-carousel-item>
          <img src="/slide2.jpg" class="block rounded-xl" alt="...">
        </div>
        <div id="carousel-item-6" class="hidden duration-500 ease-in-out" data-carousel-item>
          <img src="/slide3.jpg" class="block rounded-xl" alt="...">
        </div>
      </div>

      <!-- Slider controls -->
      <button aria-label="prev" id="data-carousel-prev" type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none">
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/90 ">
              <svg class="w-5 h-5 text-blue-600 sm:w-6 sm:h-6 dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
              <span class="hidden">Previous</span>
          </span>
      </button>
      <button aria-label="next" id="data-carousel-next" type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none">
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10  bg-white/90">
              <svg class="w-5 h-5 text-blue-600 sm:w-6 sm:h-6 dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
              <span class="hidden">Next</span>
          </span>
      </button>
  </div>
</template>