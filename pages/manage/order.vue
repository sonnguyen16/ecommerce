<script setup lang="ts">
import AdminLayout from "~/layouts/AdminLayout.vue";
import { DocumentIcon, Bars3Icon, CurrencyDollarIcon } from "@heroicons/vue/24/outline";
import Pagination from "~/components/admin/Pagination.vue";
</script>

<template>
  <AdminLayout>
    <h1 class="text-2xl">Quản lý đơn hàng</h1>
    <div class="bg-white rounded-xl p-4 mt-5 h-[calc(100vh-9.5rem)] space-y-5">
      <div class="flex flex-wrap items-center gap-4">
        <input type="text" placeholder="Tìm theo từ khóa" class="px-4 py-2 w-60 focus:outline-none focus:ring focus:ring-blue-300 border border-gray-300 rounded-lg">

        <select class="px-4 py-2 border border-gray-300 text-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
          <option selected>Tên Khách Hàng</option>
          <option>Khách Hàng 1</option>
          <option>Khách Hàng 2</option>
        </select>

        <input type="date" class="px-4 py-2 border text-gray-500 border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">

        <select class="px-4 py-2 border border-gray-300 text-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
            <option selected>Mọi lúc</option>
            <option>Ngày cập nhật 1</option>
            <option>Ngày cập nhật 2</option>
        </select>

        <button class="p-2 border rounded-lg bg-gray-100 focus:outline-none focus:ring focus:ring-blue-300">
          <Bars3Icon class="h-6 w-6 text-gray-500" />
        </button>
      </div>

      <div class="flex gap-4">
        <template v-for="i in 2">
          <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg basis-1/6">
            <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full">
              <DocumentIcon class="w-6 h-6" />
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Tổng Đơn Hàng</p>
              <p class="text-2xl font-semibold text-gray-900">123</p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg basis-1/6">
            <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full">
              <CurrencyDollarIcon class="w-6 h-6" />
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Tổng Tiền</p>
              <p class="text-2xl font-semibold text-gray-900">201.576.000 đ</p>
            </div>
          </div>
        </template>
      </div>

      <div class="mt-6 bg-gray-100 p-4 rounded-lg">
        <div class="grid grid-cols-7 gap-4 text-sm font-medium text-gray-500">
          <div class="flex items-center">
            <input type="checkbox" class="form-checkbox h-5 w-5 border-gray-300 rounded-md text-blue-600">
            <span class="ml-2">Mã Đơn Hàng</span>
          </div>
          <div>Trạng Thái</div>
          <div>Khách Hàng</div>
          <div>Tổng Tiền</div>
          <div>PT. Thanh Toán</div>
          <div>Ngày Đặt Hàng</div>
          <div>Phí Giao Hàng</div>
        </div>
      </div>
      <div class="">
        <div v-for="(row, _) in 10" :class="_ === 0 ? 'px-4 pb-4' : 'p-4'" class="grid grid-cols-7 gap-4 items-center text-sm text-gray-700 border-b border-gray-200">
          <div class="flex items-center">
            <input type="checkbox" class="form-checkbox h-5 w-5 border-gray-300 rounded-md text-blue-600">
            <a href="#" class="ml-2 text-blue-500 hover:underline">72943782</a>
          </div>
          <div>
            <span class="px-2 py-1 text-sm font-medium bg-green-100 text-green-600 rounded-lg">Đã giao hàng</span>
          </div>
          <div>Ho Ngoc Thanh Thuy</div>
          <div>300,680đ</div>
          <div>Tiền mặt</div>
          <div>31-07-2024</div>
          <div>Shop Owner</div>
        </div>

      </div>
      <Pagination />

    </div>
  </AdminLayout>
</template>

<style scoped>

</style>