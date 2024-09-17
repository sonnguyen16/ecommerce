<script lang="ts" setup>
import { TrashIcon } from "@heroicons/vue/24/outline";
import {MEDIA_ENDPOINT} from "~/lib/constants";
import {formatCash} from "~/lib/utils";
import type {Product} from "~/lib/schema";

const props = defineProps({
  product: {
    type: Object as PropType<Product>,
    required: true,
  },
  quantity: {
    type: Number,
    required: true,
  },
});

const emits = defineEmits(["increment", "decrement", "delete"]);

//caculate discount percent depend on product.price and product.sale_price
const discount = Math.round((props.product.price - props.product.sale_price) / props.product.price * 100);

const increment = () => {
  emits("increment", props.product.id);
}
const decrement = () => {
  if (props.quantity > 1) {
    emits("decrement", props.product.id);
  }
}

const deleteProduct = () => {
  emits("delete", props.product.id);
}
</script>
<template>
  <div class="">
    <!-- Product 1 -->
    <div class="grid grid-cols-8 items-center py-3 border-b">
      <!-- Product Info -->
      <div class="col-span-3 flex items-center">
        <input
          type="checkbox"
          class="h-5 w-5 border border-gray-300 rounded-md"
        />
        <img
          :src="MEDIA_ENDPOINT + product.thumbnail"
          alt="Product Image"
          class="w-24 rounded-xl"
        />
        <div>
          <p class="font-semibold text-gray-700">
            {{ product.name }}
          </p>
          <p class="text-red-500 text-sm">Sách không hỗ trợ Bookcare</p>
        </div>
      </div>

      <!-- Price -->
      <div class="col-span-2 text-red-500 text-center">
        <p>{{ formatCash(product.sale_price.toString()) }} đ</p>
        <p class="line-through text-gray-400">{{ formatCash(product.price.toString()) }} đ</p>
      </div>

      <div class="col-span-1 flex items-center justify-center space-x-2">
        <button @click.prevent="decrement" class="border rounded px-2 py-1">-</button>
        <span>{{ quantity }}</span>
        <button @click.prevent="increment" class="border rounded px-2 py-1">+</button>
      </div>

      <!-- Total -->
      <div class="col-span-1 text-red-500 text-right">
        {{ formatCash((product.sale_price * quantity).toString()) }} đ
      </div>

      <!-- Remove Icon -->
      <TrashIcon @click.prevent="deleteProduct" class="h-5 w-5 ms-auto" />
    </div>
  </div>
</template>
