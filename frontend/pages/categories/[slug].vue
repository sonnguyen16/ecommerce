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

definePageMeta({
  layout: 'main'
})

const { mediaUrl, appUrl } = useRuntimeConfig().public

const { data } = await useServerFetch<Product[]>('products')

const { data: categories } = await useServerFetch<Category[]>('categories')

const { slug } = useRoute().params

const category = categories?.value?.find((c: Category) => c.slug == slug) || null

const filterProducts = data?.value?.filter((product: any) => product.category_id == category?.id)

const { title, icon, description } = useAppConfig()

const seo_title = title + ' - ' + category?.name

useSeoMeta({
  title: seo_title,
  description: seo_title,
  ogTitle: seo_title,
  ogDescription: seo_title,
  ogImage: mediaUrl + category?.image,
  ogUrl: appUrl + `/${category?.slug}`,
  ogSiteName: seo_title,
  ogType: 'article',
  twitterTitle: seo_title,
  twitterDescription: seo_title,
  twitterImage: mediaUrl + category?.image,
  twitterCard: 'summary_large_image'
})

</script>
