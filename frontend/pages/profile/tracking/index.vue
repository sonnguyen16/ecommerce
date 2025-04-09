<script lang="ts" setup>
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'
import type { OrderDetail, PaginationData } from '~/lib/schema'
import Pagination from '~/components/admin/Pagination.vue'
import { getToastMessage, setToastMessage } from '~/lib/utils'

definePageMeta({
  layout: 'profile',
  middleware: 'auth'
})

const status = ref<string | number>('all')
let order_details: Ref<PaginationData<OrderDetail> | null> = ref(null)
const fetchData = async (p: number) => {
  let { data } = await useClientFetch<PaginationData<OrderDetail>>(`orders?page=${p}&status=${status.value}`)
  order_details.value = data?.value
}

onMounted(() => {
  const toastMessage = getToastMessage()
  if (toastMessage) {
    showToastFunc(toastMessage.message, toastMessage.type)
  }
})

const showToast = ref(false)
const message = ref('')
const type = ref('')

const showToastFunc = (msg: string, toastType: string) => {
  showToast.value = true
  message.value = msg
  type.value = toastType
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

onBeforeMount(async () => {
  await fetchData(1)
})

watch(status, async () => {
  await fetchData(1)
})

const isActived = (value: string | number) => {
  return status.value == value ? 'text-blue-500 border-b-2 border-blue-500' : 'text-gray-500'
}

const goToPage = async (p: number) => {
  if (order_details?.value && p > 0 && p <= order_details?.value?.last_page) {
    await fetchData(p)
  }
}
</script>
<template>
  <div class="bg-white rounded-md p-4">
    <!-- Header -->
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Đơn hàng của tôi</h1>

    <!-- Tabs -->
    <div class="flex space-x-4 border-b border-gray-300 mb-4">
      <button @click.prevent="status = 'all'" :class="[isActived('all'), 'py-2 px-4']">Tất cả đơn</button>
      <button @click.prevent="status = 1" :class="[isActived(1), 'py-2 px-4']">Chờ xác nhận</button>
      <button @click.prevent="status = 2" :class="[isActived(2), 'py-2 px-4']">Đang vận chuyển</button>
      <button @click.prevent="status = 3" :class="[isActived(3), 'py-2 px-4']">Đã giao</button>
      <button @click.prevent="status = 4" :class="[isActived(4), 'py-2 px-4']">Đã hủy</button>
    </div>

    <!-- Search bar -->
    <div class="flex items-center mb-6">
      <div class="relative w-full">
        <input
          type="text"
          class="border border-gray-300 rounded w-full py-2 pl-10 pr-4 focus:outline-none focus:border-blue-500"
          placeholder="Tìm đơn hàng theo Mã đơn hàng, Nhà bán hoặc Tên sản phẩm"
        />
        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
          <MagnifyingGlassIcon class="h-5 w-5" />
        </span>
      </div>
    </div>

    <!-- Empty orders message -->
    <div class="">
      <template v-if="order_details?.data?.length == 0">
        <div class="flex justify-center mb-4">
          <img src="/empty-order.png" alt="Empty order illustration" class="w-60" />
        </div>
        <p class="text-gray-500 text-center">Chưa có đơn hàng</p>
      </template>
      <template v-if="order_details?.data?.length > 0">
        <div class="grid-cols-7 grid items-center px-3">
          <div class="col-span-2 text-gray-500">Sản phẩm</div>
          <div class="col-span-2 text-gray-500 text-center">Trạng thái</div>
          <div class="col-span-1 text-gray-500 text-center">Đơn giá</div>
          <div class="col-span-1 text-gray-500 text-center">Số lượng</div>
          <div class="col-span-1 text-gray-500 text-end">Tổng tiền</div>
        </div>
        <template v-for="order in order_details.data">
          <OrderDetail :orderdetail="order" />
        </template>
        <Pagination :pagination_data="order_details" @page-change="goToPage" />
      </template>
    </div>
  </div>

  <Toast :show="showToast" :message="message" :type="type" />
</template>
