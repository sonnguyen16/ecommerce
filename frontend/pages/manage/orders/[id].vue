<script setup lang="ts">
import type {OrderDetail} from "~/lib/schema";

definePageMeta({
  layout: 'admin',
  middleware: 'auth'
})

const id = useRoute().params.id

let { data: order_detail } = await useClientFetch<OrderDetail>(`shop/orders/${id}`)
let { data: provincesData } = await useClientFetch<any>('provinces')

const statuses = [
  { id: 1, name: 'Chờ xác nhận', color: 'bg-yellow-100 text-yellow-600' },
  { id: 2, name: 'Đang giao hàng', color: 'bg-blue-100 text-blue-600' },
  { id: 3, name: 'Đã giao hàng', color: 'bg-green-100 text-green-600' },
  { id: 4, name: 'Đã hủy', color: 'bg-red-100 text-red-600' },
]

const order = order_detail?.value?.order

const province = provincesData?.value?.find((p: any) => p?.code == order?.province)

const district = province?.districts?.find((d: any) => d?.code === order?.district)

const wards = district?.wards?.find((w: any) => w?.code === order?.ward)

const form = ref({
  order_detail_id: id,
  status: order_detail?.value?.status,
  address: '',
  note: ''
})

const errorList = ref({
  status: [],
  address: [],
  note: []
})

const submitting = ref(false)
const showToast = ref(false)
const message = ref('')
const type = ref('')
const onSubmit = async () => {
    clearError()
    submitting.value = true
    const { data, error } : any = await useClientFetch(
    'shop/orders/update', {
      method: 'POST',
      body: form.value,
    })
    submitting.value = false

    if (error.value) {
      if(error.value.status === 422){
        errorList.value = error.value.data.errors
      }else{
        showToastFunc('Cập nhật trạng thái thất bại', 'error')
      }
    }else{
      clearForm()
      order_detail.value = data.value
      showToastFunc('Cập nhật trạng thái thành công', 'success')
    }
}

const clearError = () => {
  errorList.value = {
    status: [],
    address: [],
    note: []
  }
}
const clearForm = () => {
  form.value = {
    ...form.value,
    address: '',
    note: ''
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

</script>

<template>
    <h1 class="text-2xl">Quản lý đơn hàng</h1>
    <div class="bg-white rounded-xl p-4 mt-5 min-h-[calc(100vh-9.5rem)] space-y-5 grid md:grid-cols-3 gap-10">
      <div class="col-span-2">
        <div class="ps-3 space-y-3">
          <h2 class="text-xl font-semibold mb-3 text-indigo-700">Thông tin khách hàng</h2>
         <div class="flex gap-6">
           <div class="space-y-3">
             <p><strong>Tên: </strong>{{ order?.name }}</p>
             <p><strong>Số điện thoại: </strong>{{ order?.phone }}</p>
             <p><strong>Ngày tạo: </strong>{{ new Date(order?.created_at).toLocaleString() }}</p>
             <p><strong>Địa chỉ: </strong>{{ order?.address }}</p>
           </div>
           <div class="space-y-3">
             <p><strong>Phường/Xã: </strong>{{ wards?.name }}</p>
             <p><strong>Quận/Huyện: </strong>{{ district?.name }}</p>
             <p><strong>Tỉnh/Thành phố: </strong>{{ province?.name }}</p>
           </div>
         </div>
        </div>
        <div class="ps-3 mt-5">
          <h2 class="text-xl font-semibold mb-3 text-indigo-700">Chi tiết đơn hàng</h2>
          <div class="grid-cols-7 grid items-center bg-gray-200 p-3 rounded-xl mb-3">
            <div class="col-span-2 text-gray-500">
              <p class="text-gray-700 ps-3">
                Mã đơn hàng: <span class="text-blue-700">{{ order_detail?.id }}</span>
              </p>
            </div>
            <div class="col-span-2 text-gray-500 text-center">Trạng thái</div>
            <div class="col-span-1 text-gray-500 text-center">Đơn giá</div>
            <div class="col-span-1 text-gray-500 text-center">Số lượng</div>
            <div class="col-span-1 text-gray-500 text-end">Tổng tiền</div>
          </div>
          <OrderDetail v-if="order_detail" :orderdetail="order_detail" />
        </div>
        <div class="ps-3 mt-5">
          <h2 class="text-xl font-semibold mb-3 text-indigo-700">Cập nhật trạng thái</h2>
          <form @submit.prevent="onSubmit">
            <Select :errors="errorList?.status?.[0]" v-model="form.status" :options="statuses" option-default="Chọn trạng thái" class="max-w-[500px]" />
            <Input :errors="errorList?.address?.[0]" v-model="form.address" placeholder="Địa chỉ hiện tại" class="max-w-[500px]"/>
            <Input :errors="errorList?.note?.[0]" v-model="form.note" placeholder="Ghi chú" class="max-w-[500px]"/>
            <button type="submit" class="px-6 py-[10px] bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
              <Loading v-if="submitting" />
              <span v-else>Lưu trạng thái</span>
            </button>
          </form>
        </div>
      </div>
      <div class="col-span-1 pl-5">
          <h2 class="text-xl font-semibold mb-5 text-indigo-700">Thông tin vận chuyển</h2>
          <div class="relative border-l border-gray-300 pl-5">
            <template v-if="order_detail?.locations?.length > 0" v-for="(location, index) in order_detail?.locations">
              <div class="mb-10 ml-6">
                <span :class="[index === 0 ? 'bg-yellow-500' : 'bg-gray-200']" class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full"></span>
                <time class="mb-1 text-sm font-normal text-gray-400">
                  {{ new Date(location?.created_at).toLocaleDateString() }}
                  <br>{{ new Date(location?.created_at).toLocaleTimeString() }}
                </time>
                <p class="text-lg font-semibold text-gray-900">{{ location?.note }}</p>
                <p class="text-sm text-gray-500">{{ location?.address }}</p>
              </div>
            </template>
            <template v-else>
              <p class="text-gray-500">Chưa có thông tin vận chuyển</p>
            </template>
        </div>
      </div>
    </div>
    <Toast :show="showToast"
           :message="message"
           :type="type"/>
</template>

<style scoped>

</style>