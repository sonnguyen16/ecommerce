<template>
  <div class="grid grid-cols-6 gap-8">
    <div class="col-span-1 xl:block hidden">
      <HomeSidebar />
    </div>
    <div class="xl:col-span-5 col-span-full space-y-5">
      <div class="rounded-xl bg-white p-5 py-4">
        <div class="flex justify-between items-center mb-4">
          <h1 class="text-indigo-700 font-semibold text-xl">{{ category?.name }}</h1>
        </div>
        <div class="flex gap-3 overflow-x-auto lg:grid lg:grid-cols-6">
          <template v-for="product in paginatedProducts?.data">
            <Product class="basis-1/6" :product="product" />
          </template>
        </div>

        <!-- Thêm phân trang -->
        <div class="mt-6">
          <AdminPagination v-if="paginatedProducts" :pagination_data="paginatedProducts" @page-change="goToPage" />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import type { Category, Product, PaginationData } from '~/lib/schema'

definePageMeta({
  layout: 'main'
})

const route = useRoute()
const { slug } = route.params
const { mediaUrl, appUrl } = useRuntimeConfig().public

// Lấy thông tin danh mục
const { data: categories } = await useServerFetch<Category[]>('categories')
const category = categories?.value?.find((c: Category) => c.slug == slug) || null

// Cấu hình phân trang
const currentPage = ref(1)
const perPage = ref(12)
const paginatedProducts = ref<PaginationData<Product> | null>(null)

// Hàm lấy dữ liệu sản phẩm theo phân trang
const fetchProducts = async (page: number = 1) => {
  if (!category) return

  const { data, error } = await useClientFetch<PaginationData<Product>>(
    `products?category_id=${category.id}&perPage=${perPage.value}&page=${page}`
  )

  if (!error.value) {
    paginatedProducts.value = data.value
    currentPage.value = page
  }
}

// Khởi tạo dữ liệu ban đầu
onMounted(async () => {
  if (category) {
    await fetchProducts(1)
  }
})

// Theo dõi khi danh mục thay đổi
watch(
  () => route.params.slug,
  async () => {
    const newCategory = categories.value?.find((c: Category) => c.slug == route.params.slug) || null
    if (newCategory && newCategory.id !== category?.id) {
      await fetchProducts(1)
    }
  },
  { immediate: true }
)

// Hàm xử lý khi chuyển trang
const goToPage = async (page: number) => {
  await fetchProducts(page)

  // Cuộn lên đầu danh sách sản phẩm
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

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
