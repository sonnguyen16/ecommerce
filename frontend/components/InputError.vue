<script setup>
defineProps({
  message: {
    type: String,
  },
});
</script>

<template>
  <div class="h-[16px]">
    <p class="text-red-500 mt-[3px]">
      {{ message }}
    </p>
  </div>
</template>
