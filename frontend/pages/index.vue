<template>
  <div class="grid grid-cols-6 gap-8">
    <div class="col-span-1 xl:block hidden">
      <HomeSidebar />
    </div>
    <div class="xl:col-span-5 col-span-full space-y-5">
      <div class="rounded-xl lg:bg-white lg:p-5">
        <HomeCarousel />
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
          <span class="text-indigo-700 font-semibold">Xem thêm</span>
        </div>
        <div class="flex gap-3 overflow-x-auto lg:grid lg:grid-cols-6">
          <template v-for="product in featuredProducts">
            <Product class="basis-1/6" :product="product" />
          </template>
        </div>
      </div>
      <div class="rounded-xl bg-white p-5 py-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="">Hàng mới giá hot</h3>
          <span class="text-indigo-700 font-semibold">Xem thêm</span>
        </div>
        <div class="flex gap-3 overflow-x-auto lg:grid lg:grid-cols-6">
          <template v-for="product in newProducts">
            <Product class="basis-1/6" :product="product" />
          </template>
        </div>
      </div>
      <div class="rounded-xl bg-white p-5">
        <div class="grid lg:grid-cols-6 grid-cols-3 gap-3 overflow-x-auto scrollb">
          <template v-for="index in 12">
            <img :src="`/banner${index}.png`" alt="banner" class="rounded-xl" />
          </template>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import type { Product } from '~/lib/schema'

definePageMeta({
  layout: 'main'
})

// Lấy 12 sản phẩm mới nhất, chia thành 2 nhóm, mỗi nhóm 6 sản phẩm
const featuredProducts = ref<Product[]>([])
const newProducts = ref<Product[]>([])

// Lấy sản phẩm mới nhất
const fetchLatestProducts = async () => {
  const { data, error } = await useClientFetch<Product[]>(`products?limit=12&orderBy=created_at&orderDirection=desc`)

  if (!error.value && data.value) {
    // Chia 12 sản phẩm thành 2 nhóm, mỗi nhóm 6 sản phẩm
    featuredProducts.value = data.value.slice(0, 6)
    newProducts.value = data.value.slice(6, 12)
  }
}

// Lấy dữ liệu ban đầu khi component được tạo
onMounted(async () => {
  await fetchLatestProducts()
})
</script>
