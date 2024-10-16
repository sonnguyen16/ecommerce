<script lang="ts" setup>
import {
  UserCircleIcon,
  ClipboardDocumentListIcon,
  ShoppingCartIcon,
   XCircleIcon
} from "@heroicons/vue/24/outline";

const route = useRoute()
const logout = async () => {
  await useClientFetch('logout')
  useAuth().user.value = null
  navigateTo('/signin')
}

const links = [
  {
    name: 'Thông tin tài khoản',
    icon: UserCircleIcon,
    to: '/profile'
  },
  {
    name: 'Quản lý đơn hàng',
    icon: ClipboardDocumentListIcon,
    to: '/profile/tracking'
  },
  {
    name: 'Shop của tôi',
    icon: ShoppingCartIcon,
    to: '/manage/orders'
  }
]

const showCategoryMenu = ref(false)
const showCategories = () => {
  showCategoryMenu.value = !showCategoryMenu.value
}

if(process.client){
  document.addEventListener('click', (e: any) => {
    if(e.target.closest('#mobile-navigation') === null){
      showCategoryMenu.value = false
    }
  })
}
</script>
<template>
  <HomeNavbar />
  <div id="body" class="bg-[#f5f5fa] py-4">
    <div class="container">
      <span class="text-gray-400 font-normal">Trang chủ > Tài khoản</span>
      <div class="grid md:grid-cols-10 gap-4 mt-3">
        <div class="md:block hidden col-span-2">
          <ul class="space-y-2">
            <li v-for="link in links">
              <NuxtLink :class="[route.path === link.to && 'bg-gray-200', 'flex items-center space-x-3 py-2 px-5 hover:bg-gray-200  rounded-md']" :to="link.to">
                <component :is="link.icon" class="h-6 w-6 text-gray-600" />
                <span class="text-gray-700 font-medium">{{ link.name }}</span>
              </NuxtLink>
            </li>
            <li @click.prevent="logout" class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200 rounded-md">
              <XCircleIcon class="h-6 w-6 text-gray-600" />
              <span class="text-gray-700">Đăng xuất</span>
            </li>
          </ul>
        </div>
        <div class="col-span-8">
          <NuxtPage />
        </div>
      </div>
    </div>
  </div>
  <MobileNavigation @show-categories="showCategories" />
  <MobileCategoryMenu :show="showCategoryMenu" />

  <div class="bg-white container my-8">
    <HomeFooter />
  </div>
</template>
<style scoped>
* {
  font-weight: 400 !important;
}
</style>

