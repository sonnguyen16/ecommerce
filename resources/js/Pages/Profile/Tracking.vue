<script setup>
import { ref, onMounted, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import {
    MagnifyingGlassIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import Toast from "@/Components/UI/Toast.vue";
import { getToastMessage, formatCash } from "@/Utils";

defineOptions({
    layout: ProfileLayout,
});

const props = defineProps({
    orderDetails: {
        type: Object, // Paginated response is an object
        default: () => ({ data: [] }),
    },
    mediaUrl: {
        type: String,
        default: "",
    },
    currentStatus: {
        type: [String, Number],
        default: "all",
    },
});

const showToast = ref(false);
const message = ref("");
const type = ref("");

const showToastFunc = (msg, toastType) => {
    showToast.value = true;
    message.value = msg;
    type.value = toastType;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

onMounted(() => {
    const toastMessage = getToastMessage();
    if (toastMessage) {
        showToastFunc(toastMessage.message, toastMessage.type);
    }
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

// Get order details data directly from paginated response
const orderDetailsList = computed(() => {
    return props.orderDetails?.data || [];
});

// Navigate to filter by status
const filterByStatus = (status) => {
    const url =
        status === "all"
            ? "/profile/tracking"
            : `/profile/tracking?status=${status}`;
    router.visit(url, { preserveState: false });
};

const isActived = (value) => {
    const current = props.currentStatus;
    return String(current) === String(value)
        ? "text-blue-500 border-b-2 border-blue-500"
        : "text-gray-500";
};

const viewDetail = (orderId) => {
    router.visit(`/profile/tracking/${orderId}`);
};

// Pagination
const goToPage = (url) => {
    if (url) {
        router.visit(url, { preserveState: true, preserveScroll: true });
    }
};

// Pagination info
const paginationInfo = computed(() => {
    const { current_page, last_page, from, to, total } = props.orderDetails;
    return { currentPage: current_page, lastPage: last_page, from, to, total };
});

// Visible pages with limited display (1 ... 4 5 6 ... 10)
const visiblePages = computed(() => {
    const currentPage = props.orderDetails?.current_page || 1;
    const lastPage = props.orderDetails?.last_page || 1;
    const delta = 2;
    const pages = [];

    pages.push({ page: 1, url: getPageUrl(1) });

    let rangeStart = Math.max(2, currentPage - delta);
    let rangeEnd = Math.min(lastPage - 1, currentPage + delta);

    if (rangeStart > 2) {
        pages.push({ page: "...", url: null });
    }

    for (let i = rangeStart; i <= rangeEnd; i++) {
        pages.push({ page: i, url: getPageUrl(i) });
    }

    if (rangeEnd < lastPage - 1) {
        pages.push({ page: "...", url: null });
    }

    if (lastPage > 1) {
        pages.push({ page: lastPage, url: getPageUrl(lastPage) });
    }

    return pages;
});

// Helper to get page URL (preserve status filter)
const getPageUrl = (page) => {
    const baseUrl = "/profile/tracking";
    const status = props.currentStatus;
    if (status && status !== "all") {
        return `${baseUrl}?status=${status}&page=${page}`;
    }
    return `${baseUrl}?page=${page}`;
};
</script>

<template>
    <Head title="Đơn hàng của tôi" />

    <div class="bg-white rounded-md p-4">
        <!-- Header -->
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">
            Đơn hàng của tôi
        </h1>

        <!-- Tabs -->
        <div
            class="flex space-x-4 border-b border-gray-300 mb-4 overflow-x-auto"
        >
            <button
                @click.prevent="filterByStatus('all')"
                :class="[isActived('all'), 'py-2 px-4 whitespace-nowrap']"
            >
                Tất cả đơn
            </button>
            <button
                @click.prevent="filterByStatus(1)"
                :class="[isActived(1), 'py-2 px-4 whitespace-nowrap']"
            >
                Chờ xác nhận
            </button>
            <button
                @click.prevent="filterByStatus(2)"
                :class="[isActived(2), 'py-2 px-4 whitespace-nowrap']"
            >
                Đang vận chuyển
            </button>
            <button
                @click.prevent="filterByStatus(3)"
                :class="[isActived(3), 'py-2 px-4 whitespace-nowrap']"
            >
                Đã giao
            </button>
            <button
                @click.prevent="filterByStatus(4)"
                :class="[isActived(4), 'py-2 px-4 whitespace-nowrap']"
            >
                Đã hủy
            </button>
        </div>

        <!-- Search bar -->
        <div class="flex items-center mb-6">
            <div class="relative w-full">
                <input
                    type="text"
                    class="border border-gray-300 rounded w-full py-2 pl-10 pr-4 focus:outline-none focus:border-blue-500"
                    placeholder="Tìm đơn hàng theo Mã đơn hàng, Nhà bán hoặc Tên sản phẩm"
                />
                <span
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                >
                    <MagnifyingGlassIcon class="h-5 w-5" />
                </span>
            </div>
        </div>

        <!-- Empty orders message -->
        <div class="">
            <template v-if="orderDetailsList.length == 0">
                <div class="flex justify-center mb-4">
                    <img
                        src="/empty-order.png"
                        alt="Empty order illustration"
                        class="w-60"
                    />
                </div>
                <p class="text-gray-500 text-center">Chưa có đơn hàng</p>
            </template>

            <template v-if="orderDetailsList.length > 0">
                <!-- Desktop Header -->
                <div
                    class="hidden md:grid grid-cols-7 items-center px-3 bg-gray-100 p-3 rounded-lg mb-3"
                >
                    <div class="col-span-2 text-gray-600 font-medium">
                        Sản phẩm
                    </div>
                    <div
                        class="col-span-2 text-gray-600 text-center font-medium"
                    >
                        Trạng thái
                    </div>
                    <div
                        class="col-span-1 text-gray-600 text-center font-medium"
                    >
                        Đơn giá
                    </div>
                    <div
                        class="col-span-1 text-gray-600 text-center font-medium"
                    >
                        Số lượng
                    </div>
                    <div class="col-span-1 text-gray-600 text-end font-medium">
                        Tổng tiền
                    </div>
                </div>

                <!-- Order items -->
                <template v-for="detail in orderDetailsList" :key="detail.id">
                    <div
                        @click="viewDetail(detail.order_id)"
                        class="grid grid-cols-1 md:grid-cols-7 items-center p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors"
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
                                <p class="text-sm text-gray-500">
                                    Mã đơn: #{{ detail.id }}
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
                            <span class="md:hidden text-gray-500"
                                >Đơn giá:
                            </span>
                            {{ formatCash(detail.price?.toString()) }}đ
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-1 text-center mt-2 md:mt-0">
                            <span class="md:hidden text-gray-500"
                                >Số lượng:
                            </span>
                            {{ detail.quantity }}
                        </div>

                        <!-- Total -->
                        <div
                            class="col-span-1 text-end mt-2 md:mt-0 font-semibold text-red-600"
                        >
                            <span class="md:hidden text-gray-500 font-normal"
                                >Tổng:
                            </span>
                            {{ formatCash(detail.total?.toString()) }}đ
                        </div>
                    </div>
                </template>

                <!-- Pagination -->
                <div
                    v-if="orderDetails.last_page > 1"
                    class="flex items-center justify-between mt-6 pt-4 border-t"
                >
                    <div class="text-sm text-gray-500">
                        Hiển thị {{ paginationInfo.from }} -
                        {{ paginationInfo.to }} trong tổng số
                        {{ paginationInfo.total }} chi tiết đơn hàng
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Previous -->
                        <button
                            @click="goToPage(orderDetails.prev_page_url)"
                            :disabled="!orderDetails.prev_page_url"
                            :class="[
                                'px-3 py-2 rounded-lg flex items-center gap-1',
                                orderDetails.prev_page_url
                                    ? 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                                    : 'bg-gray-50 text-gray-300 cursor-not-allowed',
                            ]"
                        >
                            <ChevronLeftIcon class="w-4 h-4" />
                            Trước
                        </button>

                        <!-- Page numbers -->
                        <div class="flex items-center gap-1">
                            <template
                                v-for="(item, index) in visiblePages"
                                :key="index"
                            >
                                <!-- Ellipsis -->
                                <span
                                    v-if="item.page === '...'"
                                    class="px-2 py-2 text-gray-400"
                                >
                                    ...
                                </span>
                                <!-- Page number -->
                                <button
                                    v-else
                                    @click="goToPage(item.url)"
                                    :class="[
                                        'px-3 py-2 rounded-lg text-sm font-medium',
                                        item.page === orderDetails.current_page
                                            ? 'bg-indigo-600 text-white'
                                            : 'bg-gray-100 hover:bg-gray-200 text-gray-700',
                                    ]"
                                >
                                    {{ item.page }}
                                </button>
                            </template>
                        </div>

                        <!-- Next -->
                        <button
                            @click="goToPage(orderDetails.next_page_url)"
                            :disabled="!orderDetails.next_page_url"
                            :class="[
                                'px-3 py-2 rounded-lg flex items-center gap-1',
                                orderDetails.next_page_url
                                    ? 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                                    : 'bg-gray-50 text-gray-300 cursor-not-allowed',
                            ]"
                        >
                            Sau
                            <ChevronRightIcon class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <Toast :show="showToast" :message="message" :type="type" />
</template>
