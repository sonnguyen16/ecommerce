<script setup lang="ts">
import { TrashIcon, TicketIcon } from "@heroicons/vue/24/outline";
import type {Cart} from "~/lib/schema";
import {formatCash} from "~/lib/utils";

definePageMeta({
  layout: "main"
})

let cart: Ref<Cart[]> = ref([]);

const { data: profileData } = await useServerFetch('profile')

const ticked: Ref<number[]> = ref([])

const message = ref('Thêm vào giỏ hàng thành công')

const status = ref('success')

const showToast = ref(false)

const total = computed(() => {
  return cart?.value?.filter((c) => ticked.value.includes(c.id))
      .reduce((acc, c) => acc + c.product.sale_price * c.quantity, 0)
})

const user = await useAuth().getUser()

if(user) {
  const { data } : any = await useClientFetch('cart');
  cart = data || []
}

if(process.client){
  const cartLocal = JSON.parse(localStorage.getItem('cart') || '[]');

  if(cartLocal.length > 0){
    cartLocal.forEach((c: Cart) => {
      const index = cart.value.findIndex((cc: Cart) => cc.product.id === c.product.id);
      if(index === -1){
        cart.value.push(c)
      }else{
        cart.value[index].quantity += c.quantity
      }
    })

    localStorage.removeItem('cart')
  }
}


const increment = (id: number) => {
  const index = cart.value.findIndex((c) => c.product.id === id);
  if (index !== -1) {
    cart.value[index].quantity++;
  }
};

const decrement = (id: number) => {
  const index = cart.value.findIndex((c) => c.product.id === id);
  if (index !== -1) {
    cart.value[index].quantity--;
  }
};

const deleteProduct = (id: number) => {
  const index = cart.value.findIndex((c) => c.product.id === id);
  if (index !== -1) {
    cart.value.splice(index, 1);
  }
  ticked.value = ticked.value.filter((t) => t !== id)
};

const updateCart = async () => {
  if(user){
    await useClientFetch(
      'add-many-to-cart', {
        body: { carts: cart.value || [] },
        method: 'POST',
    })
  }else{
    localStorage.setItem('cart', JSON.stringify(cart.value))
  }
}

onBeforeUnmount(async () => {
  await updateCart()
})

const submitting = ref(false)

const order = async () => {
  if (ticked.value.length === 0) {
    showToastFunction('Vui lòng chọn sản phẩm', 'error')
    return
  }

  if(!user){
    showToastFunction('Vui lòng đăng nhập', 'error')
    return
  }

  if(!profileData?.value?.phone || !profileData?.value?.province
      || !profileData?.value?.district || !profileData?.value?.ward
      || !profileData?.value?.address){
    showToastFunction('Vui lòng cập nhật thông tin cá nhân', 'error')
    return
  }

  const total = cart.value.reduce((acc, c) => acc + c.product.sale_price * c.quantity, 0)
  const products = cart.value.filter((c) => ticked.value.includes(c.id)).map((c) => (
      {
        product_id: c.product.id,
        quantity: c.quantity,
        total: c.product.sale_price * c.quantity,
        price: c.product.sale_price
      }
  ))

  submitting.value = true
  const { error } = await useClientFetch('order', {
    body: {
      name: profileData?.value?.name,
      phone: profileData?.value?.phone,
      province: profileData?.value?.province,
      district: profileData?.value?.district,
      ward: profileData?.value?.ward,
      address: profileData?.value?.address,
      total,
      products
    },
    method: 'POST'
  })
  submitting.value = false
  if(error.value){
    showToastFunction('Đã có lỗi xảy ra, vui lòng thử lại sau', 'error')
    console.log(error.value)
  }else{
    showToastFunction('Đặt hàng thành công', 'success')
  }
  cart.value = cart.value.filter((c) => !ticked.value.includes(c.id))
  ticked.value = []
}

