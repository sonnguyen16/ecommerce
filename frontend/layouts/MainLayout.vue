<script setup lang="ts">
import '~/assets/css/input.css'
import Navbar from "~/components/home/Navbar.vue";
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