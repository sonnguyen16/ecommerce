<script setup>
import { ref, onMounted, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Sidebar from "@/Components/Home/Sidebar.vue";
import Product from "@/Components/Product/Product.vue";

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    category: {
        type: Object,
        required: true,
    },
    products: {
        type: Object,
        default: () => ({ data: [], links: [], current_page: 1 }),
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

// Handle page change
const goToPage = (url) => {
    if (url) {
        router.visit(url, { preserveScroll: true });
    }
};
</script>

<template>
    <Head :title="category.name" />

    <div class="grid grid-cols-6 gap-8">
        <div class="col-span-1 xl:block hidden">
            <Sidebar :categories="categories" :media-url="mediaUrl" />
        </div>
        <div class="xl:col-span-5 col-span-full space-y-5">
            <div class="rounded-xl bg-white p-5 py-4">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-indigo-700 font-semibold text-xl">
                        {{ category?.name }}
                    </h1>
                </div>
                <div class="flex gap-3 overflow-x-auto lg:grid lg:grid-cols-6">
                    <template
                        v-for="product in products?.data"
                        :key="product.id"
                    >
                        <Product
                            class="basis-1/6"
                            :product="product"
                            :media-url="mediaUrl"
                        />
                    </template>
                </div>

                <!-- Pagination -->
                <div
                    v-if="products?.links?.length > 3"
                    class="mt-6 flex justify-center gap-2"
                >
                    <template
                        v-for="(link, index) in products.links"
                        :key="index"
                    >
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
        </div>
    </div>
</template>
