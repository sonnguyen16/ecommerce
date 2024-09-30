<script setup lang="ts">
import '~/assets/css/input.css'
</script>

<template>
    <HomeNavbar />
    <div id="body" class="bg-[#f5f5fa] py-4">
      <div class="container">
        <NuxtPage />
      </div>
    </div>
    <div class="bg-white container my-8">
      <HomeFooter />
    </div>
</template>

<style scoped>

</style>