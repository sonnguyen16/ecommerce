<script setup lang="ts">
import { DocumentIcon, PencilSquareIcon} from "@heroicons/vue/24/outline";
import {formatCash} from "~/lib/utils";
import type {Category, PaginationData, Product} from "~/lib/schema";

definePageMeta({
  layout: 'admin',
  middleware: 'auth'
})

const { mediaUrl } = useRuntimeConfig().public

let data : Ref<PaginationData<Product> | null> = ref(null)

let { data: categoriesData } = await useClientFetch<Category[]>(`categories`)

let num = ref(0)

const fetchData = async (page: number) => {
  const { data: newData, error } = await useClientFetch<PaginationData<Product>>(`shop/products?page=${page}`)

  if(!error.value) {
    data.value = newData.value
  }else{
    console.log(error)
  }
}

onBeforeMount(async () => {
  await fetchData(1)
})

const goToPage = async (p: number) => {
  if (data.value && p > 0 && p <= data.value.last_page) {
    await fetchData(p)
  }
}
</script>

<template>
    <h1 class="text-2xl">Quản lý sản phẩm</h1>
    <div class="bg-white rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)]">
      <div class="flex flex-wrap items-center gap-4">
        <NuxtLink :to="`/manage/products/${null}`" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Thêm sản phẩm</NuxtLink>
        <input type="text" placeholder="Tìm theo từ khóa" class="px-4 py-2 w-60 focus:outline-none focus:ring focus:ring-blue-300 border border-gray-300 rounded-lg">
        <select class="px-4 py-2 border border-gray-300 text-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
          <option selected>Danh mục</option>
          <option v-for="category in categoriesData" :key="category.id" :value="category.id">{{ category.name }}</option>
        </select>
      </div>

      <div class="flex gap-4 mt-5">
        <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
          <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full">
            <DocumentIcon class="w-6 h-6" />
          </div>
          <div>
            <p class="text-sm font-medium text-gray-500">Tổng sản phẩm</p>
            <p class="text-2xl font-semibold text-gray-900">{{ data?.total }}</p>
          </div>
        </div>
      </div>

      <div class="w-full overflow-x-auto">
        <div class="min-w-[800px]">
          <div class="mt-6 bg-gray-100 p-4 rounded-lg">
            <div class="grid grid-cols-7 gap-4 text-sm font-medium text-gray-500">
              <div class="flex items-center">
                <span class="ml-2">Mã sản phẩm</span>
              </div>
              <div>Hình ảnh</div>
              <div>Tên sản phẩm</div>
              <div>Giá</div>
              <div>Phân loại</div>
              <div>Ngày tạo</div>
              <div>Thao tác</div>
            </div>
          </div>
          <div v-if="data?.data?.length > 0" class="mb-5">
            <div v-for="(product, i) in data.data" class="grid px-4 grid-cols-7 gap-4 items-center text-sm text-gray-700 border-b border-gray-200">
              <div class="flex items-center">
                <NuxtLink :to="`/manage/products/${product.id}`" class="ml-2 text-blue-500 hover:underline">{{ product.id }}</NuxtLink>
              </div>
              <div class="py-[5px]">
                <img :src="mediaUrl + product.thumbnail" alt="product" class="w-12 h-12 object-cover rounded-lg">
              </div>
              <div class="line-clamp-2">{{ product.name }}</div>
              <div>{{ formatCash(product.price.toString()) }} đ</div>
              <div>{{ product.category.name }}</div>
              <div>{{ new Date(product.created_at).toLocaleDateString() }}</div>
              <div>
                <NuxtLink :to="`/manage/products/${product.id}`" class="text-indigo-500 p-3">
                  <PencilSquareIcon class="w-5 h-5 inline-block" />  Sửa
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>

      <AdminPagination
          v-if="data"
          :pagination_data="data"
          @page-change="goToPage"
      />

    </div>
</template>

<style scoped>

</style>