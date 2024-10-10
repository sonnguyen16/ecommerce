<script setup lang="ts">
import {
  FaceSmileIcon,
  ShoppingCartIcon,
  HomeIcon,
  Bars3Icon
} from "@heroicons/vue/24/outline";


const links = [
  {
    name: "Trang chủ",
    icon: HomeIcon,
    link: "/"
  },
  {
    name: "Giỏ hàng",
    icon: ShoppingCartIcon,
    link: "/cart"
  },
  {
    name: "Tài khoản",
    icon: FaceSmileIcon,
    link: "/profile"
  }
]

const emits = defineEmits(['showCategories'])

</script>

<template>
  <div id="mobile-navigation" class="md:hidden block fixed bottom-0 w-full h-[50px] bg-white border-t">
    <div class="flex justify-between items-center h-full text-gray-700 px-5">
      <template v-for="link in links">
        <nuxt-link :to="link.link" :class="[ useRoute().path === link.link ? 'text-indigo-700' : 'text-gray-700' ]" class="flex flex-col justify-center items-center">
          <component :is="link.icon" class="w-6 h-6"/>
          <span class="text-xs">{{ link.name }}</span>
        </nuxt-link>
      </template>
      <button @click.prevent="emits('showCategories')" class="flex flex-col justify-center items-center">
        <component :is="Bars3Icon" class="w-6 h-6"/>
        <span class="text-xs">Danh mục</span>
      </button>
    </div>
  </div>
</template>
<style scoped>

</style>