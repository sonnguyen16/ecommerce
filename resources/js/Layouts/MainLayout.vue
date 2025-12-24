<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import Navbar from "@/Components/Home/Navbar.vue";
import Footer from "@/Components/Home/Footer.vue";
import MobileNavigation from "@/Components/Mobile/Navigation.vue";
import MobileCategoryMenu from "@/Components/Mobile/CategoryMenu.vue";

defineProps({
    categories: {
        type: Array,
        default: () => [],
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const showCategoryMenu = ref(false);
const showCategories = () => {
    showCategoryMenu.value = !showCategoryMenu.value;
};

// Close menu when clicking outside
const handleClickOutside = (e) => {
    if (
        e.target.closest("#mobile-navigation") === null &&
        e.target.closest("#category-menu") === null
    ) {
        showCategoryMenu.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div>
        <Navbar />
        <div id="body" class="bg-[#f5f5fa] py-4">
            <div class="container">
                <slot />
            </div>
        </div>
        <div class="bg-white container my-8">
            <Footer />
        </div>
        <MobileNavigation @show-categories="showCategories" />
        <MobileCategoryMenu
            :show="showCategoryMenu"
            :categories="categories"
            :media-url="mediaUrl"
        />
    </div>
</template>

<style scoped></style>
