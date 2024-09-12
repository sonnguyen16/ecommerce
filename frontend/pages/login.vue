<script setup lang="ts">
import MainLayout from "~/layouts/MainLayout.vue"
import usePostData from "~/composables/usePostData";

const form = ref({
  phone: '',
  password: '',
})

const submitting = ref(false)
const errorList = ref({
  phone: [],
  password: [],
})
const onSubmit = async () => {
   try{
     clearError()
     submitting.value = true
     await usePostData({url: 'auth/login', method: 'POST', body: form.value})
     navigateTo('/')
   }catch (e: any){
     if(e.status === 422){
       errorList.value = e.data.errors
     }else{
       showToastFunc()
       console.log(e)
     }
   }finally {
     submitting.value = false
   }
}

const showToast = ref(false)

const showToastFunc = () => {
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const clearError = () => {
  errorList.value = {
    phone: [],
    password: [],
  }
}

</script>

<template>
  <MainLayout>
    <div class="flex justify-center py-5">
      <div class="bg-white rounded-lg p-5" style="width: 500px !important;">
        <h2 class="text-2xl font-bold text-center text-blue-500 mb-4">Đăng nhập</h2>

        <form @submit.prevent="onSubmit">
          <div class="mb-3">
            <Input v-model="form.phone" type="text" placeholder="Nhập số điện thoại" :errors="errorList.phone?.[0]" />
          </div>

          <div class="mb-3">
            <Input v-model="form.password" type="password" placeholder="Nhập mật khẩu" :errors="errorList.password?.[0]" />
          </div>

          <button :disabled="submitting" type="submit" class="w-full bg-red-500 text-white py-2 mb-3 rounded-lg">
            <Loading v-if="submitting" />
            <span v-else>Đăng nhập</span>
          </button>
        </form>
        <span class="text-gray-500 block text-center">Chưa có tài khoản? <nuxt-link to="/register" class="text-blue-500">Đăng ký</nuxt-link></span>
      </div>
    </div>
  </MainLayout>
  <div class="bg-white container my-8 lg:block hidden">
    <Footer/>
  </div>
  <Toast :show="showToast"
         message="Đăng nhập thất bại"
         type="error"/>
</template>

<style scoped>

</style>