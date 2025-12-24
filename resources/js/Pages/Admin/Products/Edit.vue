<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Input from "@/Components/Form/Input.vue";
import InputError from "@/Components/Form/InputError.vue";
import TextArea from "@/Components/Form/TextArea.vue";
import Select from "@/Components/Form/Select.vue";
import Loading from "@/Components/UI/Loading.vue";
import Toast from "@/Components/UI/Toast.vue";
import { apiPost } from "@/Utils";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    product: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const isEdit = !!props.product;

const form = ref({
    id: props.product?.id || "",
    name: props.product?.name || "",
    description: props.product?.description || "",
    unit: props.product?.unit || "",
    price: props.product?.price || 0,
    sale_price: props.product?.sale_price || 0,
    quantity: props.product?.quantity || 0,
    thumbnail: props.product?.thumbnail || "",
    category_id: props.product?.category_id || "",
    seo_description: props.product?.seo_description || "",
    seo_title: props.product?.seo_title || "",
    seo_url: props.product?.seo_url || "",
    attributes: "",
    images: [],
});

// Parse attributes if exists
onMounted(() => {
    if (props.product?.attributes) {
        try {
            let result = "";
            for (const [key, value] of Object.entries(
                JSON.parse(props.product.attributes)
            )) {
                result += `${key}: ${value}\n`;
            }
            form.value.attributes = result;
        } catch (e) {
            form.value.attributes = props.product.attributes;
        }
    }
});

const error_list = ref({
    name: [],
    description: [],
    unit: [],
    price: [],
    sale_price: [],
    quantity: [],
    thumbnail: [],
    category_id: [],
    seo_description: [],
    seo_title: [],
    seo_url: [],
    attributes: [],
    images: [],
});

const submitting = ref(false);
const showToast = ref(false);
const message = ref("");
const type = ref("");

const onSubmit = async () => {
    clearError();
    submitting.value = true;
    let formData = new FormData();
    for (let key in form.value) {
        if (key !== "images") {
            formData.append(key, form.value[key]);
        } else {
            for (let i = 0; i < form.value.images.length; i++) {
                formData.append("images[]", form.value.images[i]);
            }
        }
    }

    try {
        const response = await apiPost("/api/shop/products/store", formData);

        submitting.value = false;

        if (!response.ok) {
            const errorData = await response.json();
            if (response.status === 422) {
                error_list.value = errorData.errors || {};
            } else {
                showToastFunc("Lưu sản phẩm thất bại", "error");
            }
            return;
        }

        showToastFunc("Lưu sản phẩm thành công", "success");
        if (!isEdit) {
            clearForm();
        }
    } catch (error) {
        submitting.value = false;
        showToastFunc("Lưu sản phẩm thất bại", "error");
    }
};

const showToastFunc = (msg, toastType) => {
    showToast.value = true;
    message.value = msg;
    type.value = toastType;
    setTimeout(() => {
        showToast.value = false;
    }, 3000);
};

const clearError = () => {
    error_list.value = {
        name: [],
        description: [],
        unit: [],
        price: [],
        sale_price: [],
        quantity: [],
        thumbnail: [],
        category_id: [],
        seo_description: [],
        seo_title: [],
        seo_url: [],
        attributes: [],
        images: [],
    };
};

const clearForm = () => {
    form.value = {
        id: "",
        name: "",
        description: "",
        unit: "",
        price: 0,
        sale_price: 0,
        quantity: 0,
        thumbnail: "",
        category_id: "",
        seo_description: "",
        seo_title: "",
        seo_url: "",
        attributes: "",
        images: [],
    };
};

const onFileChange = (e) => {
    const file = e.target.files[0];
    form.value.thumbnail = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        document
            .getElementById("img_thumbnail")
            ?.setAttribute("src", e.target.result);
    };
    reader.readAsDataURL(file);
};

