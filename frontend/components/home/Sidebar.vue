<template>
  <div class="rounded-xl bg-white px-2 py-3 sticky top-4">
    <h4 class="font-semibold ms-3">Danh mục</h4>
    <ul class="mt-3">
      <li @click.prevent="viewCategory(category)" v-for="category in data" :key="category.name" class="flex items-center p-2 gap-2 cursor-pointer">
        <img :src="mediaUrl + category.image" alt="category" class="w-8 h-8 rounded-xl object-cover"/>
        <span class="font-normal">{{ category.name }}</span>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import type {Category} from "~/lib/schema";

const { data } = await useServerFetch<Category[]>('categories')

const { mediaUrl } = useRuntimeConfig().public
const viewCategory = (category: any) => {
  useRouter().push(`/categories/${category.slug}`)
}
</script>
