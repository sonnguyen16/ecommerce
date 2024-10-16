<script lang="ts" setup>
import type {OrderDetail} from "~/lib/schema";

definePageMeta({
  layout: 'profile',
  middleware: 'auth'
})

const id = useRoute().params.id

let provincesData = ref<any>([])

let order_detail = ref<OrderDetail | null>()

onBeforeMount(async () => {
  const [order, provinces] = await Promise.all([
    useClientFetch<OrderDetail>(`orders/${id}`),
    useClientFetch<any>('provinces')
  ])

  order_detail.value = order?.data.value
  provincesData.value = provinces?.data.value
})

const order = computed(() => order_detail?.value?.order)

const province = computed(() => provincesData.value.find((p: any) => p?.code === order?.value?.province))

const district = computed(() => province?.value?.districts?.find((d: any) => d?.code === order?.value?.district))

const wards = computed(() => district?.value?.wards?.find((w: any) => w?.code === order?.value?.ward))

</script>
<template>
    <div class="bg-white rounded-md p-4 grid md:grid-cols-3">
      <div class="col-span-2">
        <div class="ps-3 space-y-3">
          <h2 class="text-xl font-semibold mb-3 text-indigo-700">Thông tin khách hàng</h2>
          <div class="flex gap-6">
            <div class="space-y-3">
              <p><strong>Tên: </strong>{{ order?.name }}</p>
              <p><strong>Số điện thoại: </strong>{{ order?.phone }}</p>
              <p><strong>Ngày tạo: </strong>{{ new Date(order?.created_at).toLocaleString() }}</p>
              <p><strong>Địa chỉ: </strong>{{ order?.address }}</p>
            </div>
            <div class="space-y-3">
              <p><strong>Phường/Xã: </strong>{{ wards?.name }}</p>
              <p><strong>Quận/Huyện: </strong>{{ district?.name }}</p>
              <p><strong>Tỉnh/Thành phố: </strong>{{ province?.name }}</p>
            </div>
          </div>
        </div>
        <div class="ps-3 mt-5">
          <h2 class="text-xl font-semibold mb-3 text-indigo-700">Chi tiết đơn hàng</h2>
          <div class="grid-cols-7 grid items-center bg-gray-200 p-3 rounded-xl mb-3">
            <div class="col-span-2 text-gray-500">
              <p class="text-gray-700 ps-3">
                Mã đơn hàng: <span class="text-blue-700">{{ order_detail?.id }}</span>
              </p>
            </div>
            <div class="col-span-2 text-gray-500 text-center">Trạng thái</div>
            <div class="col-span-1 text-gray-500 text-center">Đơn giá</div>
            <div class="col-span-1 text-gray-500 text-center">Số lượng</div>
            <div class="col-span-1 text-gray-500 text-end">Tổng tiền</div>
          </div>
          <OrderDetail v-if="order_detail" :orderdetail="order_detail" />
        </div>
      </div>
      <div class="col-span-1 pl-5">
        <h2 class="text-xl font-semibold mb-5 text-indigo-700">Thông tin vận chuyển</h2>
        <div class="relative border-l border-gray-300 pl-5">
          <template v-if="order_detail?.locations?.length > 0" v-for="(location, index) in order_detail?.locations">
            <div class="mb-10 ml-6">
              <span :class="[index === 0 ? 'bg-yellow-500' : 'bg-gray-200']" class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full"></span>
              <time class="mb-1 text-sm font-normal text-gray-400">
                {{ new Date(location?.created_at).toLocaleDateString() }}
                <br>{{ new Date(location?.created_at).toLocaleTimeString() }}
              </time>
              <p class="text-lg font-semibold text-gray-900">{{ location?.note }}</p>
              <p class="text-sm text-gray-500">{{ location.address }}</p>
            </div>
          </template>
          <template v-else>
            <p class="text-gray-500">Chưa có thông tin vận chuyển</p>
          </template>
        </div>
      </div>
    </div>
</template>
