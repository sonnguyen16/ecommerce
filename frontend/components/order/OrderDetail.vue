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

</script>

<template>
    <div class="grid grid-cols-7 items-center py-2">
      <!-- Product Info -->
      <div class="col-span-3">
        <div class="flex items-center space-x-2">
          <img
              :src="MEDIA_ENDPOINT + orderdetail.product.thumbnail"
              alt="Product Image"
              class="w-24 rounded-xl"
          />
          <div class="">
            <p class="font-semibold text-gray-700">
              {{ orderdetail.product.name }}
            </p>
            <p class="text-red-500 text-sm">Sách không hỗ trợ Bookcare</p>
          </div>
        </div>
      </div>

      <!-- Price -->
      <div class="col-span-2 text-red-500 text-center">
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

<style scoped>

</style>