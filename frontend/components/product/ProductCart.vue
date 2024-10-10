<script lang="ts" setup>
import { TrashIcon } from "@heroicons/vue/24/outline";
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
  checked: {
    type: Boolean,
    required: true,
  },
});

const emits = defineEmits(["increment", "decrement", "delete", "ticked"]);

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

const { mediaUrl } = useRuntimeConfig().public

</script>
<template>
  <div class="">
    <!-- Product 1 -->
    <div class="grid grid-cols-8 items-center py-3 border-b">
      <!-- Product Info -->
      <div class="col-span-3 flex items-center">
        <input
          v-if="useRoute().path === '/cart'"
          @change="emits('ticked', product.id)"
          type="checkbox"
          :checked="checked"
          class="h-5 w-5 border border-gray-300 rounded-md me-2"
        />
        <img
          :src="mediaUrl + product.thumbnail"
          alt="Product Image"
          class="w-24 rounded-xl"
        />
        <div class="ms-2">
          <p class="font-semibold text-gray-700 line-clamp-2">
            {{ product.name }}
          </p>
          <img src="/chinhhang.png" alt="chinhhang" class="w-[80px] lg:block inline-block mt-2">
        </div>
      </div>

      <!-- Price -->
      <div class="col-span-2 text-red-500 text-center">
        <p>{{ formatCash(product.sale_price.toString()) }} đ</p>
        <p class="line-through text-gray-400">{{ formatCash(product.price.toString()) }} đ</p>
      </div>

      <div class="col-span-1 flex items-center justify-center space-x-2">
        <button v-if="useRoute().path === '/cart'"  @click.prevent="decrement" class="border rounded px-2 py-1">-</button>
        <span>{{ quantity }}</span>
        <button v-if="useRoute().path === '/cart'"  @click.prevent="increment" class="border rounded px-2 py-1">+</button>
      </div>

      <!-- Total -->
      <div class="col-span-1 text-red-500 text-right">
        {{ formatCash((product.sale_price * quantity).toString()) }} đ
      </div>

      <!-- Remove Icon -->
      <TrashIcon v-if="useRoute().path === '/cart'" @click.prevent="deleteProduct" class="h-5 w-5 ms-auto" />
    </div>
  </div>
</template>
