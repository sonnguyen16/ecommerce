<script lang="ts" setup>
import {
    PhoneIcon,
    EnvelopeIcon,
    LockClosedIcon,
    KeyIcon,
    TrashIcon,
    CameraIcon
} from "@heroicons/vue/24/outline";
import type {User} from "~/lib/schema";
import {MEDIA_ENDPOINT} from "~/lib/constants";

definePageMeta({
   layout: 'profile',
   middleware: 'auth'
})

const genderOptions = [
  {code: 1, name: 'Nam'},
  {code: 2, name: 'Nữ'},
  {code: 3, name: 'Khác'}
]

const form = ref<User>({
  name: '',
  address: '',
  province: '',
  district: '',
  ward: '',
  id: 0,
  password: '',
  phone: '',
  email: '',
  gender: 1,
  created_at: '',
  updated_at: ''
})

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
  email: []
})

const divAvatar = ref<HTMLElement | null>(null);

let profileData = ref(await useAuth().getUser())
let { data: provincesData }  = await useClientFetch("provinces")

if (profileData?.value) {
  form.value = {
    ...profileData.value,
    province: profileData.value.province || '',
    district: profileData.value.district || '',
    ward: profileData.value.ward || '',
    gender: profileData.value.gender || 1,
    birthday: profileData.value.birthday || '2000-01-01',
    address: profileData.value.address || ''
  }

  watchEffect(() => {
    if(divAvatar.value){
      divAvatar.value.style.backgroundImage = `url(${MEDIA_ENDPOINT + profileData?.value?.avatar})`
      divAvatar.value.style.backgroundSize = 'cover'
      divAvatar.value.style.backgroundPosition = 'center'
    }
  })
}

const districts = computed(() => {
  if(form.value.province){
    return provincesData?.value?.find((item: any) => item.code == form.value.province)?.districts
  }
  return []
})

const wards = computed(() => {
  if(form.value.district){
    return districts.value.find((item: any) => item.code == form.value.district)?.wards
  }
  return []
})

watch(() => form.value.province, () => {
  if(!districts.value.find((item: any) => item.code == form.value.district)){
    form.value.district = ''
    form.value.ward = ''
  }
})

watch(() => form.value.district, () => {
  if(!wards.value.find((item: any) => item.code == form.value.ward)){
    form.value.ward = ''
  }
})


const onFileChange = (e: any) => {
  const file = e.target.files[0]
  form.value.avatar = file
  const reader = new FileReader()
  reader.onload = (e: any) => {
    if(divAvatar.value){
      divAvatar.value.style.backgroundImage = `url(${e.target.result})`
      divAvatar.value.style.backgroundSize = 'cover'
      divAvatar.value.style.backgroundPosition = 'center'
    }
  }
  reader.readAsDataURL(file)
}

const onSubmit = async () => {
    clearError()

    let formData = new FormData()
    for (const key in form.value) {
       //@ts-ignore
       formData.append(key, form.value[key])
    }

    submitting.value = true
    const { error } : any = await useClientFetch('profile/update', {method: 'POST', body: formData})
    submitting.value = false

    if(error.value) {
      if(error.value.status === 422){
        errorList.value = error.value.data.errors
      }else {
        showToastFunc('Đã có lỗi xảy ra, vui lòng thử lại sau', 'error')
      }
    }else{
      showToastFunc('Cập nhật thông tin thành công', 'success')
      await useAuth().fetchUser()
    }
}

const submitting = ref(false)
const showToast = ref(false)
const message = ref('')
const type = ref('')

const showToastFunc = (msg: string, toastType: string) => {
  showToast.value = true
  message.value = msg
  type.value = toastType
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

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
    email: []
  }
}

</script>
<template>
    <div class="bg-white rounded-md p-4">
      <div class="grid grid-cols-10">
        <div class="col-span-6  border-r-[1px] pr-10">
          <form @submit.prevent="onSubmit">
            <div class="grid grid-cols-5 gap-5">
              <div class="col-span-5">
                <h3 class="text-xl font-semibold">Thông tin cá nhân</h3>
              </div>
              <div class="flex space-x-4">
                <div ref="divAvatar" class="w-28 h-28 bg-blue-100 rounded-full flex items-center justify-center relative">
                  <div class="absolute bottom-0 right-0 bg-gray-200 rounded-full p-1 border border-white">
                    <label for="avatar" class="cursor-pointer">
                      <CameraIcon class="h-6 w-6 text-gray-600" />
                    </label>
                    <input @change="onFileChange" type="file" id="avatar" class="hidden" />
                  </div>
                </div>
              </div>
              <div class="col-span-4">
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

                <button type="submit"
                        class="bg-blue-500 text-white rounded py-2 px-4 hover:bg-blue-600 ms-[20%]">
                  <Loading v-if="submitting" />
                  <span v-else>Cập nhật</span>
                </button>
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
                  <p class="text-gray-500">{{ form.phone }}</p>
                </div>
              </div>
              <button class="text-blue-500 border border-blue-500 px-3 py-1 rounded">Cập nhật</button>
            </div>
            <div class="flex items-center justify-between py-2">
              <div class="flex items-center space-x-2">
                <EnvelopeIcon class="h-6 w-6 text-gray-600" />
                <div class="space-y-1">
                  <p class="text-gray-700">Địa chỉ email</p>
                  <p class="text-gray-500">{{ form.email }}</p>
                </div>
              </div>
              <button class="text-blue-500 border border-blue-500 px-3 py-1 rounded">Cập nhật</button>
            </div>
          </div>

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
    <Toast :show="showToast"
           :message="message"
           :type="type"/>
</template>


