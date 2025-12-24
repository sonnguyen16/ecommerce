<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { HomeIcon } from "@heroicons/vue/24/solid";
import { FaceSmileIcon } from "@heroicons/vue/24/outline";
import { ShoppingCartIcon } from "@heroicons/vue/24/outline";
import { MapPinIcon } from "@heroicons/vue/24/outline";
import { CheckBadgeIcon } from "@heroicons/vue/24/solid";
import { MagnifyingGlassIcon } from "@heroicons/vue/24/outline";

// Get current path reactively from Inertia
const page = usePage();
const currentPath = computed(() => {
    const url = page.url;
    // Remove query string if present
    return url.split("?")[0];
});

// Cart count - sẽ được cập nhật từ API hoặc localStorage
const cartCount = ref(0);

// Hàm fetch cart count - dùng chung cho cả mount và event
const fetchCartCount = async () => {
    // Check localStorage first for guest users
    const localCart = localStorage.getItem("cart");
    if (localCart) {
        try {
            const parsed = JSON.parse(localCart);
            cartCount.value = parsed?.length || 0;
        } catch (e) {
            cartCount.value = 0;
        }
    }

    // Try to fetch from API for logged in users
    try {
        const response = await fetch("/api/cart");
        if (response.ok) {
            const data = await response.json();
            if (data?.length > 0) {
                cartCount.value = data.length;
            }
        }
    } catch (error) {
        // Guest user - use localStorage count
    }
};

// Handler để cập nhật khi có event cart-updated
const handleCartUpdated = (event) => {
    // Nếu event có detail.count thì dùng luôn, không cần fetch lại
    if (event.detail?.count !== undefined) {
        cartCount.value = event.detail.count;
    } else {
        // Nếu không có count (user đã login) thì fetch lại từ API
        fetchCartCount();
    }
};

// Fetch cart count on mount và lắng nghe event
onMounted(() => {
    fetchCartCount();
    window.addEventListener("cart-updated", handleCartUpdated);
});

// Cleanup event listener khi component unmount
onUnmounted(() => {
    window.removeEventListener("cart-updated", handleCartUpdated);
});

const links = [
    {
        name: "Trang chủ",
        icon: HomeIcon,
        to: "/",
    },
    {
        name: "Tài khoản",
        icon: FaceSmileIcon,
        to: "/profile",
    },
    {
        name: "Giỏ hàng",
        icon: ShoppingCartIcon,
        to: "/cart",
    },
];

// Helper to check if link is active
const isActive = (linkPath) => {
    if (linkPath === "/") {
        return currentPath.value === "/";
    }
    return currentPath.value.startsWith(linkPath);
};
</script>

<template>
    <nav class="py-[10px] border-b lg:block hidden">
        <div class="container flex justify-between items-center">
            <Link href="/">
                <img class="w-[200px]" src="/logo.png" alt="Logo" />
            </Link>
            <div class="flex-1 mx-20">
                <div class="relative">
                    <span
                        class="absolute top-1/2 left-3 -translate-y-1/2 flex text-gray-700 font-normal"
                    >
                        <MagnifyingGlassIcon class="w-6 h-6 me-1" />
                        Tìm kiếm sản phẩm
                    </span>
                    <input
                        class="rounded-lg border-gray-300 w-full focus:border-blue-400"
                        type="text"
                        id="search"
                    />
                </div>
                <ul class="flex gap-3 mt-2">
                    <li class="text-gray-700 font-normal">điện gia dụng</li>
                    <li class="text-gray-700 font-normal">điện thoại</li>
                    <li class="text-gray-700 font-normal">máy tính</li>
                    <li class="text-gray-700 font-normal">thời trang</li>
                </ul>
            </div>
            <div>
                <ul class="flex gap-6 items-center">
                    <template v-for="link in links" :key="link.to">
                        <li>
                            <Link
                                :class="[
                                    isActive(link.to)
                                        ? 'text-blue-700'
                                        : 'text-gray-500',
                                ]"
                                :href="link.to"
                                class="flex justify-center items-center space-x-1 relative"
                            >
                                <component :is="link.icon" class="w-6 h-6" />
                                <span class="">{{ link.name }}</span>

                                <!-- Hiển thị số lượng sản phẩm trong giỏ hàng -->
                                <span
                                    v-if="link.to === '/cart' && cartCount > 0"
                                    class="absolute -top-2 -right-5 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                                >
                                    {{ cartCount > 99 ? "99+" : cartCount }}
                                </span>
                            </Link>
                        </li>
                    </template>
                </ul>
                <div class="mt-4">
                    <Link href="/" class="flex space-x-1">
                        <MapPinIcon class="w-6 h-6 text-gray-500" />
                        <span class="text-gray-500"
                            >Giao đến:
                            <span class="text-black underline"
                                >HCM, Việt Nam</span
                            ></span
                        >
                    </Link>
                </div>
            </div>
        </div>
    </nav>
    <div class="py-[10px] border-b lg:block hidden">
        <div class="container flex justify-between items-center">
            <ul class="flex gap-5 items-center">
                <li class="text-blue-700 font-bold">Cam kết</li>
                <li>
                    <div class="w-[1px] h-[20px] bg-gray-300"></div>
                </li>
                <li class="flex gap-2">
                    <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
                    <span class="font-normal">100% hàng thật</span>
                </li>
                <li>
                    <div class="w-[1px] h-[20px] bg-gray-300"></div>
                </li>
                <li class="flex gap-2">
                    <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
                    <span class="font-normal">Hoàn 200% nếu hàng giả</span>
                </li>
                <li>
                    <div class="w-[1px] h-[20px] bg-gray-300"></div>
                </li>
                <li class="flex gap-2">
                    <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
                    <span class="font-normal">30 ngày đổi trả</span>
                </li>
                <li>
                    <div class="w-[1px] h-[20px] bg-gray-300"></div>
                </li>
                <li class="flex gap-2">
                    <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
                    <span class="font-normal">Giao nhanh 2h</span>
                </li>
                <li>
                    <div class="w-[1px] h-[20px] bg-gray-300"></div>
                </li>
                <li class="flex gap-2">
                    <CheckBadgeIcon class="w-6 h-6 text-blue-600" />
                    <span class="font-normal">Giá siêu rẻ</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped></style>
