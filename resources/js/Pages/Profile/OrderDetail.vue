<script setup>
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { formatCash } from "@/Utils";

defineOptions({
    layout: ProfileLayout,
});

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

// Get first order detail for display (or compute from order details)
const orderDetail = computed(() => {
    return props.order?.order_details?.[0] || null;
});

// Status labels
const statusLabels = {
    1: { text: "Chờ xác nhận", class: "bg-yellow-100 text-yellow-600" },
    2: { text: "Đang vận chuyển", class: "bg-blue-100 text-blue-600" },
    3: { text: "Đã giao", class: "bg-green-100 text-green-600" },
    4: { text: "Đã hủy", class: "bg-red-100 text-red-600" },
};

const getStatusLabel = (statusCode) => {
    return (
        statusLabels[statusCode] || {
            text: "Không xác định",
            class: "bg-gray-100 text-gray-600",
        }
    );
};
</script>

<template>
    <Head title="Chi tiết đơn hàng" />

    <div class="bg-white rounded-md p-4">
        <!-- Back button -->
        <div class="mb-4">
            <Link
                href="/profile/tracking"
                class="text-indigo-600 hover:text-indigo-700 flex items-center gap-1"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Quay lại
            </Link>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Customer Info & Order Details -->
            <div class="col-span-2">
                <!-- Customer Info -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3 text-indigo-700">
                        Thông tin khách hàng
                    </h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <p><strong>Tên: </strong>{{ order?.name }}</p>
                            <p>
                                <strong>Số điện thoại: </strong
                                >{{ order?.phone }}
                            </p>
                            <p>
                                <strong>Ngày tạo: </strong
                                >{{
                                    new Date(order?.created_at).toLocaleString()
                                }}
                            </p>
                            <p>
                                <strong>Địa chỉ: </strong>{{ order?.address }}
                            </p>
                        </div>
                        <div class="space-y-2">
                            <p>
                                <strong>Phường/Xã: </strong
                                >{{ order?.ward?.name || order?.ward }}
                            </p>
                            <p>
                                <strong>Quận/Huyện: </strong
                                >{{ order?.district?.name || order?.district }}
                            </p>
                            <p>
                                <strong>Tỉnh/Thành phố: </strong
                                >{{ order?.province?.name || order?.province }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div>
                    <h2 class="text-xl font-semibold mb-3 text-indigo-700">
                        Chi tiết đơn hàng
                    </h2>

                    <!-- Header -->
                    <div
                        class="hidden md:grid grid-cols-7 items-center bg-gray-200 p-3 rounded-xl mb-3"
                    >
                        <div class="col-span-2 text-gray-700 ps-3">
                            Mã đơn hàng:
                            <span class="text-blue-700">#{{ order?.id }}</span>
                        </div>
                        <div class="col-span-2 text-gray-500 text-center">
                            Trạng thái
                        </div>
                        <div class="col-span-1 text-gray-500 text-center">
                            Đơn giá
                        </div>
                        <div class="col-span-1 text-gray-500 text-center">
                            Số lượng
                        </div>
                        <div class="col-span-1 text-gray-500 text-end">
                            Tổng tiền
                        </div>
                    </div>

                    <!-- Order items -->
                    <template
                        v-for="detail in order?.order_details"
                        :key="detail.id"
                    >
                        <div
                            class="grid grid-cols-1 md:grid-cols-7 items-center p-3 border-b border-gray-200"
                        >
                            <!-- Product info -->
                            <div class="col-span-2 flex items-center gap-3">
                                <img
                                    v-if="detail.product?.thumbnail"
                                    :src="mediaUrl + detail.product.thumbnail"
                                    :alt="detail.product?.name"
                                    class="w-16 h-16 object-cover rounded-lg"
                                />
                                <div>
                                    <p
                                        class="font-medium text-gray-800 line-clamp-2"
                                    >
                                        {{ detail.product?.name }}
                                    </p>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-span-2 text-center mt-2 md:mt-0">
                                <span
                                    :class="getStatusLabel(detail.status).class"
                                    class="px-3 py-1 rounded-full text-sm font-medium"
                                >
                                    {{ getStatusLabel(detail.status).text }}
                                </span>
                            </div>

                            <!-- Price -->
                            <div class="col-span-1 text-center mt-2 md:mt-0">
                                {{ formatCash(detail.price?.toString()) }}đ
                            </div>

                            <!-- Quantity -->
                            <div class="col-span-1 text-center mt-2 md:mt-0">
                                {{ detail.quantity }}
                            </div>

                            <!-- Total -->
                            <div
                                class="col-span-1 text-end mt-2 md:mt-0 font-semibold text-red-600"
                            >
                                {{ formatCash(detail.total?.toString()) }}đ
                            </div>
                        </div>
                    </template>

                    <!-- Total -->
                    <div
                        class="flex justify-end p-3 bg-gray-50 rounded-lg mt-3"
                    >
                        <div class="text-right">
                            <p class="text-gray-600">Tổng tiền đơn hàng:</p>
                            <p class="text-2xl font-bold text-red-600">
                                {{ formatCash(order?.total?.toString()) }}đ
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="col-span-1">
                <h2 class="text-xl font-semibold mb-5 text-indigo-700">
                    Thông tin vận chuyển
                </h2>
                <div class="relative border-l border-gray-300 pl-5">
                    <template v-if="orderDetail?.locations?.length > 0">
                        <div
                            v-for="(location, index) in orderDetail.locations"
                            :key="index"
                            class="mb-10 ml-6"
                        >
                            <span
                                :class="[
                                    index === 0
                                        ? 'bg-yellow-500'
                                        : 'bg-gray-200',
                                ]"
                                class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full"
                            ></span>
                            <time
                                class="mb-1 text-sm font-normal text-gray-400"
                            >
                                {{
                                    new Date(
                                        location?.created_at
                                    ).toLocaleDateString()
                                }}
                                <br />{{
                                    new Date(
                                        location?.created_at
                                    ).toLocaleTimeString()
                                }}
                            </time>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ location?.note }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ location?.address }}
                            </p>
                        </div>
                    </template>
                    <template v-else>
                        <p class="text-gray-500">
                            Chưa có thông tin vận chuyển
                        </p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
