<script setup lang="ts">
import AdminLayout from "~/layouts/AdminLayout.vue"

const { data } = await useFetchData({
  url: `categories`,
})

const id = useRoute().params.id

const form = ref({
  id: '',
  name: '',
  description: '',
  unit: '',
  price: 0,
  sale_price: 0,
  quantity: 0,
  thumbnail: '',
  category_id: '',
  seo_description: '',
  seo_title: '',
  seo_url: '',
  attributes: '',
})

const error_list = ref({
  name: [],
  description: [],
  unit: [],
  price: [],
  sale_price: [],
  quantity: [],
  thumbnail: [],
  category_id: [],
  seo_description: [],
  seo_title: [],
  seo_url: [],
  attributes: [],
})

const submitting = ref(false)
const showToast = ref(false)
const message = ref('')
const type = ref('')

if(id.toString() !== 'null') {
  const { data: productsData } = await useFetchData({
    url: `shop/products`,
    requiresToken: true,
    server: false,
  })

  watchEffect(() => {
    const product = productsData?.value?.data?.find((p: any) => p.id == id)

    if(product) {
      form.value = {
        ...product
      }

      if(product.attributes){
        let result = '';
        for (const [key, value] of Object.entries(JSON.parse(product?.attributes))) {
          result += `${key}: ${value}\n`;
        }
        form.value.attributes = result
      }
    }
  })
}


const onSubmit = async () => {
  try{
    clearError()
    submitting.value = true
    let formData = new FormData()
    for (let key in form.value) {
      // @ts-ignore
      formData.append(key, form.value[key])
    }

    await usePostData({
      url: 'shop/products/store',
      method: 'POST',
      body: formData,
      requiresToken: true
    })

    showToastFunc('Lưu sản phẩm thành công', 'success')
    if(!form.value.id){
      clearForm()
    }
    await useFetchData({
      url: `shop/products`,
      requiresToken: true,
      server: false,
      cache: false,
    })
  }catch (e: any){
    if(e.status === 422){
      error_list.value = e.data.errors
    }else{
      showToastFunc('Có lỗi xảy ra', 'error')
      console.log(e.data.message)
    }
  }finally {
    submitting.value = false
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

const clearError = () => {
  error_list.value = {
    name: [],
    description: [],
    unit: [],
    price: [],
    sale_price: [],
    quantity: [],
    thumbnail: [],
    category_id: [],
    seo_description: [],
    seo_title: [],
    seo_url: [],
    attributes: [],
  }
}

const clearForm = () => {
  form.value = {
    id: '',
    name: '',
    description: '',
    unit: '',
    price: 0,
    sale_price: 0,
    quantity: 0,
    thumbnail: '',
    category_id: '',
    seo_description: '',
    seo_title: '',
    seo_url: '',
    attributes: '',
  }
}

const onFileChange = (e: any) => {
  const file = e.target.files[0]
  form.value.thumbnail = file
  const reader = new FileReader()
  reader.onload = (e: any) => {
    document.getElementById('img_thumbnail')?.setAttribute('src', e.target.result)
  }
  reader.readAsDataURL(file)
}

</script>

<template>
  <AdminLayout>
    <h1 class="text-2xl">Quản lý sản phẩm</h1>
    <div class="rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)]  bg-white">
      <form enctype="multipart/form-data" id="form" @submit.prevent="onSubmit" class="max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Column -->
          <div>
            <div class="">
              <label for="name" class="block text-gray-600">Tên sản phẩm</label>
              <Input v-model="form.name" :errors="error_list?.name?.[0]" />
            </div>

            <div class="">
              <label for="description" class="block text-gray-600">Mô tả</label>
              <TextArea v-model="form.description" :errors="error_list?.description?.[0]" />
            </div>

            <div class="">
              <label for="unit" class="block text-gray-600">Đơn vị</label>
              <Input v-model="form.unit" :errors="error_list?.unit?.[0]" />
            </div>

            <div class="">
              <label for="price" class="block text-gray-600">Giá gốc</label>
              <Input type="number" v-model="form.price" :errors="error_list?.price?.[0]" />
            </div>

            <div class="">
              <label for="sale_price" class="block text-gray-600">Giá sale</label>
              <Input type="number" v-model="form.sale_price" :errors="error_list?.sale_price?.[0]" />
            </div>

            <div class="">
              <label for="quantity" class="block text-gray-600">Số lượng</label>
              <Input type="number" v-model="form.quantity" :errors="error_list?.quantity?.[0]" />
            </div>

            <div class="">
              <label for="thumbnail" class="block text-gray-600">Thumbnail</label>
              <input @change="onFileChange" type="file" id="thumbnail"
                     :class="[error_list?.thumbnail?.[0] ? 'border border-red-500' : 'border border-gray-300']"
                     class="rounded-lg border border-gray-300 w-full">
              <InputError :message="error_list?.thumbnail?.[0]" />
<!--              <img id="img_thumbnail" alt="thumbnail" v-if="form.thumbnail"-->
<!--                   :src="MEDIA_ENDPOINT + form.thumbnail" class="w-28 h-28 object-cover rounded-lg" />-->
            </div>
          </div>

          <!-- Right Column -->
          <div class="flex flex-col">
            <div class="">
              <label for="category_id" class="block text-gray-600">Danh mục</label>
              <Select optionDefault="Chọn danh mục"
                      v-model="form.category_id" :options="data"
                      :errors="error_list?.category_id?.[0]"/>
            </div>

            <div class="">
              <label for="seo_description" class="block text-gray-600">SEO Mô tả</label>
              <TextArea v-model="form.seo_description" :errors="error_list?.seo_description?.[0]" />
            </div>

            <div class="">
              <label for="seo_title" class="block text-gray-600">SEO Tiêu đề</label>
              <Input v-model="form.seo_title" :errors="error_list?.seo_title?.[0]" />
            </div>

            <div class="">
              <label for="seo_url" class="block text-gray-600">SEO URL</label>
              <Input v-model="form.seo_url" :errors="error_list?.seo_url?.[0]" />
            </div>

            <div class="">
              <label for="attributes" class="block text-gray-600">Thuộc tính</label>
              <TextArea v-model="form.attributes" :errors="error_list?.attributes?.[0]" />
            </div>

            <div class="mt-[14px] flex gap-2">
              <button type="submit" class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                <Loading v-if="submitting" />
                <span v-else>Lưu sản phẩm</span>
              </button>
              <button @click.prevent="useRouter().back()" class="px-6 py-[10px] bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                <span>Thoát</span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <Toast :message="message" :type="type" :show="showToast"/>
  </AdminLayout>
</template>

<style scoped>

</style>