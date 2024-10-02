<script setup lang="ts">
import {MEDIA_ENDPOINT} from "~/lib/constants";
import {formatCash} from "~/lib/utils";
import type {OrderDetail} from "~/lib/schema";

const props = defineProps({
  orderdetail: {
    type: Object as PropType<OrderDetail>,
    required: true,
  },
});

const statuses = [
  { id: 1, value: 'Chờ xác nhận', color: 'bg-yellow-100 text-yellow-600' },
  { id: 2, value: 'Đang giao hàng', color: 'bg-blue-100 text-blue-600' },
  { id: 3, value: 'Đã giao hàng', color: 'bg-green-100 text-green-600' },
  { id: 4, value: 'Đã hủy', color: 'bg-red-100 text-red-600' },
]

const viewDetail = () => {
  if(useRoute().path.includes('tracking')){
    useRouter().push(`/profile/tracking/${props.orderdetail.id}`)
  }
}

</script>

<template>
    <div @click.prevent="viewDetail" class="grid grid-cols-7 items-center py-2 hover:bg-gray-200 rounded-md hover:cursor-pointer transition duration-200 px-3">
      <!-- Product Info -->
      <div class="col-span-2">
        <div class="flex items-center space-x-2">
          <img
              :src="MEDIA_ENDPOINT + orderdetail.product.thumbnail"
              alt="Product Image"
              class="w-24"
          />
          <div class="">
            <p class="font-semibold text-gray-700">
              {{ orderdetail.product.name }}
            </p>
          </div>
        </div>
      </div>

      <div class="col-span-2 text-center">
         <span :class="statuses.find(status => status.id === orderdetail.status).color" class="px-2 py-1 rounded-full">
              {{ statuses.find(s => s.id === orderdetail.status).value }}
         </span>
      </div>

      <!-- Price -->
      <div class="col-span-1 text-red-500 text-center">
        <p>{{ formatCash(orderdetail.price.toString()) }} đ</p>
        <p class="line-through text-gray-400">{{ formatCash(orderdetail.product.price.toString()) }} đ</p>
      </div>

      <!-- Quantity -->
      <div class="col-span-1 flex items-center justify-center space-x-2">
        <span>{{ orderdetail.quantity }}</span>
      </div>

      <!-- Total -->
      <div class="col-span-1 text-red-500 text-right">
        {{ formatCash((orderdetail.total).toString()) }} đ
      </div>
    </div>
</template>
