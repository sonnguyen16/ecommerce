<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    DocumentIcon,
    PencilSquareIcon,
    TrashIcon,
    EyeIcon,
} from "@heroicons/vue/24/outline";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { apiDelete } from "@/Utils";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    blogs: {
        type: Object,
        default: () => ({ data: [], total: 0 }),
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const goToPage = (url) => {
    if (url) {
        router.visit(url);
    }
};

const deleteBlog = async (blogId) => {
    if (confirm("Bạn có chắc chắn muốn xóa bài viết này không?")) {
        try {
            const response = await apiDelete(`/api/blogs/${blogId}`);

            if (response.ok) {
                router.reload();
            } else {
                alert("Có lỗi xảy ra khi xóa bài viết");
            }
        } catch (error) {
            alert("Có lỗi xảy ra khi xóa bài viết");
        }
    }
};
</script>

<template>
    <Head title="Quản lý bài viết" />

    <h1 class="text-2xl">Quản lý bài viết</h1>
    <div class="bg-white rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)]">
        <div class="flex flex-wrap items-center gap-4">
            <Link
                href="/manage/blogs/create"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg"
                >Thêm bài viết</Link
            >
            <input
                type="text"
                placeholder="Tìm theo từ khóa"
                class="px-4 py-2 w-60 focus:outline-none focus:ring focus:ring-blue-300 border border-gray-300 rounded-lg"
            />
        </div>

        <div class="flex gap-4 mt-5">
            <div class="flex items-center gap-3 p-4 bg-blue-50 rounded-lg">
                <div
                    class="flex items-center justify-center w-10 h-10 bg-blue-500 text-white rounded-full"
                >
                    <DocumentIcon class="w-6 h-6" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Tổng bài viết
                    </p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ blogs?.total }}
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <div class="min-w-[700px]">
                <div class="mt-6 bg-gray-100 p-4 rounded-lg">
                    <div
                        class="grid grid-cols-7 gap-4 text-sm font-medium text-gray-500"
                    >
                        <div>ID</div>
                        <div>Hình ảnh</div>
                        <div class="col-span-2">Tiêu đề</div>
                        <div>Trạng thái</div>
                        <div>Ngày tạo</div>
                        <div>Thao tác</div>
                    </div>
                </div>
                <div v-if="blogs?.data?.length > 0" class="mb-5">
                    <div
                        v-for="blog in blogs.data"
                        :key="blog.id"
                        class="grid px-4 grid-cols-7 gap-4 items-center text-sm text-gray-700 border-b border-gray-200 py-3"
                    >
                        <div>{{ blog.id }}</div>
                        <div>
                            <img
                                :src="mediaUrl + blog.thumbnail"
                                alt="blog"
                                class="w-12 h-12 object-cover rounded-lg"
                            />
                        </div>
                        <div class="col-span-2 line-clamp-2">
                            {{ blog.title }}
                        </div>
                        <div>
                            <span
                                :class="
                                    blog.is_public
                                        ? 'bg-green-100 text-green-600'
                                        : 'bg-yellow-100 text-yellow-600'
                                "
                                class="px-2 py-1 rounded-full text-xs"
                            >
                                {{ blog.is_public ? "Công khai" : "Nháp" }}
                            </span>
                        </div>
                        <div>
                            {{ new Date(blog.created_at).toLocaleDateString() }}
                        </div>
                        <div class="flex gap-2">
                            <Link
                                :href="`/blog/${blog.slug}`"
                                class="text-blue-500"
                            >
                                <EyeIcon class="w-5 h-5 inline-block" />
                            </Link>
                            <Link
                                :href="`/manage/blogs/${blog.id}`"
                                class="text-indigo-500"
                            >
                                <PencilSquareIcon
                                    class="w-5 h-5 inline-block"
                                />
                            </Link>
                            <button
                                @click="deleteBlog(blog.id)"
                                class="text-red-500"
                            >
                                <TrashIcon class="w-5 h-5 inline-block" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div
            v-if="blogs?.links?.length > 3"
            class="mt-6 flex justify-center gap-2"
        >
            <template v-for="(link, index) in blogs.links" :key="index">
                <button
                    @click="goToPage(link.url)"
                    :disabled="!link.url"
                    :class="[
                        link.active
                            ? 'bg-indigo-600 text-white'
                            : 'bg-white text-gray-700 hover:bg-gray-100',
                        !link.url
                            ? 'opacity-50 cursor-not-allowed'
                            : 'cursor-pointer',
                    ]"
                    class="px-3 py-2 border rounded-md text-sm"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>

<style scoped></style>
