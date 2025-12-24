<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import {
    CheckBadgeIcon,
    PlusIcon,
    StarIcon,
    MinusIcon,
} from "@heroicons/vue/24/solid";
import MainLayout from "@/Layouts/MainLayout.vue";
import Product from "@/Components/Product/Product.vue";
import Toast from "@/Components/UI/Toast.vue";
import { formatCash, apiPost } from "@/Utils";

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    similarProducts: {
        type: Array,
        default: () => [],
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const user = usePage().props.auth?.user;
const quantityInput = ref(1);
const message = ref("Thêm vào giỏ hàng thành công");
const status = ref("success");
const showToast = ref(false);

const showFullDescription = ref(false);
const descriptionIsTooLong = ref(false);
const MAX_DESCRIPTION_LENGTH = 300;

const discount = computed(() => {
    if (!props.product) return 0;
    return Math.round(
        ((props.product.price - props.product.sale_price) /
            props.product.price) *
            100
    );
});

const form = computed(() => {
    if (!props.product) return { product_id: 0, quantity: 1, total: 0 };

    return {
        product_id: props.product.id,
        quantity: quantityInput.value,
        total: quantityInput.value * props.product.sale_price,
    };
});

onMounted(() => {
    if (
        props.product?.description &&
        props.product.description.length > MAX_DESCRIPTION_LENGTH
    ) {
        descriptionIsTooLong.value = true;
    }
});

const toggleDescription = () => {
    showFullDescription.value = !showFullDescription.value;
};

const increaseQuantity = () => {
    quantityInput.value++;
};

const decreaseQuantity = () => {
    if (quantityInput.value > 1) {
        quantityInput.value--;
    }
};

const addToCart = async () => {
    if (!props.product) return;

    if (!user) {
        // Save to localStorage for guest users
        let cart = [];
        if (localStorage.getItem("cart")) {
            cart = JSON.parse(localStorage.getItem("cart"));
        }

        const productIndex = cart.findIndex(
            (item) => item.product_id === props.product.id
        );
        if (productIndex > -1) {
            cart[productIndex].quantity += form.value.quantity;
        } else {
            cart.push({
                ...form.value,
                product: props.product,
            });
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        // Dispatch event để Navbar cập nhật số lượng
        window.dispatchEvent(
            new CustomEvent("cart-updated", { detail: { count: cart.length } })
        );
        showToastFunction("Thêm vào giỏ hàng thành công", "success");
        return;
    }

    try {
        const response = await apiPost("/api/add-to-cart", form.value);

        if (!response.ok) {
            showToastFunction(
                "Đã có lỗi xảy ra, vui lòng thử lại sau",
                "error"
            );
        } else {
            // Dispatch event để Navbar cập nhật số lượng
            window.dispatchEvent(new CustomEvent("cart-updated"));
            showToastFunction("Thêm vào giỏ hàng thành công", "success");
        }
    } catch (error) {
        showToastFunction("Đã có lỗi xảy ra, vui lòng thử lại sau", "error");
    }
};

const buyNow = async () => {
    await addToCart();
    router.visit("/cart");
};

const showToastFunction = (msg, s) => {
    message.value = msg;
    status.value = s;
    showToast.value = true;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

// Parse attributes safely
const parsedAttributes = computed(() => {
    try {
        return JSON.parse(String(props.product?.attributes || "{}"));
    } catch {
        return {};
    }
});
</script>

<template>
    <Head :title="product.name" />

    <div v-if="product" class="">
        <!-- Breadcrumb -->
        <div class="text-gray-600 mb-4 font-normal">
            <Link href="/">Trang chủ</Link> > {{ product.name }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Left Column -->
            <div class="col-span-1">
                <div class="bg-white rounded-xl p-5 space-y-4 sticky top-3">
                    <div class="image-gallery">
                        <img
                            :src="mediaUrl + product.thumbnail"
                            alt="Book Cover"
                            class="w-full rounded-lg border"
                        />
                    </div>
                    <div class="flex space-x-2">
                        <template
                            v-for="image in product.images"
                            :key="image.id"
                        >
                            <img
                                :src="mediaUrl + image.path"
                                alt="Thumbnail"
                                class="w-20 h-20 object-cover rounded-md border"
                            />
                        </template>
                    </div>
                    <div class="text-gray-700 mt-4">
                        <ul class="space-y-2">
                            <li class="font-normal flex">
                                <CheckBadgeIcon
                                    class="w-7 h-7 text-indigo-700 me-2"
                                />
                                Đảm bảo hàng chính hãng.
                            </li>
                            <li class="font-normal flex">
                                <CheckBadgeIcon
                                    class="w-7 h-7 text-indigo-700 me-2"
                                />
                                Hoàn tiền 100% nếu phát hiện hàng giả.
                            </li>
                            <li class="font-normal flex">
                                <CheckBadgeIcon
                                    class="w-7 h-7 text-indigo-700 me-2"
                                />
                                Giao hàng toàn quốc, chỉ từ 2h.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4 col-span-2">
                <div class="bg-white rounded-xl p-5">
                    <div class="flex gap-2 mb-1">
                        <img src="/topdeal2.png" alt="topdeal" width="80" />
                        <img src="/chinhhang.png" alt="chinhhang" width="90" />
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-900">
                        {{ product.name }}
                    </h1>
                    <div class="flex my-2 items-center">
                        <span class="me-2">4.8</span>
                        <template v-for="i in 5" :key="i">
                            <StarIcon class="w-5 h-5 text-yellow-300" />
                        </template>
                        <span class="font-normal text-gray-500 ms-2"
                            >(2032)</span
                        >
                        <span
                            class="w-[1px] h-[16px] mt-1 bg-gray-300 ms-3"
                        ></span>
                        <span class="font-normal text-gray-500 ms-2"
                            >Đã bán {{ product.sold }}</span
                        >
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-red-700 font-semibold text-2xl"
                            >{{
                                formatCash(product.sale_price?.toString())
                            }}
                            đ</span
                        >
                        <span
                            class="block p-1 rounded-lg bg-gray-100 font-normal text-sm"
                            >-{{ discount }}%</span
                        >
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5">
                    <div class="">
                        <p class="text-gray-600">Thông tin vận chuyển</p>
                        <div class="flex items-center justify-between">
                            <p class="font-normal">Giao đến HCM, Việt Nam</p>
                            <p class="text-indigo-700 cursor-pointer">Đổi</p>
                        </div>
                    </div>
                    <hr class="my-3" />

                    <!-- Shipping Option 1 -->
                    <div class="flex items-center">
                        <span class="text-red-700 font-bold mr-2">NOW</span>
                        <div class="flex-1">
                            <p class="text-gray-800">Giao siêu tốc 2h</p>
                        </div>
                    </div>
                    <p class="text-gray-500 font-normal">
                        Trước 12h hôm nay: 25.000₫
                    </p>

                    <!-- Shipping Option 2 -->
                    <div class="flex items-center mt-2">
                        <span class="text-indigo-700 mr-2">🌅</span>
                        <div class="flex-1">
                            <p class="text-gray-800">Giao đúng sáng mai</p>
                        </div>
                    </div>
                    <p class="text-gray-500 font-normal">
                        8h - 12h, 24/08: 16.500₫
                    </p>
                </div>

                <div class="bg-white rounded-xl p-5">
                    <h2 class="text-lg font-semibold mb-2">
                        Thông tin chi tiết
                    </h2>
                    <div class="grid grid-cols-2 gap-y-2">
                        <template
                            v-for="(value, name) in parsedAttributes"
                            :key="name"
                        >
                            <div
                                class="text-gray-600 py-2 border-b border-gray-200 font-normal"
                            >
                                {{ name }}
                            </div>
                            <div
                                class="py-2 border-b border-gray-200 font-normal"
                            >
                                {{ value }}
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-5 flex flex-col">
                    <h2 class="text-lg font-semibold mb-2">Mô tả sản phẩm</h2>
                    <div
                        :class="{
                            blurred:
                                !showFullDescription && descriptionIsTooLong,
                        }"
                        class="text-gray-700"
                    >
                        <div class="space-y-2 text-justify font-normal">
                            {{ product.description }}
                        </div>
                    </div>
                    <button
                        v-if="descriptionIsTooLong"
                        @click="toggleDescription"
                        class="text-indigo-700 mt-2"
                    >
                        {{ showFullDescription ? "Thu gọn" : "Xem thêm" }}
                    </button>
                </div>

                <div class="bg-white rounded-xl p-5">
                    <p class="text-xl mb-3">Sản phẩm tương tự</p>
                    <div
                        class="flex gap-3 lg:flex-wrap lg:overflow-hidden overflow-x-auto"
                    >
                        <template v-for="p in similarProducts" :key="p.id">
                            <Product
                                v-if="
                                    p.id !== product.id &&
                                    p.category_id == product.category_id
                                "
                                class="basis-[130px]"
                                :product="p"
                                :media-url="mediaUrl"
                            />
                        </template>
                    </div>
                </div>
            </div>
            <div class="col-span-1">
                <div class="bg-white rounded-xl p-5 sticky top-3">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <img src="/logo.svg" alt="Logo" class="w-20 mr-2" />
                            <div>
                                <p>BRTGo Trading</p>
                                <div class="flex items-center">
                                    <CheckBadgeIcon
                                        class="h-4 w-4 text-indigo-700 me-1"
                                    ></CheckBadgeIcon>
                                    <p class="text-indigo-700">OFFICIAL</p>
                                    <div
                                        class="w-[1px] h-4 bg-gray-300 mx-2"
                                    ></div>
                                    <div
                                        class="flex items-center ml-2 text-gray-600"
                                    >
                                        <span class="text-sm">4.7</span>
                                        <StarIcon
                                            class="h-3 w-3 text-yellow-300 mx-1"
                                        ></StarIcon>
                                        <span class="text-sm"
                                            >(5.4tr+ đánh giá)</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Selector -->
                    <div class="mb-4">
                        <p class="text-gray-600">Số Lượng</p>
                        <div class="flex items-center mt-2">
                            <button
                                aria-label="decrease"
                                @click.prevent="decreaseQuantity"
                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l"
                            >
                                <MinusIcon
                                    class="h-4 w-4 text-gray-600"
                                ></MinusIcon>
                            </button>
                            <input
                                type="text"
                                class="w-10 h-8 text-center border-t border-b border-gray-300"
                                v-model="quantityInput"
                            />
                            <button
                                aria-label="increase"
                                @click.prevent="increaseQuantity"
                                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r"
                            >
                                <PlusIcon
                                    class="h-4 w-4 text-gray-600"
                                ></PlusIcon>
                            </button>
                        </div>
                    </div>

                    <!-- Subtotal -->
                    <div class="my-5">
                        <p class="text-lg mb-3">Tạm tính</p>
                        <p class="text-red-700 font-semibold text-2xl">
                            {{ formatCash(form.total?.toString()) }} đ
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <button
                            @click.prevent="buyNow"
                            class="w-full bg-red-700 text-white py-2 rounded-lg"
                        >
                            Mua ngay
                        </button>
                        <button
                            @click.prevent="addToCart"
                            class="w-full border border-indigo-700 text-indigo-700 py-2 rounded-lg"
                        >
                            Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="flex flex-col items-center justify-center min-h-[50vh]">
        <h1 class="text-2xl font-semibold mb-4">Không tìm thấy sản phẩm</h1>
        <p class="text-gray-500 mb-6">
            Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.
        </p>
        <Link href="/" class="bg-indigo-600 text-white px-6 py-2 rounded-lg"
            >Quay lại trang chủ</Link
        >
    </div>
    <Toast :message="message" :type="status" :show="showToast" />
</template>

<style scoped>
/* Blurred effect */
.blurred {
    position: relative;
    overflow: hidden;
    max-height: 250px;
}
.blurred::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 200px;
    background: linear-gradient(to top, #f9fafb, rgba(249, 250, 251, 0));
}
</style>
