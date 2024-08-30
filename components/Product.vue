<script setup lang="ts">
import {StarIcon} from "@heroicons/vue/24/solid";
import {ref} from "vue";

interface Product {
  img: string;
  name: string;
}

const props = defineProps<{
  product: Product;
}>();

let mounted = ref(false);

onMounted(() => {
  mounted.value = true;
});

</script>

<template>
  <NuxtLink to="/detail">
    <div class="rounded-xl border border-gray-200 hover:shadow-lg">
      <img :src="product.img" alt="product" class="w-full rounded-tl-xl rounded-tr-xl">
      <img :key="product.img" src="/topdeal2.png" alt="topdeal" class="lg:w-[80px] w-[40px] lg:block inline-block ms-2 mt-2">
      <img :key="product.img" src="/chinhhang.png" alt="chinhhang" class="lg:w-[90px] w-[50px] lg:block inline-block ms-2 mt-2">
      <div class="px-3 py-2">
        <p class="font-normal line-clamp-2 lg:text-[14px] text-sm">{{ product.name }}</p>
        <div class="flex mt-1">
          <template v-for="i in 5">
            <StarIcon class="w-4 h-4 text-yellow-300"/>
          </template>
        </div>
        <span class="text-red-500 font-semibold">108.000đ</span>
        <div class="flex gap-2">
          <span class="block p-1 rounded-lg bg-gray-100 font-normal text-sm">-20%</span>
          <span class="text-gray-400 line-through font-normal lg:text-[14px] text-sm">135.000đ</span>
        </div>
        <div class="w-full h-[1px] bg-gray-200 mt-6"></div>
        <div class="flex gap-2 items-center mt-2">
          <img src="/now.png" alt="topdeal" class="w-[30px] lg:block hidden">
          <span class="font-normal text-gray-600 text-sm">Giao siêu tốc 2h</span>
        </div>
      </div>
    </div>
  </NuxtLink>
</template>

<style scoped>

</style>