<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Input from "@/Components/Form/Input.vue";
import InputError from "@/Components/Form/InputError.vue";
import Loading from "@/Components/UI/Loading.vue";
import Toast from "@/Components/UI/Toast.vue";
import { apiPost } from "@/Utils";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    category: {
        type: Object,
        default: null,
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const isEdit = !!props.category;

const form = ref({
    id: props.category?.id || "",
    name: props.category?.name || "",
    image: props.category?.image || "",
});

const errorList = ref({
    name: [],
    image: [],
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
        formData.append(key, form.value[key]);
    }

    try {
        const response = await apiPost("/api/shop/categories/store", formData);

        submitting.value = false;

        if (!response.ok) {
            const errorData = await response.json();
            if (response.status === 422) {
                errorList.value = errorData.errors || {};
            } else {
                showToastFunc("Lưu danh mục thất bại", "error");
            }
            return;
        }

        showToastFunc("Lưu danh mục thành công", "success");
        if (!isEdit) {
            clearForm();
        }
    } catch (error) {
        submitting.value = false;
        showToastFunc("Lưu danh mục thất bại", "error");
    }
};

const clearError = () => {
    errorList.value = {
        name: [],
        image: [],
    };
};

const clearForm = () => {
    form.value = {
        ...form.value,
        name: "",
        image: "",
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

const onFileChange = (e) => {
    const file = e.target.files[0];
    form.value.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        document
            .getElementById("img_thumbnail")
            ?.setAttribute("src", e.target.result);
    };
    reader.readAsDataURL(file);
};
</script>

<template>
    <Head :title="isEdit ? 'Sửa danh mục' : 'Thêm danh mục'" />

    <h1 class="text-2xl">{{ isEdit ? "Sửa danh mục" : "Thêm danh mục" }}</h1>
    <div class="rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)] bg-white">
        <form
            enctype="multipart/form-data"
            id="form"
            @submit.prevent="onSubmit"
            class="max-w-2xl"
        >
            <div class="">
                <label for="name" class="block text-gray-600"
                    >Tên danh mục</label
                >
                <Input v-model="form.name" :errors="errorList?.name?.[0]" />
            </div>
            <div class="">
                <label for="thumbnail" class="block text-gray-600"
                    >Thumbnail</label
                >
                <input
                    @change="onFileChange"
                    type="file"
                    id="thumbnail"
                    :class="[
                        errorList?.image?.[0]
                            ? 'border border-red-500'
                            : 'border border-gray-300',
                    ]"
                    class="rounded-lg border border-gray-300 w-full"
                />
                <InputError :message="errorList?.image?.[0]" />
                <img
                    id="img_thumbnail"
                    alt="thumbnail"
                    v-if="form.image"
                    :src="
                        typeof form.image === 'string'
                            ? mediaUrl + form.image
                            : ''
                    "
                    class="w-28 h-28 object-cover rounded-lg"
                />
            </div>

            <div class="mt-[10px] flex gap-2">
                <button
                    type="submit"
                    class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                >
                    <Loading v-if="submitting" />
                    <span v-else>Lưu danh mục</span>
                </button>
                <button
                    @click.prevent="router.visit('/manage/categories')"
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
