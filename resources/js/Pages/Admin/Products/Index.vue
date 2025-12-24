<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    DocumentIcon,
    PencilSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { formatCash, apiDelete } from "@/Utils";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    products: {
        type: Object,
        default: () => ({ data: [], total: 0 }),
    },
    categories: {
        type: Array,
        default: () => [],
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

const deleteProduct = async (productId) => {
    if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
        try {
            const response = await apiDelete(`/api/shop/products/${productId}`);

            if (response.ok) {
                router.reload();
            } else {
                alert("Có lỗi xảy ra khi xóa sản phẩm");
            }
        } catch (error) {
            alert("Có lỗi xảy ra khi xóa sản phẩm");
        }
    }
};
</script>

<template>
    <Head title="Quản lý sản phẩm" />

    <h1 class="text-2xl">Quản lý sản phẩm</h1>
    <div class="bg-white rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)]">
        <div class="flex flex-wrap items-center gap-4">
            <Link
                href="/manage/products/create"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg"
                >Thêm sản phẩm</Link
            >
            <input
                type="text"
                placeholder="Tìm theo từ khóa"
                class="px-4 py-2 w-60 focus:outline-none focus:ring focus:ring-blue-300 border border-gray-300 rounded-lg"
            />
            <select
                class="px-4 py-2 border border-gray-300 text-gray-500 rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
            >
                <option selected>Danh mục</option>
                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>
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
                        Tổng sản phẩm
                    </p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ products?.total }}
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <div class="min-w-[800px]">
                <div class="mt-6 bg-gray-100 p-4 rounded-lg">
                    <div
                        class="grid grid-cols-8 gap-4 text-sm font-medium text-gray-500"
                    >
                        <div class="flex items-center">
                            <span class="ml-2">Mã sản phẩm</span>
                        </div>
                        <div>Hình ảnh</div>
                        <div>Tên sản phẩm</div>
                        <div>Giá</div>
                        <div>Phân loại</div>
                        <div>Ngày tạo</div>
                        <div colspan="2">Thao tác</div>
                    </div>
                </div>
                <div v-if="products?.data?.length > 0" class="mb-5">
                    <div
                        v-for="product in products.data"
                        :key="product.id"
                        class="grid px-4 grid-cols-8 gap-4 items-center text-sm text-gray-700 border-b border-gray-200"
                    >
                        <div class="flex items-center">
                            <Link
                                :href="`/manage/products/${product.id}`"
                                class="ml-2 text-blue-500 hover:underline"
                                >{{ product.id }}</Link
                            >
                        </div>
                        <div class="py-[5px]">
                            <img
                                :src="mediaUrl + product.thumbnail"
                                alt="product"
                                class="w-12 h-12 object-cover rounded-lg"
                            />
                        </div>
                        <div class="line-clamp-2">{{ product.name }}</div>
                        <div>{{ formatCash(product.price?.toString()) }} đ</div>
                        <div>{{ product.category?.name }}</div>
                        <div>
                            {{
                                new Date(
                                    product.created_at
                                ).toLocaleDateString()
                            }}
                        </div>
                        <div>
                            <Link
                                :href="`/manage/products/${product.id}`"
                                class="text-indigo-500 p-3"
                            >
                                <PencilSquareIcon
                                    class="w-5 h-5 inline-block"
                                />
                                Sửa
                            </Link>
                        </div>
                        <div>
                            <button
                                @click="deleteProduct(product.id)"
                                class="text-red-500 p-3"
                            >
                                <TrashIcon class="w-5 h-5 inline-block" /> Xóa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div
            v-if="products?.links?.length > 3"
            class="mt-6 flex justify-center gap-2"
        >
            <template v-for="(link, index) in products.links" :key="index">
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
