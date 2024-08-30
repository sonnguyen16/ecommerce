<script lang="ts" setup>
import MainLayout from "~/layouts/MainLayout.vue";
import {
  UserCircleIcon,
  ClipboardDocumentListIcon,
  CreditCardIcon,
  StarIcon,
  HeartIcon,
  ChatBubbleBottomCenterIcon
} from "@heroicons/vue/24/outline";
</script>
<template>
  <MainLayout>
    <!-- breadcum -->
    <span class="text-gray-400 font-normal">Trang chủ > Tài khoản</span>
    <div class="grid grid-cols-10 gap-4 mt-3">
      <div class="col-span-2">
        <ul class="space-y-2">
          <li >
            <NuxtLink class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200  rounded-md" to="/profile">
              <UserCircleIcon class="h-6 w-6 text-gray-600" />
              <span class="text-gray-700 font-medium">Thông tin tài khoản</span>
            </NuxtLink>
          </li>

          <!-- Order Management -->
          <li >
            <NuxtLink class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200  rounded-md" to="/tracking">
              <ClipboardDocumentListIcon class="h-6 w-6 text-gray-600" />
              <span class="text-gray-700">Quản lý đơn hàng</span>
            </NuxtLink>
          </li>

          <!-- Payment Information -->
          <li class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200 rounded-md">
            <CreditCardIcon class="h-6 w-6 text-gray-600" />
            <span class="text-gray-700">Thông tin thanh toán</span>
          </li>

          <!-- Product Reviews -->
          <li class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200 rounded-md">
            <StarIcon class="h-6 w-6 text-gray-600" />
            <span class="text-gray-700">Đánh giá sản phẩm</span>
          </li>

          <!-- Favorite Products -->
          <li class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200 rounded-md">
            <HeartIcon class="h-6 w-6 text-gray-600" />
            <span class="text-gray-700">Sản phẩm yêu thích</span>
          </li>

          <!-- My Reviews -->
          <li class="flex items-center space-x-3 py-2 px-5 hover:bg-gray-200 rounded-md">
            <ChatBubbleBottomCenterIcon class="h-6 w-6 text-gray-600" />
            <span class="text-gray-700">Nhận xét của tôi</span>
          </li>
        </ul>
      </div>
      <div class="col-span-8">
        <slot/>
      </div>
    </div>
  </MainLayout>
  <div class="bg-white container my-8">
    <Footer />
  </div>
</template>
<style scoped>
* {
  font-weight: 400;
}
</style>
