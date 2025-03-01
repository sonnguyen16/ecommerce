<script setup lang="ts">
import { HomeIcon } from '@heroicons/vue/24/solid'
import { FaceSmileIcon } from '@heroicons/vue/24/outline'
import { ShoppingCartIcon } from '@heroicons/vue/24/outline'
import { MapPinIcon } from '@heroicons/vue/24/outline'
import { CheckBadgeIcon } from '@heroicons/vue/24/solid'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'

const route = useRoute()

const links = [
  {
    name: 'Trang chủ',
    icon: HomeIcon,
    to: '/'
  },
  {
    name: 'Tài khoản',
    icon: FaceSmileIcon,
    to: '/profile'
  },
  {
    name: 'Giỏ hàng',
    icon: ShoppingCartIcon,
    to: '/cart'
  }
]
</script>

<template>
  <nav class="py-[10px] border-b lg:block hidden">
    <div class="container flex justify-between items-center">
      <NuxtLink to="/">
        <img class="w-[200px]" src="/logo.png" alt="Logo" />
      </NuxtLink>
      <div class="flex-1 mx-20">
        <div class="relative">
          <span class="absolute top-1/2 left-3 -translate-y-1/2 flex text-gray-700 font-normal">
            <MagnifyingGlassIcon class="w-6 h-6 me-1" />
            Tìm kiếm sản phẩm
          </span>
          <input class="rounded-lg border-gray-300 w-full focus:border-blue-400" type="text" id="search" />
        </div>
        <ul class="flex gap-3 mt-2">
          <li class="text-gray-700 font-normal">điện gia dụng</li>
          <li class="text-gray-700 font-normal">điện thoại</li>
          <li class="text-gray-700 font-normal">máy tính</li>
          <li class="text-gray-700 font-normal">thời trang</li>
        </ul>
      </div>
      <div>
        <ul class="flex gap-6 items-center">
          <template v-for="link in links">
            <li>
              <NuxtLink
                :class="[route.path === link.to ? 'text-blue-700' : 'text-gray-500']"
                :to="link.to"
                class="flex justify-center items-center space-x-1"
              >
                <component :is="link.icon" class="w-6 h-6" />
                <span class="">{{ link.name }}</span>
              </NuxtLink>
            </li>
          </template>
        </ul>
        <div class="mt-4">
          <NuxtLink to="/" class="flex space-x-1">
            <MapPinIcon class="w-6 h-6 text-gray-500" />
            <span class="text-gray-500">Giao đến: <span class="text-black underline">HCM, Việt Nam</span></span>
          </NuxtLink>
        </div>
      </div>
    </div>
  </nav>
  <div class="py-[10px] border-b lg:block hidden">
    <div class="container flex justify-between items-center">
      <ul class="flex gap-5 items-center">
        <li class="text-blue-700 font-bold">Cam kết</li>
        <li>
          <div class="w-[1px] h-[20px] bg-gray-300"></div>
        </li>
        <li class="flex gap-2">
          <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
          <span class="font-normal">100% hàng thật</span>
        </li>
        <li>
          <div class="w-[1px] h-[20px] bg-gray-300"></div>
        </li>
        <li class="flex gap-2">
          <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
          <span class="font-normal">Hoàn 200% nếu hàng giả</span>
        </li>
        <li>
          <div class="w-[1px] h-[20px] bg-gray-300"></div>
        </li>
        <li class="flex gap-2">
          <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
          <span class="font-normal">30 ngày đổi trả</span>
        </li>
        <li>
          <div class="w-[1px] h-[20px] bg-gray-300"></div>
        </li>
        <li class="flex gap-2">
          <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
          <span class="font-normal">Giao nhanh 2h</span>
        </li>
        <li>
          <div class="w-[1px] h-[20px] bg-gray-300"></div>
        </li>
        <li class="flex gap-2">
          <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
          <span class="font-normal">Giá siêu rẻ</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<style scoped></style>
