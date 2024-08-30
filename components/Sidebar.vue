a<template>
  <div class="rounded-xl bg-white px-2 py-3">
    <h4 class="font-semibold ms-3">Danh mục</h4>
    <ul class="mt-3">
      <li v-for="category in multipliedCategories" :key="category.name" class="flex items-center p-2 gap-2">
        <img :src="category.img" alt="category" class="w-8 h-8 rounded-xl"/>
        <span class="font-normal">{{ category.name }}</span>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">

let categories = [
  {
    img: "/sach.png",
    name: "Nhà sách tiki"
  },
  {
    img: "/cook.png",
    name: "Nhà cửa đời sống"
  },
  {
    img: "/mobile.png",
    name: "Điện thoại - Máy tính bảng"
  },
  {
    img: "/toy.png",
    name: "Đồ chơi - Mẹ & bé"
  },
]

let multipliedCategories: any = [];

for (let i = 0; i < 12; i++) {
  multipliedCategories = multipliedCategories.concat(categories);
}


</script>

<style scoped>

</style>