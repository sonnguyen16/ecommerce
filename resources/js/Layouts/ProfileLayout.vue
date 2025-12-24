<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import Navbar from "@/Components/Home/Navbar.vue";
import Footer from "@/Components/Home/Footer.vue";

// Current path - reactive from Inertia
const page = usePage();
const currentPath = computed(() => {
    const url = page.url;
    return url.split("?")[0];
});

// Auth user
const user = usePage().props.auth?.user;

// Helper to check if link is active
const isActive = (linkPath) => {
    if (linkPath === "/profile" && currentPath.value === "/profile") {
        return true;
    }
    if (linkPath !== "/profile" && currentPath.value.startsWith(linkPath)) {
        return true;
    }
    return false;
};

// Profile navigation links
const links = [
    { name: "Thông tin tài khoản", to: "/profile", icon: "user" },
    { name: "Quản lý đơn hàng", to: "/profile/tracking", icon: "orders" },
    {
        name: "Shop của tôi",
        to: "/manage/orders",
        icon: "shop",
        requireSeller: true,
    },
];

// Mobile menu
const showCategoryMenu = ref(false);

const toggleCategoryMenu = () => {
    showCategoryMenu.value = !showCategoryMenu.value;
};

// Logout
const logout = () => {
    router.post("/logout");
};

// Click outside handler
const handleClickOutside = (e) => {
    if (!e.target.closest("#mobile-navigation")) {
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
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <!-- Main Content -->
        <main id="body" class="flex-1 bg-gray-100 py-4">
            <div class="container mx-auto px-4">
                <!-- Breadcrumb -->
                <nav class="mb-4">
                    <span class="text-gray-400">
                        <Link href="/" class="hover:text-indigo-600"
                            >Trang chủ</Link
                        >
                        <span class="mx-2">></span>
                        <span>Tài khoản</span>
                    </span>
                </nav>

                <div class="grid md:grid-cols-10 gap-6">
                    <!-- Sidebar (Desktop) -->
                    <aside class="hidden md:block col-span-2">
                        <div class="bg-white rounded-lg p-4">
                            <!-- User Avatar -->
                            <div
                                v-if="user"
                                class="flex items-center gap-3 pb-4 border-b mb-4"
                            >
                                <div
                                    class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-indigo-600 font-bold text-lg"
                                    >
                                        {{ user.name?.charAt(0) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ user.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ user.phone }}
                                    </p>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <ul class="space-y-1">
                                <li v-for="link in links" :key="link.to">
                                    <Link
                                        v-if="
                                            !link.requireSeller ||
                                            (link.requireSeller &&
                                                user?.role === 1)
                                        "
                                        :href="link.to"
                                        :class="[
                                            isActive(link.to)
                                                ? 'bg-indigo-50 text-indigo-600'
                                                : 'text-gray-600 hover:bg-gray-50',
                                        ]"
                                        class="flex items-center gap-3 py-2.5 px-3 rounded-lg transition-colors"
                                    >
                                        <!-- User Icon -->
                                        <svg
                                            v-if="link.icon === 'user'"
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>

                                        <!-- Orders Icon -->
                                        <svg
                                            v-if="link.icon === 'orders'"
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                            />
                                        </svg>

                                        <!-- Shop Icon -->
                                        <svg
                                            v-if="link.icon === 'shop'"
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>

                                        <span class="font-medium">{{
                                            link.name
                                        }}</span>
                                    </Link>
                                </li>

                                <!-- Logout -->
                                <li>
                                    <button
                                        @click="logout"
                                        class="w-full flex items-center gap-3 py-2.5 px-3 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                            />
                                        </svg>
                                        <span class="font-medium"
                                            >Đăng xuất</span
                                        >
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <!-- Page Content -->
                    <div class="col-span-full md:col-span-8">
                        <slot />
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <div class="bg-white">
            <div class="container mx-auto px-4 py-8">
                <Footer />
            </div>
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav
            id="mobile-navigation"
            class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t z-50 pb-safe"
        >
            <div class="flex justify-around py-2">
                <Link
                    href="/"
                    class="flex flex-col items-center px-3 py-1 text-gray-600"
                >
                    <svg
                        class="w-6 h-6"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"
                        />
                        <path
                            d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"
                        />
                    </svg>
                    <span class="text-xs">Trang chủ</span>
                </Link>

                <Link
                    href="/profile"
                    class="flex flex-col items-center px-3 py-1 text-indigo-600"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>
                    <span class="text-xs">Tài khoản</span>
                </Link>

                <Link
                    href="/profile/tracking"
                    class="flex flex-col items-center px-3 py-1 text-gray-600"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                        />
                    </svg>
                    <span class="text-xs">Đơn hàng</span>
                </Link>

                <button
                    @click="logout"
                    class="flex flex-col items-center px-3 py-1 text-gray-600"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        />
                    </svg>
                    <span class="text-xs">Đăng xuất</span>
                </button>
            </div>
        </nav>
    </div>
</template>

<style scoped>
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>
