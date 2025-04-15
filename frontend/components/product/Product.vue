<script setup lang="ts">
import { StarIcon } from '@heroicons/vue/24/solid'
import type { Product } from '~/lib/schema'
import { formatCash } from '~/lib/utils'

const props = defineProps<{
  product: Product
}>()

const { mediaUrl } = useRuntimeConfig().public

const discount = Math.round(((props.product.price - props.product?.sale_price) / props.product.price) * 100)
</script>

<template>
  <NuxtLink :to="`/products/${product.slug}`">
    <div class="rounded-xl border border-gray-200 hover:shadow-lg min-w-[120px]">
      <img
        :src="product.thumbnail.toString() !== 'undefined' ? mediaUrl + product.thumbnail : mediaUrl + 'icoy.png'"
        alt="product"
        class="w-full rounded-tl-xl rounded-tr-xl h-[186px] object-cover"
      />
      <img src="/topdeal2.png" alt="topdeal" class="lg:w-[80px] w-[40px] lg:block inline-block ms-2 mt-2" />
      <img src="/chinhhang.png" alt="chinhhang" class="lg:w-[90px] w-[50px] lg:block inline-block ms-2 mt-2" />
      <div class="px-3 py-2">
        <p class="font-normal line-clamp-2 lg:text-[14px] text-sm">{{ product.name }}</p>
        <div class="flex mt-1">
          <template v-for="i in 5">
            <StarIcon class="w-4 h-4 text-yellow-300" />
          </template>
        </div>
        <span class="text-red-700 font-semibold">{{ formatCash(product.sale_price.toString()) }} đ</span>
        <div class="flex gap-2 items-center">
          <span class="block rounded-lg bg-gray-100 font-normal text-sm">-{{ discount }}%</span>
          <span class="text-gray-600 line-through font-normal lg:text-[14px] text-sm"
            >{{ formatCash(product.price?.toString()) }} đ</span
          >
        </div>
        <div class="w-full h-[1px] bg-gray-200 mt-6"></div>
        <div class="flex gap-2 items-center mt-2">
          <img src="/now.png" alt="topdeal" class="w-[30px] lg:block hidden" />
          <span class="font-normal text-gray-600 text-sm">Giao siêu tốc 2h</span>
        </div>
      </div>
    </div>
  </NuxtLink>
</template>

<style scoped></style>
