<script setup>
defineProps({
  message: {
    type: String,
  },
});
</script>

<template>
  <div class="h-[20px] flex items-center">
    <p class="text-red-500">
      {{ message }}
    </p>
  </div>
</template>
