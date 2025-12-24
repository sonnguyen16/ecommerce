<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";

// Sidebar collapsed state
const isCollapsed = ref(false);

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
};

// Current path - reactive from Inertia
const page = usePage();
const currentPath = computed(() => {
    const url = page.url;
    return url.split("?")[0];
});

// Helper to check if link is active (including sub-routes)
const isActive = (linkPath) => {
    return currentPath.value.startsWith(linkPath);
};

// Auth user
const user = usePage().props.auth?.user;

// Navigation links
const links = [
    { icon: "orders", text: "Đơn Hàng", to: "/manage/orders" },
    { icon: "products", text: "Sản Phẩm", to: "/manage/products" },
    { icon: "categories", text: "Danh mục", to: "/manage/categories" },
    { icon: "blogs", text: "Bài viết", to: "/manage/blogs" },
];

// Logout
const logout = () => {
    router.post("/logout");
};
</script>

<template>
    <div class="h-screen w-screen flex overflow-hidden">
        <!-- Sidebar -->
        <aside
            :class="[isCollapsed ? 'w-16' : 'w-72']"
            class="hidden md:flex flex-col bg-white border-r transition-all duration-300"
        >
            <!-- Sidebar Header -->
            <div
                :class="[isCollapsed ? 'justify-center' : 'justify-between']"
                class="h-14 flex items-center px-4 bg-indigo-600 text-white"
            >
                <button
                    @click="toggleSidebar"
                    class="hover:bg-indigo-700 p-1 rounded"
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
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>
                <Link v-show="!isCollapsed" href="/" class="text-xl font-bold">
                    Ecommerce
                </Link>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 py-4">
                <ul class="space-y-1">
                    <li v-for="link in links" :key="link.to">
                        <Link
                            :href="link.to"
                            :class="[
                                isCollapsed ? 'justify-center' : '',
                                isActive(link.to)
                                    ? 'bg-indigo-50 text-indigo-600 border-r-4 border-indigo-600'
                                    : 'text-gray-600 hover:bg-gray-50',
                            ]"
                            class="flex items-center gap-3 py-3 px-4 transition-colors"
                        >
                            <!-- Orders Icon -->
                            <svg
                                v-if="link.icon === 'orders'"
                                class="w-6 h-6 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>

                            <!-- Products Icon -->
                            <svg
                                v-if="link.icon === 'products'"
                                class="w-6 h-6 flex-shrink-0"
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

                            <!-- Categories Icon -->
                            <svg
                                v-if="link.icon === 'categories'"
                                class="w-6 h-6 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                />
                            </svg>

                            <!-- Blogs Icon -->
                            <svg
                                v-if="link.icon === 'blogs'"
                                class="w-6 h-6 flex-shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                                />
                            </svg>

                            <span v-show="!isCollapsed" class="font-medium">{{
                                link.text
                            }}</span>
                        </Link>
                    </li>
                </ul>
            </nav>

            <!-- User Info -->
            <div v-if="user" class="p-4 border-t">
                <div
                    :class="[
                        isCollapsed ? 'justify-center' : 'justify-between',
                    ]"
                    class="flex items-center"
                >
                    <div v-show="!isCollapsed" class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center"
                        >
                            <span class="text-indigo-600 font-semibold">{{
                                user.name?.charAt(0)
                            }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ user.name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ user.phone }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="logout"
                        class="text-gray-400 hover:text-gray-600"
                        title="Đăng xuất"
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
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header
                class="h-14 bg-indigo-600 text-white flex items-center justify-between px-6"
            >
                <div class="flex items-center gap-3">
                    <svg
                        class="w-7 h-7"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                        />
                    </svg>
                    <span class="text-lg font-medium">Shop của tôi</span>
                </div>

                <div class="flex items-center gap-6">
                    <button
                        class="hover:bg-indigo-700 p-2 rounded-full transition-colors"
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
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                            />
                        </svg>
                    </button>
                    <Link
                        href="/profile"
                        class="hover:bg-indigo-700 p-2 rounded-full transition-colors"
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
                    </Link>
                    <button
                        class="hover:bg-indigo-700 p-2 rounded-full transition-colors"
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
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-100 p-5">
                <slot />
            </main>
        </div>
    </div>
</template>
