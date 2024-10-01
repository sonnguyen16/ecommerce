<template>
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
      </div>
    </div>
</template>
<script setup lang="ts">
import type {Category, Product} from "~/lib/schema";
import {MEDIA_ENDPOINT} from "~/lib/constants";

definePageMeta({
  layout: 'main'
})

const { data } = await useServerFetch<Product[]>('products')

const { data: categories } = await useServerFetch<Category[]>('categories')

const category_id = useRoute().params.category_id

const category = categories?.value?.find((c: any) => c.id == category_id)

const filterProducts = data?.value?.filter((product: any) => product.category_id == category_id)

const { title, app_url, icon, description } = useAppConfig()

const seo_title = title + ' - ' + category?.name

useSeoMeta({
  title: seo_title,
  description: seo_title,
  ogTitle: seo_title,
  ogDescription: seo_title,
  ogImage: MEDIA_ENDPOINT + category?.image,
  ogUrl: app_url + `/${category_id}/${category?.slug}`,
  ogSiteName: seo_title,
  ogType: 'article',
  twitterTitle: seo_title,
  twitterDescription: seo_title,
  twitterImage: MEDIA_ENDPOINT + category?.image,
  twitterCard: 'summary_large_image'
})

</script>
