<script setup lang="ts">

import MainLayout from "~/layouts/MainLayout.vue";
</script>

<template>
  <MainLayout>
      <div class="flex justify-center py-5">
        <div class="bg-white rounded-lg p-5" style="width: 500px !important;">
          <h2 class="text-2xl font-bold text-center text-blue-500 mb-4">Đăng ký</h2>

          <form>
            <div class="mb-4">
              <label for="fullName" class="block text-gray-700 font-medium mb-2">Họ tên</label>
              <input type="text" id="fullName" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập họ tên">
            </div>

            <div class="mb-4">
              <label for="phoneNumber" class="block text-gray-700 font-medium mb-2">Số điện thoại</label>
              <input type="text" id="phoneNumber" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập số điện thoại">
            </div>

            <div class="mb-4">
              <label for="password" class="block text-gray-700 font-medium mb-2">Mật khẩu</label>
              <input type="password" id="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập mật khẩu">
            </div>

            <div class="mb-4">
              <label for="shippingAddress" class="block text-gray-700 font-medium mb-2">Địa chỉ nhận hàng</label>
              <input type="text" id="shippingAddress" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nhập địa chỉ nhận hàng">
            </div>

            <div class="mb-4">
              <label for="province" class="block text-gray-700 font-medium mb-2">Tỉnh</label>
              <select id="province" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Chọn tỉnh</option>
                <option value="Hanoi">Hà Nội</option>
                <option value="HCM">Hồ Chí Minh</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="district" class="block text-gray-700 font-medium mb-2">Huyện</label>
              <select id="district" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Chọn huyện</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="commune" class="block text-gray-700 font-medium mb-2">Xã</label>
              <select id="commune" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Chọn xã</option>
              </select>
            </div>

            <button class="w-full bg-red-500 text-white py-2 my-2 rounded-lg">Đăng ký</button>
          </form>
        </div>
      </div>
  </MainLayout>
  <div class="bg-white container my-8">
    <Footer/>
  </div>
</template>

<style scoped>
  #body {
    background: linear-gradient(to right ,rgba(0,0,0,0.4), rgba(0,0,0,0.1)), url("/tikibg.png");
    background-size: cover;
  }
</style>