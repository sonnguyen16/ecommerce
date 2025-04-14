<template>
  <h1 class="text-2xl">Quản lý bài viết</h1>
  <div class="bg-white rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)]">
    <div class="flex flex-wrap items-center gap-4">
      <NuxtLink :to="`/manage/blogs/${null}`" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
        Thêm bài viết
      </NuxtLink>
      <input
        type="text"
        placeholder="Tìm theo từ khóa"
        class="px-4 py-2 w-60 focus:outline-none focus:ring focus:ring-blue-300 border border-gray-300 rounded-lg"
      />
    </div>

    <div class="flex gap-4 mt-5">
      <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
        <div class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full">
          <DocumentIcon class="w-6 h-6" />
        </div>
        <div>
          <p class="text-sm font-medium text-gray-500">Tổng bài viết</p>
          <p class="text-2xl font-semibold text-gray-900">{{ data?.data?.total }}</p>
        </div>
      </div>
    </div>

    <div class="w-full overflow-x-auto">
      <div class="min-w-[800px]">
        <div class="mt-6 bg-gray-100 p-4 rounded-lg">
          <div class="grid grid-cols-6 gap-4 text-sm font-medium text-gray-500">
            <div class="flex items-center">
              <span class="ml-2">Mã bài viết</span>
            </div>
            <div>Hình ảnh</div>
            <div>Tiêu đề</div>
            <div>Trạng thái</div>
            <div>Ngày tạo</div>
            <div colspan="2">Thao tác</div>
          </div>
        </div>
        <div v-if="data?.data?.data?.length > 0" class="mb-5">
          <div
            v-for="(blog, i) in data.data.data"
            class="grid px-4 grid-cols-6 gap-4 items-center text-sm text-gray-700 border-b border-gray-200"
          >
            <div class="flex items-center">
              <NuxtLink :to="`/manage/blogs/${blog.id}`" class="ml-2 text-blue-500 hover:underline">
                {{ blog.id }}
              </NuxtLink>
            </div>
            <div class="py-[5px]">
              <img :src="mediaUrl + blog.thumbnail" alt="blog" class="w-12 h-12 object-cover rounded-lg" />
            </div>
            <div class="line-clamp-2">{{ blog.title }}</div>
            <div>
              <span
                :class="[
                  blog.is_public ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800',
                  'px-2 inline-flex text-xs leading-5 font-semibold rounded-full'
                ]"
              >
                {{ blog.is_public ? 'Công khai' : 'Nháp' }}
              </span>
            </div>
            <div>{{ new Date(blog.created_at).toLocaleDateString() }}</div>
            <div class="flex items-center">
              <NuxtLink :to="`/manage/blogs/${blog.slug}`" class="text-indigo-500 p-3">
                <PencilSquareIcon class="w-5 h-5 inline-block" /> Sửa
              </NuxtLink>
              <button @click="deleteBlog(blog.id)" class="text-red-500 p-3">
                <TrashIcon class="w-5 h-5 inline-block" /> Xóa
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AdminPagination v-if="data" :pagination_data="data.data" @page-change="goToPage" />
  </div>
</template>

<script setup lang="ts">
import { DocumentIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import type { PaginationData } from '~/lib/schema'
import type { Blog } from '~/lib/schema'

definePageMeta({
  layout: 'admin',
  middleware: 'auth'
})

const { mediaUrl } = useRuntimeConfig().public

let data: Ref<PaginationData<Blog> | null> = ref(null)

const fetchData = async (page: number) => {
  const { data: newData, error } = await useClientFetch<PaginationData<Blog>>(`blogs?page=${page}`)

  if (!error.value) {
    data.value = newData.value
  } else {
    console.log(error)
  }
}

onBeforeMount(async () => {
  await fetchData(1)
})

const goToPage = async (p: number) => {
  if (data.value && p > 0 && p <= data.value.data.last_page) {
    await fetchData(p)
  }
}

const deleteBlog = async (blogId: number) => {
  if (confirm('Bạn có chắc chắn muốn xóa bài viết này không?')) {
    const { error } = await useClientFetch(`blogs/${blogId}`, {
      method: 'DELETE'
    })

    if (!error.value) {
      await fetchData(data.value?.data.current_page || 1)
    } else {
      alert('Có lỗi xảy ra khi xóa bài viết')
    }
  }
}
</script>

<style scoped></style>
