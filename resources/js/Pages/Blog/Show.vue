<script setup>
import { ref, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    blog: {
        type: Object,
        default: null,
    },
    relatedBlogs: {
        type: Array,
        default: () => [],
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleDateString("vi-VN", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
};
</script>

<template>
    <Head :title="blog?.title || 'Blog'" />

    <div class="container mx-auto px-4 pb-5 pt-1">
        <!-- Breadcrumb -->
        <div v-if="blog" class="text-gray-600 mb-4 font-normal">
            <Link href="/" class="hover:underline text-gray-600 font-normal"
                >Trang chủ</Link
            >
            &gt;
            <span class="text-gray-600 font-normal">{{ blog.title }}</span>
        </div>

        <div v-if="blog" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl overflow-hidden shadow-sm">
                    <!-- Blog Content -->
                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            {{ blog.title }}
                        </h1>

                        <div
                            class="flex items-center text-gray-500 text-sm mb-6"
                        >
                            <span class="mr-4">
                                {{ formatDate(blog.created_at) }}
                            </span>
                        </div>

                        <div
                            class="prose prose-lg max-w-none"
                            v-html="blog.content"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4">
                <!-- Related Posts -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold mb-4">
                        Bài viết liên quan
                    </h3>
                    <div v-if="relatedBlogs.length > 0" class="space-y-4">
                        <div
                            v-for="relatedBlog in relatedBlogs"
                            :key="relatedBlog.id"
                            class="flex items-start"
                        >
                            <img
                                :src="mediaUrl + relatedBlog.thumbnail"
                                :alt="relatedBlog.title"
                                class="w-16 h-16 object-cover rounded mr-3"
                            />
                            <div>
                                <Link
                                    :href="`/blog/${relatedBlog.slug}`"
                                    class="font-medium leading-tight hover:text-blue-600 hover:underline"
                                >
                                    {{ relatedBlog.title }}
                                </Link>
                                <p class="text-gray-500 text-xs mt-1">
                                    {{ formatDate(relatedBlog.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-gray-500 text-sm">
                        Không có bài viết liên quan
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="flex items-center justify-center min-h-[400px]">
            <div class="text-center">
                <div class="mb-4">
                    <svg
                        class="w-16 h-16 text-gray-400 mx-auto"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </div>
                <p class="text-gray-500 text-xl font-medium">
                    Không tìm thấy bài viết
                </p>
                <p class="text-gray-400 mt-2">
                    Bài viết không tồn tại hoặc đã bị xóa
                </p>
                <Link
                    href="/"
                    class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                >
                    Quay lại trang chủ
                </Link>
            </div>
        </div>
    </div>
</template>

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