const showToastFunction = (msg: string, s: string) => {
  message.value = msg
  status.value = s
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

const addAllProduct = (e: any) => {
  if(e.target.checked){
    ticked.value = cart.value.map((c) => c.id)
  }else{
    ticked.value = []
  }
}

const addProduct = (id: number) => {
  const cartFound = cart.value.find((c) => c.product.id === id) as Cart

  if(ticked.value.includes(cartFound.id)){
    ticked.value = ticked.value.filter((t) => t !== cartFound.id)
  }else{
    ticked.value.push(cartFound.id)
  }
}

</script>

<template>
    <div class="">
        <h1 class="text-2xl mb-3 font-semibold">Giỏ hàng</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 md:gap-4 md:space-y-0 space-y-4">
          <!-- Left Column -->
          <div class="col-span-3 space-y-4">
            <!-- Cart Header -->

            <!-- Cart Header with 7 Columns -->
            <div class="w-full overflow-x-auto">
              <div class="min-w-[600px] space-y-4">
                <div class="bg-white p-4 rounded-lg">
                  <div class="grid grid-cols-8 items-center">
                    <!-- First Column: Checkbox and Text -->
                    <div class="flex items-center col-span-3">
                      <input @change="addAllProduct" type="checkbox" class="h-5 w-5 border border-gray-300 rounded-md"/>
                      <span class="ml-2 text-gray-700 font-semibold">Tất cả ({{ cart?.length }} sản phẩm)</span>
                    </div>
                    <!-- Second to Seventh Column: Labels -->
                    <div class="text-gray-500 col-span-2 text-center">Đơn giá</div>
                    <div class="text-gray-500 text-center">Số lượng</div>
                    <div class="text-gray-500 text-end">Thành tiền</div>
                    <div class="text-end">
                      <TrashIcon class="h-5 w-5 ms-auto" />
                    </div>
                  </div>
                </div>

                <div class="bg-white p-4 rounded-lg">
                  <!-- Promotion -->
                  <div class="bg-orange-100 p-2 rounded-lg text-orange-600 text-sm">
                    Mua 3, giảm 5%
                  </div>


                  <div v-if="cart">
                    <ProductCart
                        v-for="c in cart"
                        :key="c.id"
                        :product="c.product"
                        :quantity="c.quantity"
                        :checked="ticked.includes(c.id)"
                        @ticked="addProduct"
                        @increment="increment"
                        @decrement="decrement"
                        @delete="deleteProduct"
                    />
                  </div>

                </div>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-span-1 space-y-4">
            <div class="bg-white p-4 rounded-lg">
              <!-- Coupon Section -->
              <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <div class="flex justify-between items-center">
                  <span class="text-gray-700 font-semibold">Voucher Khuyến Mãi</span>
                  <span class="text-gray-400 text-sm">Có thể chọn 0</span>
                </div>
                <div class="mt-2">
                  <button class="text-blue-500 font-semibold flex items-center">
                    <TicketIcon class="h-5 w-5 me-2" />
                    Chọn hoặc nhập Khuyến mãi khác
                  </button>
                </div>
              </div>

              <!-- Price Summary -->
              <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-500">Tạm tính</span>
                  <span class="text-gray-500">{{ formatCash(total?.toString()) }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-500">Giảm giá</span>
                  <span class="text-gray-500">0₫</span>
                </div>
                <div
                  class="flex justify-between items-center text-red-500 font-semibold mb-4"
                >
                  <span>Tổng tiền</span>
                  <span>{{ formatCash(total?.toString()) }}đ</span>
                </div>
                <div class="text-gray-400 text-sm text-right">
                  <span>(Đã bao gồm VAT nếu có)</span>
                </div>
              </div>

              <!-- Checkout Button -->
              <button @click.prevent="order" class="w-full bg-red-700 text-white font-semibold py-3 rounded-lg transition">
                <Loading v-if="submitting" />
                <span v-else>Mua Hàng ({{ ticked?.length }})</span>
              </button>
            </div>
          </div>
        </div>
      </div>
  <Toast :message="message" :type="status" :show="showToast"/>
</template>

