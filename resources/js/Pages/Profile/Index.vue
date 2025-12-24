<script setup>
import { ref, watch, onMounted } from "vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import {
    PhoneIcon,
    EnvelopeIcon,
    LockClosedIcon,
    KeyIcon,
    TrashIcon,
    CameraIcon,
} from "@heroicons/vue/24/outline";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import Input from "@/Components/Form/Input.vue";
import Select from "@/Components/Form/Select.vue";
import Loading from "@/Components/UI/Loading.vue";
import Toast from "@/Components/UI/Toast.vue";
import { getToastMessage, apiPost } from "@/Utils";

defineOptions({
    layout: ProfileLayout,
});

const props = defineProps({
    mediaUrl: {
        type: String,
        default: "",
    },
    provinces: {
        type: Array,
        default: () => [],
    },
    districts: {
        type: Array,
        default: () => [],
    },
    wards: {
        type: Array,
        default: () => [],
    },
});

const user = usePage().props.auth?.user;

const genderOptions = [
    { code: 1, name: "Nam" },
    { code: 2, name: "Nữ" },
    { code: 3, name: "Khác" },
];

const form = ref({
    name: user?.name || "",
    address: user?.address || "",
    province: user?.province || "",
    district: user?.district || "",
    ward: user?.ward || "",
    phone: user?.phone || "",
    email: user?.email || "",
    gender: user?.gender || 1,
    birthday: user?.birthday || "",
    avatar: null,
});

const errorList = ref({
    name: [],
    address: [],
    province: [],
    district: [],
    ward: [],
    gender: [],
    birthday: [],
    avatar: [],
    phone: [],
    email: [],
});

const divAvatar = ref(null);
const provincesData = ref(props.provinces || []);
const districts = ref(props.districts || []);
const wards = ref(props.wards || []);

onMounted(async () => {
    // Fetch provinces if not provided
    if (provincesData.value.length === 0) {
        try {
            const response = await fetch("/api/provinces");
            if (response.ok) {
                provincesData.value = await response.json();
            }
        } catch (error) {
            console.error("Error fetching provinces:", error);
        }
    }

    // Set avatar background
    if (divAvatar.value && user?.avatar) {
        divAvatar.value.style.backgroundImage = `url(${
            props.mediaUrl + user.avatar
        })`;
        divAvatar.value.style.backgroundSize = "cover";
        divAvatar.value.style.backgroundPosition = "center";
    }

    // Check for toast message from sessionStorage
    const toast = getToastMessage();
    if (toast) {
        showToastFunc(toast.message, toast.type);
    }

    // Fetch districts if province is set
    if (form.value.province) {
        await fetchDistricts(form.value.province);
    }
});

const fetchDistricts = async (provinceId) => {
    if (!provinceId) return;
    try {
        const response = await fetch(`/api/districts/${provinceId}`);
        if (response.ok) {
            districts.value = await response.json();
        }
    } catch (error) {
        console.error("Error fetching districts:", error);
    }
};

const fetchWards = async (districtId) => {
    if (!districtId) return;
    try {
        const response = await fetch(`/api/wards/${districtId}`);
        if (response.ok) {
            wards.value = await response.json();
        }
    } catch (error) {
        console.error("Error fetching wards:", error);
    }
};

watch(
    () => form.value.province,
    async (newVal, oldValue) => {
        if (newVal) {
            await fetchDistricts(newVal);
            if (oldValue) {
                form.value.district = "";
                form.value.ward = "";
            }
        }
    }
);

watch(
    () => form.value.district,
    async (newVal, oldValue) => {
        if (newVal) {
            await fetchWards(newVal);
            if (oldValue) {
                form.value.ward = "";
            }
        }
    }
);

const onFileChange = (e) => {
    const file = e.target.files[0];
    form.value.avatar = file;
    const reader = new FileReader();
    reader.onload = (e) => {
        if (divAvatar.value) {
            divAvatar.value.style.backgroundImage = `url(${e.target.result})`;
            divAvatar.value.style.backgroundSize = "cover";
            divAvatar.value.style.backgroundPosition = "center";
        }
    };
    reader.readAsDataURL(file);
};

const submitting = ref(false);
const showToast = ref(false);
const message = ref("");
const type = ref("");

