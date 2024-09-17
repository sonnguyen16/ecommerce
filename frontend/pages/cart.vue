<script setup lang="ts">
import { TrashIcon, TicketIcon } from "@heroicons/vue/24/outline";
import MainLayout from "~/layouts/MainLayout.vue";


definePageMeta({
  middleware: 'auth'
});

let cart: Ref<any[]>

const isAuthenticated = Boolean(useCookie('access_token').value)

const message = ref('Thêm vào giỏ hàng thành công')

const status = ref('success')

const showToast = ref(false)

if(!isAuthenticated) {
  if(process.client){
    cart = ref(JSON.parse(localStorage.getItem('cart') || '[]'));
  }
}else{
  const { data } : { data: any } = await useFetchData({
    url: 'cart',
    requiresToken: true,
    server: false,
    cache: false,
  });

  cart = data
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
};

const updateCart = async () => {
  if(!isAuthenticated){
    localStorage.setItem('cart', JSON.stringify(cart.value));
  }else{
    try{
      await usePostData({
        url: 'add-many-to-cart',
        body: { carts: cart.value },
        requiresToken: true,
      })
    }catch (e){
      console.log(e)
    }
  }
}

onBeforeRouteLeave(() => {
  updateCart()
})

const order = async () => {
  const total = cart.value.reduce((acc, c) => acc + c.product.sale_price * c.quantity, 0)
  const products = cart.value.map((c) => (
      { product_id: c.product.id,
        quantity: c.quantity,
        total: c.product.sale_price * c.quantity,
        price: c.product.sale_price
      }
      ))

  try{
    await usePostData({
      url: 'order',
      body: {
        total,
        products
      },
      requiresToken: true,
    })
    cart.value = []
    showToastFunction('Đặt hàng thành công', 'success')
  }catch (e){
    showToastFunction('Đặt hàng thất bại', 'error')
    console.log(e)
  }
}

const showToastFunction = (msg: string, s: string) => {
  message.value = msg
  status.value = s
  showToast.value = true
  setTimeout(() => {
    showToast.value = false
  }, 3000)
}

</script>

<template>
  <div>
    <MainLayout>
      <div class="">
        <h1 class="text-2xl mb-3 font-semibold">Giỏ hàng</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Left Column -->
          <div class="col-span-3 space-y-4">
            <!-- Cart Header -->

            <!-- Cart Header with 7 Columns -->
            <div class="bg-white p-4 rounded-lg">
              <div class="grid grid-cols-8 items-center">
                <!-- First Column: Checkbox and Text -->
                <div class="flex items-center col-span-3">
                  <input type="checkbox" class="h-5 w-5 border border-gray-300 rounded-md"/>
                  <span class="ml-2 text-gray-700 font-semibold">Tất cả (2 sản phẩm)</span>
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
              <!-- Header -->
              <div class="flex justify-between items-center border-b pb-3">
                <div class="flex items-center">
                  <input type="checkbox" class="h-5 w-5 border border-gray-300 rounded-md"/>
                  <span class="ml-2 text-gray-700 font-semibold">Tiki Trading</span>
                </div>
                <div class="text-blue-500 font-semibold">Chọn sản phẩm</div>
              </div>

              <!-- Promotion -->
              <div class="bg-orange-100 p-2 mt-3 rounded-lg text-orange-600 text-sm">
                Mua 3, giảm 5%
              </div>


              <template v-if="cart">
                <ProductCart
                    v-for="c in cart"
                    :key="c.id"
                    :product="c.product"
                    :quantity="c.quantity"
                    @increment="increment"
                    @decrement="decrement"
                    @delete="deleteProduct"
                />
              </template>

              <!-- Shop Promotion -->
              <div class="pt-3 text-gray-500 text-sm border-t">
                Shop Khuyến Mãi:
                <span class="text-blue-600"> Mua 3, giảm 5%</span>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-span-1 space-y-4">
            <div class="bg-white p-4 rounded-lg">
              <!-- Coupon Section -->
              <div class="bg-gray-100 p-4 rounded-lg mb-4">
                <div class="flex justify-between items-center">
                  <span class="text-gray-700 font-semibold">Tiki Khuyến Mãi</span>
                  <span class="text-gray-400 text-sm">Có thể chọn 2</span>
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
                  <span class="text-gray-500">0₫</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-500">Giảm giá</span>
                  <span class="text-gray-500">0₫</span>
                </div>
                <div
                  class="flex justify-between items-center text-red-500 font-semibold mb-4"
                >
                  <span>Tổng tiền</span>
                  <span>Vui lòng chọn sản phẩm</span>
                </div>
                <div class="text-gray-400 text-sm text-right">
                  <span>(Đã bao gồm VAT nếu có)</span>
                </div>
              </div>

              <!-- Checkout Button -->
              <button @click.prevent="order" class="w-full bg-red-500 text-white font-semibold py-3 rounded-lg hover:bg-red-600 transition">
                Mua Hàng (0)
              </button>
            </div>

            <div class="bg-white p-4 rounded-lg">
              <!-- Promotional Banner -->
              <div class="">
                <img src="/slide.jpg" alt="Promotion" class="rounded-lg" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </MainLayout>
    <div class="bg-white container my-8">
      <Footer />
    </div>
  </div>
  <Toast :message="message" :type="status" :show="showToast"/>
</template>

<style scoped>
</style>
