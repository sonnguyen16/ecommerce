<script setup>
import { ref, onMounted } from "vue";

const isMobile = ref(false);
const currentSlide = ref(0);
const isAutoPlaying = ref(true);
let autoPlayInterval = null;

// Desktop slides
const desktopSlides = [
    { id: 1, images: ["/phu_minh_quang.jpg", "/phu_minh_quang_2.jpg"] },
    { id: 2, images: ["/phu_minh_quang.jpg", "/phu_minh_quang_2.jpg"] },
];

// Mobile slides
const mobileSlides = [
    { id: 3, image: "/slide.jpg" },
    { id: 4, image: "/slide1.jpg" },
    { id: 5, image: "/slide2.jpg" },
    { id: 6, image: "/slide3.jpg" },
];

const slides = ref([]);

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length;
};

const prevSlide = () => {
    currentSlide.value =
        (currentSlide.value - 1 + slides.value.length) % slides.value.length;
};

const startAutoPlay = () => {
    if (autoPlayInterval) clearInterval(autoPlayInterval);
    autoPlayInterval = setInterval(() => {
        if (isAutoPlaying.value) {
            nextSlide();
        }
    }, 3000);
};

onMounted(() => {
    isMobile.value = window.innerWidth < 1024;
    slides.value = isMobile.value ? mobileSlides : desktopSlides;
    startAutoPlay();
});
</script>

<template>
    <div class="relative w-full" data-carousel="slide">
        <!-- Carousel wrapper - Desktop -->
        <div
            v-if="!isMobile"
            class="relative lg:block hidden overflow-hidden rounded-lg"
        >
            <template v-for="(slide, index) in desktopSlides" :key="slide.id">
                <div
                    :class="[
                        currentSlide === index
                            ? 'opacity-100'
                            : 'opacity-0 absolute inset-0',
                    ]"
                    class="duration-500 ease-in-out transition-opacity"
                >
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <img
                                :src="slide.images[0]"
                                class="block rounded-xl w-full h-auto object-cover"
                                alt="..."
                            />
                        </div>
                        <div class="col-span-1">
                            <img
                                :src="slide.images[1]"
                                class="block rounded-xl w-full h-auto object-cover"
                                alt="..."
                            />
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Carousel wrapper - Mobile -->
        <div
            v-if="isMobile"
            class="relative md:h-72 h-56 lg:hidden block overflow-hidden rounded-lg"
        >
            <template v-for="(slide, index) in mobileSlides" :key="slide.id">
                <div
                    :class="[
                        currentSlide === index
                            ? 'opacity-100'
                            : 'opacity-0 absolute inset-0',
                    ]"
                    class="duration-500 ease-in-out transition-opacity"
                >
                    <img
                        :src="slide.image"
                        class="block rounded-xl w-full h-full object-cover"
                        alt="..."
                    />
                </div>
            </template>
        </div>

        <!-- Slider controls -->
        <button
            aria-label="prev"
            @click="prevSlide"
            type="button"
            class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        >
            <span
                class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/90"
            >
                <svg
                    class="w-5 h-5 text-blue-600 sm:w-6 sm:h-6 dark:text-gray-800"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    ></path>
                </svg>
                <span class="hidden">Previous</span>
            </span>
        </button>
        <button
            aria-label="next"
            @click="nextSlide"
            type="button"
            class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
        >
            <span
                class="inline-flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/90"
            >
                <svg
                    class="w-5 h-5 text-blue-600 sm:w-6 sm:h-6 dark:text-gray-800"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                    ></path>
                </svg>
                <span class="hidden">Next</span>
            </span>
        </button>

        <!-- Indicators -->
        <div
            class="absolute z-30 flex space-x-2 -translate-x-1/2 bottom-4 left-1/2"
        >
            <button
                v-for="(_, index) in slides"
                :key="index"
                @click="currentSlide = index"
                :class="[
                    currentSlide === index ? 'bg-blue-600' : 'bg-white/50',
                ]"
                class="w-2.5 h-2.5 rounded-full transition-colors"
                :aria-label="`Slide ${index + 1}`"
            ></button>
        </div>
    </div>
</template>
