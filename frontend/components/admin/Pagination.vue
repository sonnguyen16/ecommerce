<script setup lang="ts">

import type {PaginationData} from "~/lib/schema";
import {ArrowLeftIcon, ArrowRightIcon} from "@heroicons/vue/24/outline";

const props = defineProps({
  pagination_data: {
    type: Object as PropType<PaginationData<any>>,
    required: true
  }
});

const emit = defineEmits(['page-change']);

const goToPage = (page: number) => {
  if (page > 0 && page <= props.pagination_data.last_page) {
    emit('page-change', page);
  }
};

const visiblePages = computed(() => {
  const pagesBefore = 4;
  const pagesAfter = 3;

  const startPage = Math.max(props.pagination_data.current_page - pagesBefore, 1);
  const endPage = Math.min(props.pagination_data.current_page + pagesAfter, props.pagination_data.last_page);

  let pages = [];
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }

  return pages;
});
</script>

<template>
  <div class="flex items-center justify-end">
    <div class="flex items-center gap-2">
      <button
          :disabled="pagination_data.current_page === 1"
          @click="goToPage(1)"
          class="px-3 py-1 text-md font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <ArrowLeftIcon class="w-5 h-5" />
      </button>


      <button
          v-for="page in visiblePages" :key="page"
          :class="{
          'bg-blue-500 text-white border-blue-500 hover:bg-blue-600': page === pagination_data.current_page,
          'text-gray-500 bg-white border-gray-300 hover:bg-gray-100': page !== pagination_data.current_page
        }"
          class="px-3 py-1 text-md font-medium border rounded-md"
          @click="goToPage(page)"
      >
        {{ page }}
      </button>


      <button
          :disabled="pagination_data.current_page === pagination_data.last_page"
          @click="goToPage(pagination_data.last_page)"
          class="px-3 py-1 text-md font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <ArrowRightIcon class="w-5 h-5" />
      </button>
    </div>
  </div>
</template>

<style scoped>
button[disabled] {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
