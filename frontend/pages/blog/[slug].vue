<template>
  <div class="container mx-auto px-4 pb-5 pt-1">
    <!-- Breadcrumb -->
    <div v-if="blog" class="text-gray-600 mb-4 font-normal">
      <NuxtLink to="/" class="hover:underline text-gray-600 font-normal">Trang chủ</NuxtLink> &gt;
      <NuxtLink to="/blog" class="hover:underline text-gray-600 font-normal">Blog</NuxtLink> &gt;
      <span class="text-gray-600 font-normal">{{ blog.title }}</span>
    </div>

    <div v-if="blog" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-8">
        <div class="bg-white rounded-xl overflow-hidden shadow-sm">
          <!-- Blog Content -->
          <div class="p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ blog.title }}</h1>

            <div class="flex items-center text-gray-500 text-sm mb-6">
              <span class="mr-4">
                <i class="far fa-calendar-alt mr-1"></i>
                {{
                  new Date(blog.created_at).toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                  })
                }}
              </span>
            </div>

            <div class="prose prose-lg max-w-none" v-html="blog.content"></div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-4">
        <!-- Related Posts -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
          <h3 class="text-lg font-semibold mb-4">Bài viết liên quan</h3>
          <div v-if="relatedBlogs.length > 0" class="space-y-4">
            <div v-for="relatedBlog in relatedBlogs" :key="relatedBlog.id" class="flex items-start">
              <img
                :src="mediaUrl + relatedBlog.thumbnail"
                :alt="relatedBlog.title"
                class="w-16 h-16 object-cover rounded mr-3"
              />
              <div>
                <NuxtLink
                  :to="`/blog/${relatedBlog.slug}`"
                  class="font-medium leading-tight hover:text-blue-600 hover:underline"
                >
                  {{ relatedBlog.title }}
                </NuxtLink>
                <p class="text-gray-500 text-xs mt-1">
                  {{ new Date(relatedBlog.created_at).toLocaleDateString() }}
                </p>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500 text-sm">Không có bài viết liên quan</div>
        </div>
      </div>
    </div>

    <div v-else class="flex items-center justify-center min-h-[400px]">
      <div class="text-center">
        <div class="mb-4">
          <svg class="w-16 h-16 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
        </div>
        <p class="text-gray-500 text-xl font-medium">Không tìm thấy bài viết</p>
        <p class="text-gray-400 mt-2">Bài viết không tồn tại hoặc đã bị xóa</p>
        <NuxtLink to="/blog" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
          Quay lại trang Blog
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { Blog } from '~/lib/schema'

definePageMeta({
  layout: 'main'
})

const { mediaUrl, appUrl } = useRuntimeConfig().public

const route = useRoute()
const { slug } = route.params

const blog = ref<Blog | null>(null)
const relatedBlogs = ref<Blog[]>([])

const fetchBlog = async () => {
  const { data: blogData, error } = await useClientFetch<{ success: boolean; data: Blog }>(`blogs/${slug}`)

  if (!error.value && blogData.value?.success) {
    blog.value = blogData.value.data

    useSeoMeta({
      title: blog.value.title,
      description: blog.value.title,
      ogTitle: blog.value.title,
      ogDescription: blog.value.title,
      ogImage: mediaUrl + blog.value.thumbnail,
      ogUrl: appUrl + `/blog/${blog.value.slug}`,
      ogSiteName: blog.value.title,
      ogType: 'article',
      twitterTitle: blog.value.title,
      twitterDescription: blog.value.title,
      twitterImage: mediaUrl + blog.value.thumbnail,
      twitterCard: 'summary_large_image'
    })

    // Fetch related blogs
    fetchRelatedBlogs()
  }
}

const fetchRelatedBlogs = async () => {
  if (!blog.value) return

  const { data: blogsData, error } = await useClientFetch<{ success: boolean; data: Blog[] }>('blogs')

  if (blogsData.value) {
    // Lọc ra các bài viết khác (không bao gồm bài viết hiện tại) và có trạng thái công khai
    relatedBlogs.value = blogsData.value.data.data.filter((b) => b.id !== blog.value?.id && b.is_public).slice(0, 3) // Chỉ lấy tối đa 3 bài viết
  }
}

onMounted(() => {
  fetchBlog()
})
</script>

<style>
.prose {
  max-width: 100%;
}
.prose img {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin: 1.5rem auto;
}
.prose h2 {
  font-size: 1.5rem;
  margin-top: 1.5rem;
  margin-bottom: 1rem;
  font-weight: 600;
  color: #111827;
}
.prose h3 {
  font-size: 1.25rem;
  margin-top: 1.25rem;
  margin-bottom: 0.75rem;
  font-weight: 600;
  color: #111827;
}
.prose p {
  margin-top: 1rem;
  margin-bottom: 1rem;
  line-height: 1.7;
}
.prose ul {
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
  list-style-type: disc;
  padding-left: 1.5rem;
}
.prose li {
  margin-top: 0.25rem;
  margin-bottom: 0.25rem;
}
</style>
