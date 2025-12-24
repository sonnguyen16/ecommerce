<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Sidebar from "@/Components/Home/Sidebar.vue";
import Carousel from "@/Components/Home/Carousel.vue";
import Product from "@/Components/Product/Product.vue";

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    featuredProducts: {
        type: Array,
        default: () => [],
    },
    newProducts: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <Head title="Trang chủ" />

    <div class="grid grid-cols-6 gap-8">
        <div class="col-span-1 xl:block hidden">
            <Sidebar :categories="categories" :media-url="mediaUrl" />
        </div>
        <div class="xl:col-span-5 col-span-full space-y-5">
            <div class="rounded-xl lg:bg-white lg:p-5">
                <Carousel />
            </div>
            <!--        <div class="rounded-xl bg-white p-4">-->
            <!--          <div class="grid lg:grid-cols-10 gap-4 grid-cols-5">-->
            <!--            <div v-for="category in categories" :key="category.name" class="flex flex-col items-center">-->
            <!--              <img :src="category.img" alt="category" class="w-16 h-16 rounded-xl"/>-->
            <!--              <span class="lg:text-sm text-[10px] text-gray-700">{{ category.name }}</span>-->
            <!--            </div>-->
            <!--          </div>-->
            <!--        </div>-->
            <div class="rounded-xl bg-white p-5 py-4">
                <div class="flex justify-between items-center mb-4">
                    <img src="/topdeal3.png" alt="topdeal" class="w-[200px]" />
                    <span class="text-indigo-700 font-semibold cursor-pointer"
                        >Xem thêm</span
                    >
                </div>
                <div class="flex gap-3 overflow-x-auto lg:grid lg:grid-cols-6">
                    <template
                        v-for="product in featuredProducts"
                        :key="product.id"
                    >
                        <Product
                            class="basis-1/6"
                            :product="product"
                            :media-url="mediaUrl"
                        />
                    </template>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5 py-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="">Hàng mới giá hot</h3>
                    <span class="text-indigo-700 font-semibold cursor-pointer"
                        >Xem thêm</span
                    >
                </div>
                <div class="flex gap-3 overflow-x-auto lg:grid lg:grid-cols-6">
                    <template v-for="product in newProducts" :key="product.id">
                        <Product
                            class="basis-1/6"
                            :product="product"
                            :media-url="mediaUrl"
                        />
                    </template>
                </div>
            </div>
            <div class="rounded-xl bg-white p-5">
                <div
                    class="grid lg:grid-cols-6 grid-cols-3 gap-3 overflow-x-auto scrollb"
                >
                    <template v-for="index in 12" :key="index">
                        <img
                            :src="`/banner${index}.png`"
                            alt="banner"
                            class="rounded-xl"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
