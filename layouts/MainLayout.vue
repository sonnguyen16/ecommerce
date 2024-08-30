<script setup lang="ts">
import '~/assets/css/input.css'
</script>

<template>
  <Navbar />
  <div id="body" class="bg-[#f5f5fa] py-4">
      <div class="container">
        <slot />
      </div>
  </div>
</template>

<style scoped>

</style>