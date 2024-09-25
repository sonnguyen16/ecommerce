<script lang="ts" setup>
import MainLayout from "~/layouts/MainLayout.vue";
import {
  UserCircleIcon,
  ClipboardDocumentListIcon,
  ShoppingCartIcon,
   XCircleIcon
} from "@heroicons/vue/24/outline";

const route = useRoute()
const logout = async () => {
  try {
    await useFetchData({url: 'auth/logout', requiresToken: true})
  }catch (e: any){
    console.log(e)
  }finally {
    useCookie('access_token').value = ''
    useCookie('refresh_token').value = ''
    navigateTo('/login')
  }
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
</script>
<template>
    <MainLayout>
      <!-- breadcum -->
      <span class="text-gray-400 font-normal">Trang chủ > Tài khoản</span>
      <div class="grid grid-cols-10 gap-4 mt-3">
        <div class="col-span-2">
          <ul class="space-y-2">
            <li v-for="link in links">
              <NuxtLink :class="[route.path === link.to && 'bg-gray-200', 'flex items-center space-x-3 py-2 px-5 hover:bg-gray-200  rounded-md']" :to="link.to">
                <component :is="link.icon" class="h-6 w-6 text-gray-600" />
                <span class="text-gray-700 font-medium">{{ link.name }}</span>
              </NuxtLink>
            </li>

            <!-- Product Reviews -->
            <li @click.prevent="logout" class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200 rounded-md">
              <XCircleIcon class="h-6 w-6 text-gray-600" />
              <span class="text-gray-700">Đăng xuất</span>
            </li>
          </ul>
        </div>
        <div class="col-span-8">
          <slot/>
        </div>
      </div>
    </MainLayout>
    <div class="bg-white container my-8">
      <HomeFooter />
    </div>
</template>
<style scoped>
* {
  font-weight: 400;
}
</style>
