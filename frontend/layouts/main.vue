<script setup lang="ts">
import '~/assets/css/input.css'

const showCategoryMenu = ref(false)
const showCategories = () => {
  showCategoryMenu.value = !showCategoryMenu.value
}

if(process.client){
  document.addEventListener('click', (e: any) => {
    if(e.target.closest('#mobile-navigation') === null){
      showCategoryMenu.value = false
    }
  })
}


</script>

<template>
    <div>
      <HomeNavbar />
      <div id="body" class="bg-[#f5f5fa] py-4">
        <div class="container">
          <NuxtPage />
        </div>
      </div>
      <div class="bg-white container my-8">
        <HomeFooter />
      </div>
      <MobileNavigation @show-categories="showCategories" />
      <MobileCategoryMenu :show="showCategoryMenu" />
    </div>
</template>

<style scoped>

</style>