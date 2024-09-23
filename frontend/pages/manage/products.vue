<script setup lang="ts">
import AdminLayout from "~/layouts/AdminLayout.vue";
import { DocumentIcon, Bars3Icon} from "@heroicons/vue/24/outline";
import {formatCash} from "~/lib/utils";
import {MEDIA_ENDPOINT} from "~/lib/constants";
import Pagination from "~/components/admin/Pagination.vue";
import type {PaginationData, Product} from "~/lib/schema";

const page = ref(1);

let { data } : { data: Ref<PaginationData<Product>> } = await useFetchData({
  url: `shop/products?page=${page}`,
  requiresToken: true,
  server: false,
})

const fetchData = async (page: number) => {
  const { data: newData, error } : { data: Ref<PaginationData<Product>>, error: any } = await useFetchData({
    url: `shop/products?page=${page}`,
    requiresToken: true,
    server: false,
  })

  if(!error.value) {
    data.value = newData.value
  }else{
    console.log(error)
  }
}

const goToPage = async (p: number) => {
  if (p > 0 && p <= data.value.last_page) {
    await fetchData(p)
  }
}
</script>

<template>
  <AdminLayout>
    <h1 class="text-2xl">Quản lý sản phẩm</h1>
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
        <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg basis-1/6">
          <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full">
            <DocumentIcon class="w-6 h-6" />
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Tổng sản phẩm</p>
            <p class="text-2xl font-semibold text-gray-900">{{ data?.total }}</p>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-gray-100 p-4 rounded-lg">
        <div class="grid grid-cols-7 gap-4 text-sm font-medium text-gray-500">
          <div class="flex items-center">
            <input type="checkbox" class="form-checkbox h-5 w-5 border-gray-300 rounded-md text-blue-600">
            <span class="ml-2">Mã sản phẩm</span>
          </div>
          <div>Hình ảnh</div>
          <div>Tên sản phẩm</div>
          <div>Giá</div>
          <div>Phân loại</div>
          <div>Ngày tạo</div>

        </div>
      </div>
      <div v-if="data?.data?.length > 0">
        <div v-for="(product, i) in data.data" :class="i === 0 ? 'px-4 pb-4' : 'p-4'" class="grid grid-cols-7 gap-4 items-center text-sm text-gray-700 border-b border-gray-200">
          <div class="flex items-center">
            <input type="checkbox" class="form-checkbox h-5 w-5 border-gray-300 rounded-md text-blue-600">
            <a href="#" class="ml-2 text-blue-500 hover:underline">{{ product.id }}</a>
          </div>
          <div>
            <img :src="MEDIA_ENDPOINT + product.thumbnail" alt="product" class="w-16 h-16 object-cover rounded-lg">
          </div>
          <div>{{ product.name }}</div>
          <div>{{ formatCash(product.price.toString()) }} đ</div>
          <div>{{ product.category.name }}</div>
          <div>{{ new Date(product.created_at).toLocaleDateString() }}</div>
        </div>
      </div>

        <Pagination
          v-if="data"
          :pagination_data="data"
          @page-change="goToPage"
        />
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>