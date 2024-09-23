<script setup lang="ts">
import { UserCircleIcon, Cog6ToothIcon, BellAlertIcon, ShoppingBagIcon } from "@heroicons/vue/24/outline";

</script>

<template>
  <div class="w-full p-4 bg-blue-600">
    <div class="flex justify-between items-center">
      <span class="text-white flex items-center gap-3">
        <ShoppingBagIcon class="h-7 w-7" />
        <span class="text-lg">Shop của tôi</span>
      </span>
      <div class="flex items-center gap-8">
        <button>
          <BellAlertIcon class="h-8 w-8 text-white" />
        </button>
        <button>
          <UserCircleIcon class="h-8 w-8 text-white" />
        </button>
        <button>
          <Cog6ToothIcon class="h-8 w-8 text-white" />
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>