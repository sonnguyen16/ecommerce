<script setup>
import { computed, ref, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    FaceSmileIcon,
    ShoppingCartIcon,
    HomeIcon,
    Bars3Icon,
} from "@heroicons/vue/24/outline";

// Get current path reactively from Inertia
const page = usePage();
const currentPath = computed(() => {
    const url = page.url;
    return url.split("?")[0];
});

// Cart count for badge
const cartCount = ref(0);

onMounted(async () => {
    // Check localStorage first
    const localCart = localStorage.getItem("cart");
    if (localCart) {
        try {
            const parsed = JSON.parse(localCart);
            cartCount.value = parsed?.length || 0;
        } catch (e) {
            cartCount.value = 0;
        }
    }

    // Try API for logged in users
    try {
        const response = await fetch("/api/cart");
        if (response.ok) {
            const data = await response.json();
            if (data?.length > 0) {
                cartCount.value = data.length;
            }
        }
    } catch (error) {
        // Guest user
    }
});

const links = [
    {
        name: "Trang chủ",
        icon: HomeIcon,
        link: "/",
    },
    {
        name: "Giỏ hàng",
        icon: ShoppingCartIcon,
        link: "/cart",
    },
    {
        name: "Tài khoản",
        icon: FaceSmileIcon,
        link: "/profile",
    },
];

// Helper to check if link is active
const isActive = (linkPath) => {
    if (linkPath === "/") {
        return currentPath.value === "/";
    }
    return currentPath.value.startsWith(linkPath);
};

const emits = defineEmits(["showCategories"]);
</script>

<template>
    <div
        id="mobile-navigation"
        class="md:hidden block fixed bottom-0 w-full h-[50px] bg-white border-t z-50"
    >
        <div
            class="flex justify-between items-center h-full text-gray-700 px-5"
        >
            <template v-for="link in links" :key="link.link">
                <Link
                    :href="link.link"
                    :class="[
                        isActive(link.link)
                            ? 'text-indigo-700'
                            : 'text-gray-700',
                    ]"
                    class="flex flex-col justify-center items-center relative"
                >
                    <component :is="link.icon" class="w-6 h-6" />
                    <span class="text-xs">{{ link.name }}</span>

                    <!-- Cart badge -->
                    <span
                        v-if="link.link === '/cart' && cartCount > 0"
                        class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center text-[10px]"
                    >
                        {{ cartCount > 99 ? "99+" : cartCount }}
                    </span>
                </Link>
            </template>
            <button
                @click.prevent="emits('showCategories')"
                class="flex flex-col justify-center items-center"
            >
                <component :is="Bars3Icon" class="w-6 h-6" />
                <span class="text-xs">Danh mục</span>
            </button>
        </div>
    </div>
</template>

<style scoped></style>