const onSubmit = async () => {
    clearError();
    let formData = new FormData();
    for (const key in form.value) {
        if (form.value[key] !== null) {
            formData.append(key, form.value[key]);
        }
    }

    submitting.value = true;

    try {
        const response = await apiPost("/api/profile/update", formData);

        submitting.value = false;

        if (!response.ok) {
            const errorData = await response.json();
            if (response.status === 422) {
                errorList.value = errorData.errors || {};
                if (errorData.errors?.avatar) {
                    showToastFunc(errorData.errors.avatar[0], "error");
                }
            } else {
                showToastFunc(
                    "Đã có lỗi xảy ra, vui lòng thử lại sau",
                    "error"
                );
            }
        } else {
            showToastFunc("Cập nhật thông tin thành công", "success");
            router.reload();
        }
    } catch (error) {
        submitting.value = false;
        showToastFunc("Đã có lỗi xảy ra, vui lòng thử lại sau", "error");
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
    errorList.value = {
        name: [],
        address: [],
        province: [],
        district: [],
        ward: [],
        gender: [],
        birthday: [],
        avatar: [],
        phone: [],
        email: [],
    };
};
</script>

<template>
    <Head title="Thông tin tài khoản" />

    <div class="bg-white rounded-md p-4">
        <div class="grid md:grid-cols-10">
            <div class="col-span-6 md:border-r-[1px] md:pr-10">
                <form @submit.prevent="onSubmit">
                    <div class="grid md:grid-cols-5">
                        <div class="col-span-5">
                            <h3 class="text-xl font-semibold mb-5">
                                Thông tin cá nhân
                            </h3>
                        </div>
                        <div
                            ref="divAvatar"
                            class="w-28 h-28 bg-blue-100 rounded-full flex items-center justify-center relative md:mb-0 mb-5 mx-auto"
                        >
                            <div
                                class="absolute bottom-0 right-0 bg-gray-200 rounded-full p-1 border border-white"
                            >
                                <label for="avatar" class="cursor-pointer">
                                    <CameraIcon class="h-6 w-6 text-gray-600" />
                                </label>
                                <input
                                    @change="onFileChange"
                                    type="file"
                                    id="avatar"
                                    class="hidden"
                                />
                            </div>
                        </div>
                        <div class="col-span-5 md:col-span-4 ms-3">
                            <div class="flex">
                                <label
                                    class="md:w-1/5 w-2/6 text-gray-700 font-medium"
                                    >Họ & Tên</label
                                >
                                <Input
                                    class="md:w-4/5 w-4/6"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Nhập họ tên"
                                    :errors="errorList.name?.[0]"
                                />
                            </div>
                            <div class="flex">
                                <label class="md:w-1/5 w-2/6 text-gray-700"
                                    >Ngày sinh</label
                                >
                                <Input
                                    class="md:w-4/5 w-4/6"
                                    v-model="form.birthday"
                                    type="date"
                                    :errors="errorList.birthday?.[0]"
                                />
                            </div>
                        </div>
                        <div class="col-span-5 ms-3">
                            <div class="flex">
                                <label class="md:w-1/5 w-2/6 text-gray-700"
                                    >Giới tính</label
                                >
                                <Select
                                    class="md:w-4/5 w-4/6"
                                    optionDefault="Chọn giới tính"
                                    :options="genderOptions"
                                    v-model="form.gender"
                                    :errors="errorList.gender?.[0]"
                                />
                            </div>

                            <div class="flex">
                                <label class="md:w-1/5 w-2/6 text-gray-700"
                                    >Tỉnh/T.phố</label
                                >
                                <Select
                                    class="md:w-4/5 w-4/6"
                                    optionDefault="Chọn tỉnh/thành phố"
                                    v-model="form.province"
                                    :options="provincesData"
                                    :errors="errorList.province?.[0]"
                                />
                            </div>

                            <div class="flex">
                                <label class="md:w-1/5 w-2/6 text-gray-700"
                                    >Quận/Huyện</label
                                >
                                <Select
                                    class="md:w-4/5 w-4/6"
                                    optionDefault="Chọn quận/huyện"
                                    v-model="form.district"
                                    :options="districts"
                                    :errors="errorList.district?.[0]"
                                />
                            </div>

                            <div class="flex">
                                <label class="md:w-1/5 w-2/6 text-gray-700"
                                    >Phường/Xã</label
                                >
                                <Select
                                    class="md:w-4/5 w-4/6"
                                    optionDefault="Chọn phường/xã"
                                    v-model="form.ward"
                                    :options="wards"
                                    :errors="errorList.ward?.[0]"
                                />
                            </div>

                            <div class="flex">
                                <label class="md:w-1/5 w-2/6 text-gray-700"
                                    >Địa chỉ</label
                                >
                                <Input
                                    class="md:w-4/5 w-4/6"
                                    v-model="form.address"
                                    type="text"
                                    placeholder="Nhập địa chỉ"
                                    :errors="errorList.address?.[0]"
                                />
                            </div>

                            <button
                                type="submit"
                                class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-blue-600 md:ms-[20%]"
                            >
                                <Loading v-if="submitting" />
                                <span v-else>Cập nhật</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-span-4 md:ps-10 ps-3 md:mt-0 mt-5 space-y-8">
                <div class="mt-5">
                    <h2 class="font-bold text-gray-800">
                        Số điện thoại và email
                    </h2>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center space-x-2">
                            <PhoneIcon class="h-6 w-6 text-gray-600" />
                            <div class="space-y-1">
                                <p class="text-gray-700">Số điện thoại</p>
                                <p class="text-gray-500">{{ form.phone }}</p>
                            </div>
                        </div>
                        <button
                            class="text-blue-500 border border-blue-500 px-3 py-1 rounded"
                        >
                            Cập nhật
                        </button>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-2">
                            <EnvelopeIcon class="h-6 w-6 text-gray-600" />
                            <div class="space-y-1">
                                <p class="text-gray-700">Địa chỉ email</p>
                                <p class="text-gray-500">{{ form.email }}</p>
                            </div>
                        </div>
                        <button
                            class="text-blue-500 border border-blue-500 px-3 py-1 rounded"
                        >
                            Cập nhật
                        </button>
                    </div>
                </div>

                <div>
                    <h2 class="font-bold text-gray-800">Bảo mật</h2>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-2">
                            <LockClosedIcon class="h-6 w-6 text-gray-600" />
                            <p class="text-gray-700">Thiết lập mật khẩu</p>
                        </div>
                        <button
                            class="text-blue-500 border border-blue-500 px-3 py-1 rounded"
                        >
                            Cập nhật
                        </button>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-2">
                            <KeyIcon class="h-6 w-6 text-gray-600" />
                            <p class="text-gray-700">Thiết lập mã PIN</p>
                        </div>
                        <button
                            class="text-blue-500 border border-blue-500 px-3 py-1 rounded"
                        >
                            Thiết lập
                        </button>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-2">
                            <TrashIcon class="h-6 w-6 text-gray-600" />
                            <p class="text-gray-700">Yêu cầu xóa tài khoản</p>
                        </div>
                        <button
                            class="text-blue-500 border border-blue-500 px-3 py-1 rounded"
                        >
                            Yêu cầu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Toast :show="showToast" :message="message" :type="type" />
</template>
