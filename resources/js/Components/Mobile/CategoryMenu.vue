<script setup>
import { router } from "@inertiajs/vue3";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
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

const viewCategory = (category) => {
    router.visit(`/categories/${category.slug}`);
};
</script>

<template>
    <div
        id="category-menu"
        :class="[show ? 'left-0' : '-left-full']"
        class="border-r bg-white px-2 py-3 fixed top-0 bottom-[50px] z-[9999] md:hidden transition-all duration-500"
    >
        <h4 class="font-semibold ms-3">Danh mục</h4>
        <ul class="mt-3">
            <li
                @click.prevent="viewCategory(category)"
                v-for="category in categories"
                :key="category.name"
                class="flex items-center p-2 gap-2 cursor-pointer"
            >
                <img
                    :src="mediaUrl + category.image"
                    alt="category"
                    class="w-8 h-8 rounded-xl object-cover"
                />
                <span class="font-normal">{{ category.name }}</span>
            </li>
        </ul>
    </div>
</template>

<style scoped></style>
