<script setup lang="ts">
import { DocumentIcon,  CurrencyDollarIcon, EyeIcon } from "@heroicons/vue/24/outline";
import {formatCash} from "~/lib/utils";

definePageMeta({
  layout: 'admin',
})

let { data } : any = await useFetchData({
  url: `shop/orders`,
  requiresToken: true,
  server: false,
})

const fetchData = async (page: number) => {
  const { data: newData, error }: any = await useFetchData({
    url: `shop/orders?page=${page}`,
    requiresToken: true,
    server: false,
    cache: false,
  })

  if(!error.value) {
    data.value = newData.value
  }else{
    console.log(error)
  }
}

const goToPage = async (p: number) => {
  if (p > 0 && p <= data?.value?.orders?.last_page) {
    await fetchData(p)
  }
}

const statuses = [
  { id: 1, value: 'Chờ xác nhận', color: 'bg-yellow-100 text-yellow-600' },
  { id: 2, value: 'Đang giao hàng', color: 'bg-blue-100 text-blue-600' },
  { id: 3, value: 'Đã giao hàng', color: 'bg-green-100 text-green-600' },
  { id: 4, value: 'Đã hủy', color: 'bg-red-100 text-red-600' },
]

</script>

<template>
    <h1 class="text-2xl">Quản lý đơn hàng</h1>
    <div class="bg-white rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)] space-y-5">
      <div class="flex flex-wrap items-center gap-4">
        <input type="text" placeholder="Tìm theo từ khóa" class="px-4 py-2 w-60 focus:outline-none focus:ring focus:ring-blue-300 border border-gray-300 rounded-lg">

        <input type="date" class="px-4 py-2 border text-gray-500 border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">

        <select class="px-4 py-2 border border-gray-300 text-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
            <option selected>Trạng thái</option>
            <option v-for="status in statuses" :value="status.id" >{{ status.value }}</option>
        </select>
      </div>

      <div class="flex gap-4">
          <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg basis-1/6">
            <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full">
              <DocumentIcon class="w-6 h-6" />
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Tổng Đơn Hàng</p>
              <p class="text-2xl font-semibold text-gray-900">{{ data?.orders?.total }}</p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg basis-1/6">
            <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full">
              <CurrencyDollarIcon class="w-6 h-6" />
            </div>
            <div>
              <p class="text-sm font-medium text-gray-500">Tổng Tiền</p>
              <p class="text-2xl font-semibold text-gray-900">{{ formatCash(data?.total_money?.toString()) }} đ</p>
            </div>
          </div>
      </div>

      <div class="mt-6 bg-gray-100 p-4 rounded-lg">
        <div class="grid grid-cols-7 gap-4 text-sm font-medium text-gray-500">
          <div class="flex items-center">
            <span class="ml-2">Mã Đơn Hàng</span>
          </div>
          <div>Trạng Thái</div>
          <div>Khách Hàng</div>
          <div>Tổng Tiền</div>
          <div>PT. Thanh Toán</div>
          <div>Ngày Đặt Hàng</div>
          <div>Thao tác</div>
        </div>
      </div>
      <div v-if="data?.orders?.data?.length">
        <div v-for="(ord_detail, _) in data?.orders?.data" :class="_ === 0 ? 'px-4 pb-4' : 'p-4'" class="grid grid-cols-7 gap-4 items-center text-sm text-gray-700 border-b border-gray-200">
          <div class="flex items-center">
            <NuxtLink :to="`/manage/orders/${ord_detail.id}`" class="ml-2 text-blue-500 hover:underline">{{ ord_detail.id }}</NuxtLink>
          </div>
          <div>
            <span :class="statuses.find(status => status.id === ord_detail.status).color" class="px-2 py-1 rounded-full">
              {{ statuses.find(s => s.id === ord_detail.status).value }}
            </span>
          </div>
          <div>{{ ord_detail.order.name }}</div>
          <div>{{ formatCash(ord_detail.total.toString()) }} đ</div>
          <div>Tiền mặt</div>
          <div>{{ new Date(ord_detail.created_at).toLocaleString() }}</div>
          <div class="flex gap-4">
            <NuxtLink :to="`/manage/orders/${ord_detail.id}`" class="text-blue-500 hover:underline flex items-center">
              <EyeIcon class="w-5 h-5" /> Xem
            </NuxtLink>
          </div>
        </div>
      </div>
      <AdminPagination
          v-if="data?.orders"
          :pagination_data="data?.orders"
          @page-change="goToPage"
      />
    </div>
</template>

<style scoped>

</style>