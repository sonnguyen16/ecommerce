<template>
  <MainLayout>
    <div class="grid grid-cols-6 gap-8">
      <div class="col-span-1 xl:block hidden">
        <HomeSidebar/>
      </div>
      <div class="xl:col-span-5 col-span-full space-y-5">
        <div class="rounded-xl bg-white p-5 py-4">
          <div class="flex justify-between items-center mb-4">
            <h1 class="text-indigo-700 font-semibold text-xl">{{ category?.name }}</h1>
          </div>
          <div class="flex gap-3 overflow-x-auto lg:grid lg:grid-cols-6">
            <template v-for="product in filterProducts">
              <Product class="basis-1/6" :product="product"/>
            </template>
          </div>
        </div>
        <div class="rounded-xl bg-white p-5 lg:block hidden">
          <HomeFooter/>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
<script setup lang="ts">
import MainLayout from "~/layouts/MainLayout.vue";
import type {Product} from "~/lib/schema";
import useFetchData from "~/composables/useFetchData";
import {MEDIA_ENDPOINT} from "~/lib/constants";

const { data } : { data:  Ref<Product[]> } = await useFetchData<Product[]>({url: 'products'})

const { data: Categories } = await useFetchData({url: 'categories'})

const category_id = useRoute().params.category_id

const category = computed(() => {
  return Categories?.value?.find((c: any) => c.id == category_id)
})

const filterProducts = computed(() => {
  return data?.value?.filter((product: any) => product.category_id == category_id)
})

const { title, app_url, icon, description } = useAppConfig()

const seo_title = title + ' - ' + category?.value?.name

useSeoMeta({
  title: seo_title,
  description: seo_title,
  ogTitle: seo_title,
  ogDescription: seo_title,
  ogImage: MEDIA_ENDPOINT + category?.value?.image,
  ogUrl: app_url + `/${category_id}/${category?.value?.slug}`,
  ogSiteName: seo_title,
  ogType: 'article',
  twitterTitle: seo_title,
  twitterDescription: seo_title,
  twitterImage: MEDIA_ENDPOINT + category?.value?.image,
  twitterCard: 'summary_large_image'
})

</script>
