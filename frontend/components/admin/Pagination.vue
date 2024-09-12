<script setup lang="ts">

</script>

<template>
  <div class="flex items-center justify-between py-4">
    <!-- Pagination Info -->
    <div class="text-md text-gray-500">
      Hiển thị 1 đến 2 của 50 kết quả
    </div>

    <!-- Pagination Controls -->
    <div class="flex items-center gap-2">
      <!-- Previous Button -->
      <button class="px-3 py-1 text-md font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
        Trước
      </button>

      <!-- Page Numbers -->
      <button class="px-3 py-1 text-md font-medium text-white bg-blue-500 border border-blue-500 rounded-md hover:bg-blue-600">
        1
      </button>
      <button class="px-3 py-1 text-md font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
        2
      </button>
      <button class="px-3 py-1 text-md font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
        3
      </button>
      <span class="px-3 py-1 text-md text-gray-500">...</span>
      <button class="px-3 py-1 text-md font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
        10
      </button>

      <!-- Next Button -->
      <button class="px-3 py-1 text-md font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-100">
        Tiếp
      </button>
    </div>
  </div>
</template>

<style scoped>

</style>