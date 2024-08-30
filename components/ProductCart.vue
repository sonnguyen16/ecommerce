<script lang="ts" setup>
import { TrashIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
  product: {
    type: Object as PropType<{
      img: string;
      name: string;
    }>,
    required: true,
  },
});
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
          :src="product.img"
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
        <p>129.000₫</p>
        <p class="line-through text-gray-400">179.000₫</p>
      </div>

      <!-- Quantity -->
      <div class="col-span-1 flex items-center justify-center space-x-2">
        <button class="border rounded px-2 py-1">-</button>
        <span>1</span>
        <button class="border rounded px-2 py-1">+</button>
      </div>

      <!-- Total -->
      <div class="col-span-1 text-red-500 text-right">129.000₫</div>

      <!-- Remove Icon -->
      <TrashIcon class="h-5 w-5 ms-auto" />
    </div>
  </div>
</template>
