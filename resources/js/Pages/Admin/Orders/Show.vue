<script setup>
import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Input from "@/Components/Form/Input.vue";
import Select from "@/Components/Form/Select.vue";
import Loading from "@/Components/UI/Loading.vue";
import Toast from "@/Components/UI/Toast.vue";
import { formatCash, apiPost } from "@/Utils";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
    orderDetail: {
        type: Object,
        required: true,
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const statuses = [
    { code: 1, name: "Chờ xác nhận" },
    { code: 2, name: "Đang giao hàng" },
    { code: 3, name: "Đã giao hàng" },
    { code: 4, name: "Đã hủy" },
];

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

const form = ref({
    order_detail_id: props.orderDetail?.id,
    status: String(props.orderDetail?.status || ""),
    address: "",
    note: "",
});

const errorList = ref({
    status: [],
    address: [],
    note: [],
});

const submitting = ref(false);
const showToast = ref(false);
const message = ref("");
const type = ref("");

const onSubmit = async () => {
    clearError();
    submitting.value = true;

    try {
        const response = await apiPost("/api/shop/orders/update", form.value);
        submitting.value = false;

        if (!response.ok) {
            const errorData = await response.json();
            if (response.status === 422) {
                errorList.value = errorData.errors || {};
            } else {
                showToastFunc("Cập nhật trạng thái thất bại", "error");
            }
        } else {
            clearForm();
            showToastFunc("Cập nhật trạng thái thành công", "success");
            router.reload();
        }
    } catch (error) {
        submitting.value = false;
        showToastFunc("Cập nhật trạng thái thất bại", "error");
    }
};

const clearError = () => {
    errorList.value = {
        status: [],
        address: [],
        note: [],
    };
};

const clearForm = () => {
    form.value = {
        ...form.value,
        address: "",
        note: "",
    };
};

const showToastFunc = (msg, toastType) => {
    showToast.value = true;
    message.value = msg;
    type.value = toastType;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};
</script>

<template>
    <Head title="Chi tiết đơn hàng" />

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl">Chi tiết đơn hàng #{{ orderDetail?.id }}</h1>
        <Link
            href="/manage/orders"
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

    <div
        class="bg-white rounded-xl p-4 min-h-[calc(100vh-9.5rem)] grid md:grid-cols-3 gap-6"
    >
        <!-- Customer & Order Info -->
        <div class="col-span-2 space-y-6">
            <!-- Customer Info -->
            <div>
                <h2 class="text-xl font-semibold mb-3 text-indigo-700">
                    Thông tin khách hàng
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p><strong>Tên: </strong>{{ order?.name }}</p>
                        <p>
                            <strong>Số điện thoại: </strong>{{ order?.phone }}
                        </p>
                        <p>
                            <strong>Ngày tạo: </strong
                            >{{ new Date(order?.created_at).toLocaleString() }}
                        </p>
                        <p><strong>Địa chỉ: </strong>{{ order?.address }}</p>
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

            <!-- Order Detail -->
            <div>
                <h2 class="text-xl font-semibold mb-3 text-indigo-700">
                    Chi tiết đơn hàng
                </h2>
                <div class="overflow-x-auto">
                    <!-- Header -->
                    <div
                        class="hidden md:grid grid-cols-7 items-center bg-gray-200 p-3 rounded-xl mb-3 min-w-[600px]"
                    >
                        <div class="col-span-2 text-gray-700 ps-3">
                            Mã đơn hàng:
                            <span class="text-blue-700"
                                >#{{ orderDetail?.id }}</span
                            >
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

                    <!-- Order item -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-7 items-center p-3 border-b border-gray-200 min-w-[600px]"
                    >
                        <!-- Product info -->
                        <div class="col-span-2 flex items-center gap-3">
                            <img
                                v-if="orderDetail?.product?.thumbnail"
                                :src="mediaUrl + orderDetail.product.thumbnail"
                                :alt="orderDetail?.product?.name"
                                class="w-16 h-16 object-cover rounded-lg"
                            />
                            <div>
                                <p
                                    class="font-medium text-gray-800 line-clamp-2"
                                >
                                    {{ orderDetail?.product?.name }}
                                </p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-span-2 text-center mt-2 md:mt-0">
                            <span
                                :class="
                                    getStatusLabel(orderDetail?.status).class
                                "
                                class="px-3 py-1 rounded-full text-sm font-medium"
                            >
                                {{ getStatusLabel(orderDetail?.status).text }}
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="col-span-1 text-center mt-2 md:mt-0">
                            {{ formatCash(orderDetail?.price?.toString()) }}đ
                        </div>

                        <!-- Quantity -->
                        <div class="col-span-1 text-center mt-2 md:mt-0">
                            {{ orderDetail?.quantity }}
                        </div>

                        <!-- Total -->
                        <div
                            class="col-span-1 text-end mt-2 md:mt-0 font-semibold text-red-600"
                        >
                            {{ formatCash(orderDetail?.total?.toString()) }}đ
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Status Form -->
            <div>
                <h2 class="text-xl font-semibold mb-3 text-indigo-700">
                    Cập nhật trạng thái
                </h2>
                <form
                    @submit.prevent="onSubmit"
                    class="max-w-[500px] space-y-3"
                >
                    <Select
                        :errors="errorList?.status?.[0]"
                        v-model="form.status"
                        :options="statuses"
                        optionDefault="Chọn trạng thái"
                    />
                    <Input
                        :errors="errorList?.address?.[0]"
                        v-model="form.address"
                        placeholder="Địa chỉ hiện tại"
                    />
                    <Input
                        :errors="errorList?.note?.[0]"
                        v-model="form.note"
                        placeholder="Ghi chú"
                    />
                    <button
                        type="submit"
                        class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                    >
                        <Loading v-if="submitting" />
                        <span v-else>Lưu trạng thái</span>
                    </button>
                </form>
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
                                index === 0 ? 'bg-yellow-500' : 'bg-gray-200',
                            ]"
                            class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full"
                        ></span>
                        <time class="mb-1 text-sm font-normal text-gray-400">
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
                    <p class="text-gray-500">Chưa có thông tin vận chuyển</p>
                </template>
            </div>
        </div>
    </div>

    <Toast :show="showToast" :message="message" :type="type" />
</template>
