<script setup lang="ts">
import type {Category} from "~/lib/schema";
import {MEDIA_ENDPOINT} from "~/lib/constants";

definePageMeta({
  layout: 'admin',
  middleware: 'auth'
})

const id = useRoute().params.id

let { data: category } = await useClientFetch<Category>(`shop/categories/${id}`)

const form = ref({
  id: id == 'null' ? '' : id,
  name: category?.value?.name || '',
  image: category?.value?.image || '',
})

const errorList = ref({
  name: [],
  image: []
})

const submitting = ref(false)
const showToast = ref(false)
const message = ref('')
const type = ref('')
const onSubmit = async () => {
  console.log(form.value)
  clearError()
  submitting.value = true
  let formData = new FormData()
  for (let key in form.value) {
    // @ts-ignore
    formData.append(key, form.value[key])
  }

  const {error} : any = await useClientFetch('shop/categories/store',{
    method: 'POST',
    body: formData,
  })
  submitting.value = false

  if (error.value) {
    if(error.value.status === 422){
      errorList.value = error.value.data.errors
    }else{
      showToastFunc('Lưu danh mục thất bại', 'error')
    }
    return
  }
  showToastFunc('Lưu danh mục thành công', 'success')
  if(id.toString() === 'null'){
    clearForm()
  }
}

const clearError = () => {
  errorList.value = {
    name: [],
    image: []
  }
}
const clearForm = () => {
  form.value = {
    ...form.value,
    name: '',
    image: ''
  }
}
const showToastFunc = (msg: string, toastType: string) => {
  showToast.value = true
  message.value = msg
  type.value = toastType
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const onFileChange = (e: any) => {
  const file = e.target.files[0]
  form.value.image = file
  const reader = new FileReader()
  reader.onload = (e: any) => {
    document.getElementById('img_thumbnail')?.setAttribute('src', e.target.result)
  }
  reader.readAsDataURL(file)
}

</script>

<template>
  <h1 class="text-2xl">Quản lý sản phẩm</h1>
  <div class="rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)]  bg-white">
    <form enctype="multipart/form-data" id="form" @submit.prevent="onSubmit" class="max-w-2xl">
          <div class="">
            <label for="name" class="block text-gray-600">Tên danh mục</label>
            <Input v-model="form.name" :errors="errorList?.name?.[0]" />
          </div>
          <div class="">
            <label for="thumbnail" class="block text-gray-600">Thumbnail</label>
            <input @change="onFileChange" type="file" id="thumbnail"
                   :class="[errorList?.image?.[0] ? 'border border-red-500' : 'border border-gray-300']"
                   class="rounded-lg border border-gray-300 w-full">
            <InputError :message="errorList?.image?.[0]" />
            <img id="img_thumbnail" alt="thumbnail" v-if="form.image"
                 :src="MEDIA_ENDPOINT + form.image" class="w-28 h-28 object-cover rounded-lg" />
          </div>

          <div class="mt-[10px] flex gap-2">
            <button type="submit" class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
              <Loading v-if="submitting" />
              <span v-else>Lưu danh mục</span>
            </button>
            <button @click.prevent="useRouter().back()" class="px-6 py-[10px] bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
              <span>Thoát</span>
            </button>
          </div>
    </form>
  </div>
  <Toast :message="message" :type="type" :show="showToast"/>
</template>

<style scoped>

</style>