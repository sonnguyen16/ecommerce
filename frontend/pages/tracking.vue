<script lang="ts" setup>
import ProfileLayout from "~/layouts/ProfileLayout.vue";
</script>
<template>
 <ProfileLayout>
   <div class="bg-white rounded-md p-4">
     <!-- Header -->
     <h1 class="text-2xl font-bold text-gray-800 mb-4">Đơn hàng của tôi</h1>

     <!-- Tabs -->
     <div class="flex space-x-4 border-b border-gray-300 mb-4">
       <button class="text-blue-500 border-b-2 border-blue-500 py-2 px-4">Tất cả đơn</button>
       <button class="text-gray-500 py-2 px-4">Chờ thanh toán</button>
       <button class="text-gray-500 py-2 px-4">Đang xử lý</button>
       <button class="text-gray-500 py-2 px-4">Đang vận chuyển</button>
       <button class="text-gray-500 py-2 px-4">Đã giao</button>
       <button class="text-gray-500 py-2 px-4">Đã hủy</button>
     </div>

     <!-- Search bar -->
     <div class="flex items-center mb-6">
       <div class="relative w-full">
         <input type="text" class="border border-gray-300 rounded w-full py-2 pl-10 pr-4 focus:outline-none focus:border-blue-500" placeholder="Tìm đơn hàng theo Mã đơn hàng, Nhà bán hoặc Tên sản phẩm">
         <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    🔍
                </span>
       </div>
       <button class="bg-white border border-gray-300 text-blue-500 rounded ml-2 px-4 py-2">Tìm</button>
     </div>

     <!-- Empty orders message -->
     <div class="p-8 text-center">
       <div class="flex justify-center mb-4">
         <img src="https://frontend.tikicdn.com/_desktop-next/static/img/account/empty-order.png" alt="Empty order illustration" class="w-60">
       </div>
       <p class="text-gray-500">Chưa có đơn hàng</p>
     </div>

   </div>
 </ProfileLayout>
</template>
<style scoped>
* {
  font-weight: 400;
}
</style>
