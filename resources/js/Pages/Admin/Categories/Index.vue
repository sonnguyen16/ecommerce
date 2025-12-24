<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    DocumentIcon,
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { apiDelete } from "@/Utils";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    categories: {
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

const deleteCategory = async (categoryId) => {
    if (confirm("Bạn có chắc chắn muốn xóa danh mục này không?")) {
        try {
            const response = await apiDelete(
                `/api/shop/categories/${categoryId}`
            );

            if (response.ok) {
                router.reload();
            } else {
                alert("Có lỗi xảy ra khi xóa danh mục");
            }
        } catch (error) {
            alert("Có lỗi xảy ra khi xóa danh mục");
        }
    }
};
</script>

<template>
    <Head title="Quản lý danh mục" />

    <h1 class="text-2xl">Quản lý danh mục</h1>
    <div class="bg-white rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)]">
        <div class="flex flex-wrap items-center gap-4">
            <Link
                href="/manage/categories/create"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg"
                >Thêm danh mục</Link
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
                        Tổng danh mục
                    </p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ categories?.total }}
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <div class="min-w-[600px]">
                <div class="mt-6 bg-gray-100 p-4 rounded-lg">
                    <div
                        class="grid grid-cols-6 gap-4 text-sm font-medium text-gray-500"
                    >
                        <div>ID</div>
                        <div>Hình ảnh</div>
                        <div>Tên danh mục</div>
                        <div>Slug</div>
                        <div>Ngày tạo</div>
                        <div>Thao tác</div>
                    </div>
                </div>
                <div v-if="categories?.data?.length > 0" class="mb-5">
                    <div
                        v-for="category in categories.data"
                        :key="category.id"
                        class="grid px-4 grid-cols-6 gap-4 items-center text-sm text-gray-700 border-b border-gray-200 py-3"
                    >
                        <div>{{ category.id }}</div>
                        <div>
                            <img
                                :src="mediaUrl + category.image"
                                alt="category"
                                class="w-12 h-12 object-cover rounded-lg"
                            />
                        </div>
                        <div>{{ category.name }}</div>
                        <div>{{ category.slug }}</div>
                        <div>
                            {{
                                new Date(
                                    category.created_at
                                ).toLocaleDateString()
                            }}
                        </div>
                        <div class="flex gap-2">
                            <Link
                                :href="`/manage/categories/${category.id}`"
                                class="text-indigo-500"
                            >
                                <PencilSquareIcon
                                    class="w-5 h-5 inline-block"
                                />
                            </Link>
                            <button
                                @click="deleteCategory(category.id)"
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
            v-if="categories?.links?.length > 3"
            class="mt-6 flex justify-center gap-2"
        >
            <template v-for="(link, index) in categories.links" :key="index">
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