const onImagesChange = (e) => {
    form.value.images = e.target.files;

    for (let i = 0; i < e.target.files.length; i++) {
        const reader = new FileReader();
        reader.onload = (event) => {
            document
                .getElementById(`image${i}`)
                ?.setAttribute("src", event.target.result);
        };
        reader.readAsDataURL(e.target.files[i]);
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Sửa sản phẩm' : 'Thêm sản phẩm'" />

    <h1 class="text-2xl">{{ isEdit ? "Sửa sản phẩm" : "Thêm sản phẩm" }}</h1>
    <div
        class="rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)] bg-white flex flex-col"
    >
        <form
            enctype="multipart/form-data"
            id="form"
            @submit.prevent="onSubmit"
        >
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div>
                    <div class="">
                        <label for="name" class="block text-gray-600"
                            >Tên sản phẩm</label
                        >
                        <Input
                            v-model="form.name"
                            :errors="error_list?.name?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="description" class="block text-gray-600"
                            >Mô tả</label
                        >
                        <TextArea
                            v-model="form.description"
                            :errors="error_list?.description?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="unit" class="block text-gray-600"
                            >Đơn vị</label
                        >
                        <Input
                            v-model="form.unit"
                            :errors="error_list?.unit?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="price" class="block text-gray-600"
                            >Giá gốc</label
                        >
                        <Input
                            type="number"
                            v-model="form.price"
                            :errors="error_list?.price?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="sale_price" class="block text-gray-600"
                            >Giá sale</label
                        >
                        <Input
                            type="number"
                            v-model="form.sale_price"
                            :errors="error_list?.sale_price?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="quantity" class="block text-gray-600"
                            >Số lượng</label
                        >
                        <Input
                            type="number"
                            v-model="form.quantity"
                            :errors="error_list?.quantity?.[0]"
                        />
                    </div>
                </div>

                <!-- Right Column -->
                <div class="flex flex-col">
                    <div class="">
                        <label for="category_id" class="block text-gray-600"
                            >Danh mục</label
                        >
                        <Select
                            optionDefault="Chọn danh mục"
                            v-model="form.category_id"
                            :options="categories || []"
                            :errors="error_list?.category_id?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="seo_description" class="block text-gray-600"
                            >SEO Mô tả</label
                        >
                        <TextArea
                            v-model="form.seo_description"
                            :errors="error_list?.seo_description?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="seo_title" class="block text-gray-600"
                            >SEO Tiêu đề</label
                        >
                        <Input
                            v-model="form.seo_title"
                            :errors="error_list?.seo_title?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="seo_url" class="block text-gray-600"
                            >SEO URL</label
                        >
                        <Input
                            v-model="form.seo_url"
                            :errors="error_list?.seo_url?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="attributes" class="block text-gray-600"
                            >Thuộc tính</label
                        >
                        <TextArea
                            v-model="form.attributes"
                            :errors="error_list?.attributes?.[0]"
                        />
                    </div>
                </div>

                <div>
                    <div class="">
                        <label for="thumbnail" class="block text-gray-600"
                            >Thumbnail</label
                        >
                        <input
                            @change="onFileChange"
                            type="file"
                            id="thumbnail"
                            :class="[
                                error_list?.thumbnail?.[0]
                                    ? 'border border-red-500'
                                    : 'border border-gray-300',
                            ]"
                            class="rounded-lg border border-gray-300 w-full"
                        />
                        <InputError :message="error_list?.thumbnail?.[0]" />
                        <img
                            id="img_thumbnail"
                            alt="thumbnail"
                            v-if="form.thumbnail"
                            :src="
                                typeof form.thumbnail === 'string'
                                    ? mediaUrl + form.thumbnail
                                    : ''
                            "
                            class="w-28 h-28 object-cover rounded-lg mb-5"
                        />
                    </div>

                    <div class="">
                        <label for="images" class="block text-gray-600"
                            >Hình ảnh</label
                        >
                        <input
                            @change="onImagesChange"
                            type="file"
                            id="images"
                            multiple
                            :class="[
                                error_list?.images?.[0]
                                    ? 'border border-red-500'
                                    : 'border border-gray-300',
                            ]"
                            class="rounded-lg border border-gray-300 w-full"
                        />
                        <InputError :message="error_list?.images?.[0]" />
                        <div class="flex-wrap flex gap-4">
                            <template
                                v-for="(image, index) in product?.images || []"
                                :key="index"
                            >
                                <img
                                    alt="image"
                                    :id="`image${index}`"
                                    :src="mediaUrl + image.path"
                                    class="w-28 h-28 object-cover rounded-lg"
                                />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button
                    type="submit"
                    class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                >
                    <Loading v-if="submitting" />
                    <span v-else>Lưu sản phẩm</span>
                </button>
                <button
                    @click.prevent="router.visit('/manage/products')"
                    class="px-6 py-[10px] bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600"
                >
                    <span>Thoát</span>
                </button>
            </div>
        </form>
    </div>
    <Toast :message="message" :type="type" :show="showToast" />
</template>

<style scoped></style>
