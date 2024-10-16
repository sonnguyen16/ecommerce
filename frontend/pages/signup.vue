<script setup lang="ts">

definePageMeta({
  layout: 'main'
})

const form = ref({
  name: '',
  phone: '',
  password: '',
  address: '',
  province: '',
  district: '',
  ward: ''
})

let provinces  = ref<any | null>([])
let districts = ref<any | null>([])
let wards = ref<any | null>([])

onBeforeMount(async () => {
  const provinceData = await useClientFetch('provinces')
  provinces.value = provinceData.data.value
})

watch(() => form.value.province, async (newVal) => {
  if(newVal){
    const { data } = await useClientFetch(`districts/${newVal}`)
    districts.value = data.value
    form.value.district = ''
    form.value.ward = ''
  }
})

watch(() => form.value.district, async (newVal) => {
  if(newVal){
    const { data } = await useClientFetch(`wards/${newVal}`)
    wards.value = data.value
    form.value.ward = ''
  }
})


const submitting = ref(false)
const showToast = ref(false)
const message = ref('')
const type = ref('')

const errorList = ref({
  name: [],
  phone: [],
  password: [],
  address: [],
  province: [],
  district: [],
  ward: []
})

const onSubmit = async () => {
  clearError()
  submitting.value = true
  const { error } : any = await useClientFetch('register', {
    method: 'POST',
    body: form.value
  })
  submitting.value = false

  if(error.value){
    if(error.value.status === 422){
      errorList.value = error.value.data.data.errors
    }else{
      showToastFunc('Đã có lỗi xảy ra, vui lòng thử lại sau', 'error')
    }
  }else{
    navigateTo('/')
  }
}

const showToastFunc = (msg: string, toastType: string) => {
  showToast.value = true
  message.value = msg
  type.value = toastType
}

const clearError = () => {
  errorList.value = {
    name: [],
    phone: [],
    password: [],
    address: [],
    province: [],
    district: [],
    ward: []
  }
}

</script>

<template>
      <div class="flex justify-center py-5">
        <div class="bg-white rounded-lg p-5" style="width: 500px !important;">
          <h2 class="text-2xl font-bold text-center text-blue-500 mb-4">Đăng ký</h2>

          <form @submit.prevent="onSubmit">
            <div class="">
              <Input v-model="form.name"
                     type="text" placeholder="Nhập họ tên"
                     :errors="errorList.name?.[0]"/>
            </div>

            <div class="">
              <Input v-model="form.phone" type="text"
                     placeholder="Nhập số điện thoại"
                     :errors="errorList.phone?.[0]"/>
            </div>

            <div class="">
              <Input v-model="form.password" type="password"
                     placeholder="Nhập mật khẩu"
                     :errors="errorList.password?.[0]"/>
            </div>

            <div class="">
              <Select optionDefault="Chọn tỉnh/thành phố"
                      v-model="form.province" :options="provinces"
                      :errors="errorList.province?.[0]"/>
            </div>

            <div class="">
              <Select optionDefault="Chọn quận/huyện"
                      v-model="form.district" :options="districts"
                      :errors="errorList.district?.[0]"/>
            </div>

            <div class="">
              <Select optionDefault="Chọn phường/xã"
                      v-model="form.ward" :options="wards"
                      :errors="errorList.ward?.[0]"/>
            </div>

            <div class="">
              <Input v-model="form.address" type="text"
                     placeholder="Nhập địa chỉ nhận hàng"
                     :errors="errorList.address?.[0]"/>
            </div>

            <button :disabled="submitting" type="submit"
                    class="w-full bg-red-700 text-white py-2 mb-3 rounded-lg">
              <Loading v-if="submitting" />
              <span v-else>Đăng ký</span>
            </button>
          </form>
          <span class="text-gray-500 block text-center">
            Đã có tài khoản? <nuxt-link to="/signin" class="text-blue-500">Đăng nhập</nuxt-link>
          </span>
        </div>
      </div>
      <Toast :show="showToast"
             :message="message"
             :type="type"/>
</template>
