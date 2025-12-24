<script setup>
import { ref, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";

const blogs = ref([]);

const fetchBlogs = async () => {
    try {
        const response = await fetch("/api/blogs");
        if (response.ok) {
            const blogData = await response.json();
            if (blogData.data) {
                blogs.value = blogData.data.data.filter(
                    (blog) => blog.is_public
                );
            }
        }
    } catch (error) {
        console.error("Lỗi khi tải danh sách blog:", error);
    }
};

onMounted(() => {
    fetchBlogs();
});
</script>

<template>
    <footer class="">
        <div
            class="grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 hidden md:grid"
        >
            <div>
                <ul class="space-y-1">
                    <h2 class="font-semibold mb-2">Danh sách bài viết</h2>
                    <li v-for="blog in blogs" :key="blog.id">
                        <Link
                            :href="`/blog/${blog.slug}`"
                            class="font-normal text-gray-500 hover:underline"
                        >
                            {{ blog.title }}
                        </Link>
                    </li>
                </ul>
            </div>

            <div>
                <h2 class="mb-2">Hỗ trợ khách hàng</h2>
                <ul class="space-y-1">
                    <li class="font-normal text-gray-500">
                        <a
                            href="tel:0908202570"
                            class="font-normal text-gray-500 hover:underline"
                            >Hotline: 0908.202.570</a
                        >
                    </li>
                    <li>
                        <a
                            href="mailto:hotro@BRTGo.vn"
                            class="font-normal text-gray-500 hover:underline"
                            >Hỗ trợ khách hàng: hotro@teenshop.vn</a
                        >
                    </li>
                    <li>
                        <a
                            href="mailto:security@BRTGo.vn"
                            class="font-normal text-gray-500 hover:underline"
                            >Báo lỗi bảo mật: security@teenshop.vn</a
                        >
                    </li>
                </ul>
                <div class="mt-4">
                    <h2 class="font-semibold">Website mang tính thử nghiệm</h2>
                </div>
            </div>
            <div>
                <h2 class="font-semibold">Phương thức thanh toán</h2>
                <img src="/payment.png" width="150" alt="payment" />
            </div>
            <div class="">
                <h2 class="font-semibold">Kết nối với chúng tôi</h2>
                <img src="/mxh.png" alt="mxh" width="150" />
            </div>
            <div>
                <h2 class="font-semibold">Dịch vụ giao hàng</h2>
                <img class="w-[200px]" src="/logo.png" alt="Logo" />
            </div>
        </div>
    </footer>
</template>

<style scoped></style>
