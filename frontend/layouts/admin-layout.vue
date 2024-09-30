<script setup lang="ts">
const name = 'admin-layout'
</script>

<template>
  <NuxtLayout :name="name">
    <div class="h-screen w-screen">
      <div class="flex">
        <div class="">
          <AdminSidebar />
        </div>
        <div class="bg-gray-100 w-[100vw]">
          <AdminHeader />
          <div class="py-4 px-5">
            <NuxtPage />
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<style scoped>

</style>