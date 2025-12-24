<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
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
    blog: {
        type: Object,
        default: null,
    },
    mediaUrl: {
        type: String,
        default: "",
    },
});

const isEdit = !!props.blog;
const editorId = "blog-editor";

const form = ref({
    id: props.blog?.id || "",
    title: props.blog?.title || "",
    content: props.blog?.content || "",
    thumbnail: props.blog?.thumbnail || "",
    is_public: props.blog?.is_public || 0,
});

const error_list = ref({
    title: [],
    content: [],
    thumbnail: [],
    is_public: [],
});

const submitting = ref(false);
const showToast = ref(false);
const message = ref("");
const type = ref("");
let editor = null;

onMounted(() => {
    // Load CKEditor 4 from CDN
    if (!document.getElementById("ckeditor-script")) {
        const script = document.createElement("script");
        script.id = "ckeditor-script";
        script.src = "https://cdn.ckeditor.com/4.20.0/standard-all/ckeditor.js";
        script.async = true;

        script.onload = () => {
            initializeCKEditor();
        };

        document.head.appendChild(script);
    } else {
        initializeCKEditor();
    }
});

const initializeCKEditor = () => {
    if (window.CKEDITOR) {
        window.CKEDITOR.disableAutoInline = true;
        window.CKEDITOR.config.disableNativeSpellChecker = false;
        window.CKEDITOR.config.scayt_autoStartup = false;
        window.CKEDITOR.config.title = false;
        window.CKEDITOR.timestamp = new Date().getTime();
        window.CKEDITOR.config.removePlugins = "exportpdf,scayt,wsc,about";

        if (editor) {
            editor.destroy();
        }

        editor = window.CKEDITOR.replace(editorId, {
            filebrowserUploadUrl: "/api/upload-image",
            toolbar: [
                { name: "document", items: ["Source"] },
                {
                    name: "clipboard",
                    items: [
                        "Cut",
                        "Copy",
                        "Paste",
                        "PasteText",
                        "PasteFromWord",
                        "-",
                        "Undo",
                        "Redo",
                    ],
                },
                {
                    name: "editing",
                    items: ["Find", "Replace", "-", "SelectAll"],
                },
                {
                    name: "basicstyles",
                    items: [
                        "Bold",
                        "Italic",
                        "Underline",
                        "Strike",
                        "Subscript",
                        "Superscript",
                        "-",
                        "RemoveFormat",
                    ],
                },
                {
                    name: "paragraph",
                    items: [
                        "NumberedList",
                        "BulletedList",
                        "-",
                        "Outdent",
                        "Indent",
                        "-",
                        "Blockquote",
                        "-",
                        "JustifyLeft",
                        "JustifyCenter",
                        "JustifyRight",
                        "JustifyBlock",
                    ],
                },
                { name: "links", items: ["Link", "Unlink", "Anchor"] },
                {
                    name: "insert",
                    items: ["Image", "Table", "HorizontalRule", "SpecialChar"],
                },
                {
                    name: "styles",
                    items: ["Styles", "Format", "Font", "FontSize"],
                },
                { name: "colors", items: ["TextColor", "BGColor"] },
                { name: "tools", items: ["Maximize"] },
            ],
            extraPlugins: "image2,uploadimage",
            height: 400,
        });

        editor.on("instanceReady", () => {
            editor.setData(form.value.content);
        });

        editor.on("change", () => {
            form.value.content = editor.getData();
        });
    }
};

const onSubmit = async () => {
    if (editor) {
        form.value.content = editor.getData();
    }

    clearError();
    submitting.value = true;
    let formData = new FormData();
    for (let key in form.value) {
        if (key === "is_public") {
            formData.append(key, form.value[key] === true ? 1 : 0);
        } else {
            formData.append(key, form.value[key]);
        }
    }

    try {
        const response = await apiPost("/api/blogs", formData);

        submitting.value = false;

        if (!response.ok) {
            const errorData = await response.json();
            if (response.status === 422) {
                error_list.value = errorData.errors || {};
            } else {
                showToastFunc("Lưu bài viết thất bại", "error");
            }
            return;
        }

        showToastFunc("Lưu bài viết thành công", "success");
        if (!isEdit) {
            clearForm();
        }
    } catch (error) {
        submitting.value = false;
        showToastFunc("Lưu bài viết thất bại", "error");
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
        title: [],
        content: [],
        thumbnail: [],
        is_public: [],
    };
};

const clearForm = () => {
    form.value = {
        id: "",
        title: "",
        content: "",
        thumbnail: "",
        is_public: 0,
    };

    if (editor) {
        editor.setData("");
    }
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

onBeforeUnmount(() => {
    if (editor) {
        editor.destroy();
        editor = null;
    }
});
</script>

<template>
    <Head :title="isEdit ? 'Sửa bài viết' : 'Thêm bài viết'" />

    <h1 class="text-2xl">{{ isEdit ? "Sửa bài viết" : "Thêm bài viết" }}</h1>
    <div
        class="rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)] bg-white flex flex-col"
    >
        <form
            enctype="multipart/form-data"
            id="form"
            @submit.prevent="onSubmit"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div>
                    <div class="">
                        <label for="title" class="block text-gray-600"
                            >Tiêu đề bài viết</label
                        >
                        <Input
                            v-model="form.title"
                            :errors="error_list?.title?.[0]"
                        />
                    </div>

                    <div class="">
                        <label for="content" class="block text-gray-600 mb-2"
                            >Nội dung</label
                        >
                        <textarea
                            :id="editorId"
                            name="content"
                            style="display: none"
                        ></textarea>
                        <InputError :message="error_list?.content?.[0]" />
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="">
                        <label for="thumbnail" class="block text-gray-600"
                            >Ảnh bài viết</label
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
                        <label for="is_public" class="block text-gray-600"
                            >Trạng thái</label
                        >
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input
                                    type="checkbox"
                                    v-model="form.is_public"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                />
                                <span class="ml-2">Công khai</span>
                            </label>
                        </div>
                        <InputError :message="error_list?.is_public?.[0]" />
                    </div>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button
                    type="submit"
                    class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700"
                >
                    <Loading v-if="submitting" />
                    <span v-else>Lưu bài viết</span>
                </button>
                <button
                    @click.prevent="router.visit('/manage/blogs')"
                    class="px-6 py-[10px] bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600"
                >
                    <span>Thoát</span>
                </button>
            </div>
        </form>
    </div>
    <Toast :message="message" :type="type" :show="showToast" />
</template>

<style scoped>
:deep(.cke_chrome) {
    border: 1px solid #d1d5db !important;
    border-radius: 0.5rem;
    overflow: hidden;
}
</style>
