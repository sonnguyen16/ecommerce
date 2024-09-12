<script lang="ts" setup>
import {
    PhoneIcon,
    EnvelopeIcon,
    LockClosedIcon,
    KeyIcon,
    TrashIcon,
    CameraIcon
} from "@heroicons/vue/24/outline";
import ProfileLayout from "~/layouts/ProfileLayout.vue";

definePageMeta({
  middleware: 'auth'
})

const [ profileResponse, provincesResponse ] : any = await Promise.all([
  useFetchData({url: 'auth/profile', requiresToken: true}),
  useFetchData({url: 'provinces'}),
])

const provincesData : Ref<any> = provincesResponse.data
const profileData : any = profileResponse.data?.value?.data


const genderOptions = [
  { code: 1, name: 'Nam' },
  { code: 2, name: 'Nữ' },
  { code: 3, name: 'Khác' }
]

const form = ref({
  name: profileData?.name || '',
  address: profileData?.address || '',
  province: profileData?.province || '',
  district: profileData?.district || '',
  ward: profileData?.ward || '',
  gender: profileData?.gender || '',
  birthday: profileData?.birthday || '',
  avatar: profileData?.avatar || ''
})

const errorList = ref({
  name: [],
  address: [],
  province: [],
  district: [],
  ward: [],
  gender: [],
  birthday: [],
  avatar: []
})

const districts = computed(() => {
  if(form.value.province){
    return provincesData.value.find((item: any) => item.code == form.value.province)?.districts
  }
  return []
})

const wards = computed(() => {
  if(form.value.district){
    return districts.value.find((item: any) => item.code == form.value.district)?.wards
  }
  return []
})


</script>
<template>
  <ProfileLayout>
      <div class="bg-white rounded-md p-4">
        <div class="grid grid-cols-10">
          <div class="col-span-6  border-r-[1px] pr-10">
            <form enctype="multipart/form-data">
              <div class="grid grid-cols-5 gap-5">
                <div class="col-span-5">
                  <h3 class="text-xl font-semibold">Thông tin cá nhân</h3>
                </div>
                <div class="flex space-x-4">
                  <div class="w-28 h-28 bg-blue-100 rounded-full flex items-center justify-center relative">
                    <div class="absolute bottom-0 right-0 bg-gray-200 rounded-full p-1 border border-white">
                      <label for="avatar" class="cursor-pointer">
                        <CameraIcon class="h-6 w-6 text-gray-600" />
                      </label>
                      <input type="file" id="avatar" class="hidden" />
                    </div>
                  </div>
                </div>
                <div class="col-span-4">
                  <!-- Họ & Tên field -->
                  <div class="flex">
                    <label class="w-1/5 text-gray-700 font-medium">Họ & Tên</label>
                    <Input class="w-4/5" v-model="form.name" type="text" placeholder="Nhập họ tên" :errors="errorList.name?.[0]"/>
                  </div>
                  <div class="flex">
                    <label class="w-1/5 text-gray-700">Ngày sinh</label>
                    <Input class="w-4/5" v-model="form.birthday" type="date" :errors="errorList.birthday?.[0]"/>
                  </div>

                </div>
                <div class="col-span-5 ms-3">
                  <div class="flex">
                    <label class="w-1/5 text-gray-700">Giới tính</label>
                    <Select class="w-4/5" optionDefault="Chọn giới tính" :options="genderOptions" v-model="form.gender" :errors="errorList.gender?.[0]"/>
                  </div>

                  <div class="flex">
                    <label class="w-1/5 text-gray-700">Tỉnh/Thành phố</label>
                    <Select class="w-4/5" optionDefault="Chọn tỉnh/thành phố" v-model="form.province" :options="provincesData" :errors="errorList.province?.[0]"/>
                  </div>

                  <div class="flex">
                    <label class="w-1/5 text-gray-700">Quận/Huyện</label>
                    <Select class="w-4/5" optionDefault="Chọn quận/huyện" v-model="form.district" :options="districts"  :errors="errorList.district?.[0]"/>
                  </div>

                  <div class="flex">
                    <label class="w-1/5 text-gray-700">Phường/Xã</label>
                    <Select class="w-4/5" optionDefault="Chọn phường/xã" v-model="form.ward" :options="wards"  :errors="errorList.ward?.[0]"/>
                  </div>

                  <div class="flex ">
                    <label class="w-1/5 text-gray-700">Địa chỉ</label>
                    <Input class="w-4/5" v-model="form.address" type="text" placeholder="Nhập địa chỉ" :errors="errorList.address?.[0]"/>
                  </div>

                  <button type="submit" class="bg-blue-500 text-white mt-4 rounded py-2 px-4 hover:bg-blue-600 ms-[20%]">Lưu thay đổi</button>
                </div>
              </div>
            </form>
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

/* assets/css/main.css */
.page-enter-active, .page-leave-active {
  transition: opacity 0.5s;
}

.page-enter, .page-leave-to /* .page-leave-active trong <2.1.8 */ {
  opacity: 0;
}

</style>
