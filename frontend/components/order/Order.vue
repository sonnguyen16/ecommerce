<script setup lang="ts">
import type {Order} from "~/lib/schema";
import OrderDetail from "~/components/order/OrderDetail.vue";
import {formatCash} from "~/lib/utils";

const props = defineProps({
  order: {
    type: Object as PropType<Order>,
    required: true,
  },
  index: {
    type: Number,
    required: true,
  },
});

const firstOrder = props.order.order_details?.[0]

const showFullOrderDetail : Ref<Boolean> = ref(false)

</script>

<template>
  <div class="border-b">
    <div class="grid-cols-7 grid items-center ">
      <div class="col-span-3 text-gray-500"><p class="text-gray-700 ps-3 mt-2">Mã đơn hàng: <span class="text-blue-700">{{ order.code }}</span></p></div>
      <div v-if="index === 0" class="col-span-2 text-gray-500 text-center">Đơn giá</div>
      <div v-if="index === 0" class="col-span-1 text-gray-500 text-center">Số lượng</div>
      <div v-if="index === 0" class="col-span-1 text-gray-500 text-end">Tổng tiền</div>
    </div>
    <OrderDetail :orderdetail="firstOrder" />
    <div v-if="showFullOrderDetail" v-for="orderdetail in order.order_details.slice(1)">
      <OrderDetail :orderdetail="orderdetail" />
    </div>
    <p class="text-gray-700 text-end mb-2">Tổng tiền: <span class="text-red-500">{{ formatCash(order.total.toString()) }} đ</span></p>
    <div v-if="order.order_details.length > 1" class="flex justify-center mt-2">
      <button @click="showFullOrderDetail = !showFullOrderDetail" class="text-red-500 py-2 border-t w-full">
        <span v-if="showFullOrderDetail">Thu gọn</span>
        <span v-else>Xem thêm {{ order.order_details.length - 1 }} sản phẩm</span>
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>