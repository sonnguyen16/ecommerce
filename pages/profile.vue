<script lang="ts" setup>
import MainLayout from "~/layouts/MainLayout.vue";
import {
    PhoneIcon,
    EnvelopeIcon,
    LockClosedIcon,
    KeyIcon,
    TrashIcon,
    PlusIcon
} from "@heroicons/vue/24/outline";
import ProfileLayout from "~/layouts/ProfileLayout.vue";

</script>
<template>
  <ProfileLayout>
    <div class="bg-white rounded-md p-4">
      <div class="grid grid-cols-10">
        <div class="col-span-6  border-r-[1px] pr-10">
          <div class="grid grid-cols-5 gap-5">
            <div class="col-span-5">
              <h3 class="text-xl font-semibold">Thông tin cá nhân</h3>
            </div>
            <div class="flex space-x-4">
              <div class="w-28 h-28 bg-blue-100 rounded-full flex items-center justify-center relative">
                <div class="absolute bottom-0 right-0 bg-gray-200 rounded-full p-1 border border-white">
                  <PlusIcon class="h-6 w-6 text-gray-600" />
                </div>
              </div>
            </div>
            <div class="col-span-4">
              <!-- Họ & Tên field -->
              <div class="flex items-center mb-4">
                <label class="w-1/5 text-gray-700 font-medium">Họ & Tên</label>
                <input
                    type="text"
                    placeholder="Họ & tên"
                    class="w-4/5 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                />
              </div>
              <!-- Nickname field -->
              <div class="flex items-center">
                <label class="w-1/5 text-gray-700 font-medium">Nickname</label>
                <input
                    type="text"
                    placeholder="Thêm nickname"
                    class="w-4/5 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                />
              </div>
            </div>
            <form class="space-y-4 col-span-5 ms-3">
              <!-- Date of Birth -->
              <div class="flex items-center">
                <label class="w-1/4 text-gray-700">Ngày sinh</label>
                <div class="flex space-x-2 w-3/4">
                  <select class="border border-gray-300 rounded p-2 w-1/3">
                    <option>Ngày</option>
                  </select>
                  <select class="border border-gray-300 rounded p-2 w-1/3">
                    <option>Tháng</option>
                  </select>
                  <select class="border border-gray-300 rounded p-2 w-1/3">
                    <option>Năm</option>
                  </select>
                </div>
              </div>

              <!-- Gender -->
              <div class="flex py-2">
                <label class="w-1/4 text-gray-700">Giới tính</label>
                <div class="flex space-x-4 w-3/4">
                  <label class="inline-flex items-center">
                    <input type="radio" name="gender" class="form-radio text-blue-500">
                    <span class="ml-2">Nam</span>
                  </label>
                  <label class="inline-flex items-center">
                    <input type="radio" name="gender" class="form-radio text-blue-500">
                    <span class="ml-2">Nữ</span>
                  </label>
                  <label class="inline-flex items-center">
                    <input type="radio" name="gender" class="form-radio text-blue-500">
                    <span class="ml-2">Khác</span>
                  </label>
                </div>
              </div>

              <!-- Nationality -->
              <div class="flex">
                <label class="w-1/4 text-gray-700">Quốc tịch</label>
                <div class="space-y-5">
                  <select class="border border-gray-300 rounded p-2 w-3/4">
                    <option>Chọn quốc tịch</option>
                  </select>
                  <button type="submit" class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-blue-600">Lưu thay đổi</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-span-4 ps-10 space-y-8">
          <div class="mt-5">
            <h2 class="font-bold text-gray-800">Số điện thoại và email</h2>
            <div class="flex items-center justify-between py-3">
              <div class="flex items-center space-x-2">
                <PhoneIcon class="h-6 w-6 text-gray-600" />
                <div class="space-y-1">
                  <p class="text-gray-700">Số điện thoại</p>
                  <p class="text-gray-500">0817702334</p>
                </div>
              </div>
              <button class="text-blue-500 border border-blue-500 px-3 py-1 rounded">Cập nhật</button>
            </div>
            <div class="flex items-center justify-between py-2">
              <div class="flex items-center space-x-2">
                <EnvelopeIcon class="h-6 w-6 text-gray-600" />
                <div class="space-y-1">
                  <p class="text-gray-700">Địa chỉ email</p>
                  <p class="text-gray-500">sonnv2212@gmail.com</p>
                </div>
              </div>
              <button class="text-blue-500 border border-blue-500 px-3 py-1 rounded">Cập nhật</button>
            </div>
          </div>

          <!-- Security Section -->
          <div>
            <h2 class="font-bold text-gray-800">Bảo mật</h2>
            <div class="flex items-center justify-between py-2">
              <div class="flex items-center space-x-2">
                <LockClosedIcon class="h-6 w-6 text-gray-600" />
                <p class="text-gray-700">Thiết lập mật khẩu</p>
              </div>
              <button class="text-blue-500 border border-blue-500 px-3 py-1 rounded">Cập nhật</button>
            </div>
            <div class="flex items-center justify-between py-2">
              <div class="flex items-center space-x-2">
                <KeyIcon class="h-6 w-6 text-gray-600" />
                <p class="text-gray-700">Thiết lập mã PIN</p>
              </div>
              <button class="text-blue-500 border border-blue-500 px-3 py-1 rounded">Thiết lập</button>
            </div>
            <div class="flex items-center justify-between py-2">
              <div class="flex items-center space-x-2">
                <TrashIcon class="h-6 w-6 text-gray-600" />
                <p class="text-gray-700">Yêu cầu xóa tài khoản</p>
              </div>
              <button class="text-blue-500 border border-blue-500 px-3 py-1 rounded">Yêu cầu</button>
            </div>
          </div>
        </div>

      </div>

    </div>
  </ProfileLayout>
</template>
<style scoped>
* {
  font-weight: 400;
}
</style>
