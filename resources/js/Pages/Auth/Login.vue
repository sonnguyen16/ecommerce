<script setup>
import { ref } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Input from "@/Components/Form/Input.vue";
import Loading from "@/Components/UI/Loading.vue";
import Toast from "@/Components/UI/Toast.vue";

defineOptions({
    layout: MainLayout,
});

const form = ref({
    phone: "",
    password: "",
});

const submitting = ref(false);
const errorList = ref({
    phone: [],
    password: [],
});

const showToast = ref(false);
const message = ref("");
const type = ref("");

const showToastFunc = (msg, toastType) => {
    showToast.value = true;
    message.value = msg;
    type.value = toastType;
};

const clearError = () => {
    errorList.value = {
        phone: [],
        password: [],
    };
};

const onSubmit = async () => {
    clearError();
    submitting.value = true;

    try {
        await router.post("/login", form.value, {
            onError: (errors) => {
                submitting.value = false;
                if (errors.phone) errorList.value.phone = [errors.phone];
                if (errors.password)
                    errorList.value.password = [errors.password];
                if (errors.auth) {
                    showToastFunc(errors.auth, "error");
                }
            },
            onSuccess: () => {
                submitting.value = false;
            },
        });
    } catch (error) {
        submitting.value = false;
        showToastFunc("Đã có lỗi xảy ra, vui lòng thử lại sau", "error");
    }
};
</script>

<template>
    <Head title="Đăng nhập" />

    <div class="flex justify-center py-5">
        <div class="bg-white rounded-lg p-5" style="width: 500px !important">
            <h2 class="text-2xl font-bold text-center text-blue-500 mb-4">
                Đăng nhập
            </h2>

            <form @submit.prevent="onSubmit">
                <div class="">
                    <Input
                        v-model="form.phone"
                        type="text"
                        placeholder="Nhập số điện thoại"
                        :errors="errorList.phone?.[0]"
                    />
                </div>

                <div class="">
                    <Input
                        v-model="form.password"
                        type="password"
                        placeholder="Nhập mật khẩu"
                        :errors="errorList.password?.[0]"
                    />
                </div>

                <button
                    :disabled="submitting"
                    type="submit"
                    class="w-full bg-red-700 text-white py-2 mb-3 rounded-lg"
                >
                    <Loading v-if="submitting" />
                    <span v-else>Đăng nhập</span>
                </button>
            </form>
            <span class="text-gray-500 block text-center">
                Chưa có tài khoản?
                <Link href="/register" class="text-blue-500">Đăng ký</Link>
            </span>
        </div>
    </div>
    <Toast :show="showToast" :message="message" :type="type" />
</template>

<style scoped></style>
