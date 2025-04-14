<script setup lang="ts">
import { CheckBadgeIcon, PlusIcon, StarIcon, MinusIcon } from '@heroicons/vue/24/solid'
import { ref, computed, onMounted } from 'vue'
import { formatCash } from '~/lib/utils'
import type { Product } from '~/lib/schema'
import { useCartEvents } from '~/composables/useCartEvents'
import { useAuth } from '~/composables/useAuth'

definePageMeta({
  layout: 'main'
})

const { mediaUrl, appUrl } = useRuntimeConfig().public

const route = useRoute()
const { slug } = route.params

// Lấy thông tin sản phẩm hiện tại
const { data: productData, error: productError } = await useServerFetch<Product>(`products/${slug}`)
const product = ref<Product | null>(productData.value)
const isLoading = ref(true)

// Kiểm tra xem sản phẩm có tồn tại không
if (!product.value || productError.value) {
  isLoading.value = false
}

// Lấy 12 sản phẩm tương tự thay vì sử dụng phân trang
const similarProducts = ref<Product[]>([])

// Hàm lấy sản phẩm tương tự
const fetchSimilarProducts = async () => {
  if (!product.value) return

  const { data, error } = await useClientFetch<Product[]>(
    `products?category_id=${product.value.category_id}&limit=12&orderBy=created_at&orderDirection=desc`
  )

  if (!error.value && data.value) {
    // Lọc ra sản phẩm khác với sản phẩm hiện tại
    similarProducts.value = data.value.filter((p) => p.id !== product.value?.id).slice(0, 12)
  }

  isLoading.value = false
}

// Gọi API lấy sản phẩm tương tự khi component được mounted
onMounted(async () => {
  if (product.value) {
    await fetchSimilarProducts()
  }
})

const showFullDescription = ref<Boolean>(false)

// Chỉ tính toán discount khi có dữ liệu sản phẩm
const discount = computed(() => {
  if (!product.value) return 0
  return Math.round(((product.value.price - product.value.sale_price) / product.value.price) * 100)
})

// Sử dụng computed để đảm bảo form luôn được cập nhật khi product thay đổi
const form = computed(() => {
  if (!product.value) return { product_id: 0, quantity: 1, total: 0 }

  return {
    product_id: product.value.id,
    quantity: quantityInput.value,
    total: quantityInput.value * product.value.sale_price
  }
})

const message = ref('Thêm vào giỏ hàng thành công')
const status = ref('success')
const showToast = ref(false)

// Sử dụng cartCount từ composable
const { increaseCartCount, updateCartCount } = useCartEvents()
const auth = useAuth()
const quantityInput = ref(1)

function toggleDescription() {
  showFullDescription.value = !showFullDescription.value
}

// Chỉ thiết lập SEO meta khi có dữ liệu sản phẩm
if (product.value) {
  useSeoMeta({
    title: product.value.seo_title,
    description: product.value.seo_description,
    ogTitle: product.value.seo_title,
    ogDescription: product.value.seo_description,
    ogImage: mediaUrl + product.value.thumbnail,
    ogUrl: appUrl + `/products/${product.value.seo_url}`,
    ogSiteName: product.value.seo_title,
    ogType: 'article',
    twitterTitle: product.value.seo_title,
    twitterDescription: product.value.seo_description,
    twitterImage: mediaUrl + product.value.thumbnail,
    twitterCard: 'summary_large_image'
  })
}

const increaseQuantity = () => {
  quantityInput.value++
}

const decreaseQuantity = () => {
  if (quantityInput.value > 1) {
    quantityInput.value--
  }
}

const addToCart = async () => {
  if (!product.value) return

  const user = await useAuth().getUser()

  if (user === null) {
    let cart = []
    if (localStorage.getItem('cart')) {
      cart = JSON.parse(localStorage.getItem('cart') as string)
    }

    const productIndex = cart.findIndex((item: any) => item.product_id === product.value?.id)
    if (productIndex > -1) {
      cart[productIndex].quantity += form.value.quantity
    } else {
      cart.push({
        ...form.value,
        product: product.value
      })
    }
    localStorage.setItem('cart', JSON.stringify(cart))
    showToastFunction('Thêm vào giỏ hàng thành công', 'success')

    // Cập nhật số lượng giỏ hàng
    increaseCartCount(form.value.quantity)
    return
  }

  const { error } = await useClientFetch('add-to-cart', { body: form.value, method: 'POST' })

  if (error.value) {
    showToastFunction('Đã có lỗi xảy ra, vui lòng thử lại sau', 'error')
  } else {
    showToastFunction('Thêm vào giỏ hàng thành công', 'success')

    // Cập nhật số lượng giỏ hàng
    increaseCartCount(form.value.quantity)
  }
}

const buyNow = async () => {
  await addToCart()
  navigateTo('/cart')
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
  <div v-if="isLoading" class="flex justify-center items-center min-h-[50vh]">
    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
  </div>

  <div v-else-if="product" class="">
    <!-- Breadcrumb -->
    <div class="text-gray-600 mb-4 font-normal">Trang chủ > {{ product.name }}</div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <!-- Left Column -->
      <div class="col-span-1">
        <div class="bg-white rounded-xl p-5 space-y-4 sticky top-3">
          <div class="image-gallery">
            <img :src="mediaUrl + product.thumbnail" alt="Book Cover" class="w-full rounded-lg border" />
          </div>
          <div class="flex space-x-2">
            <template v-for="image in product.images">
              <img :src="mediaUrl + image.path" alt="Thumbnail 2" class="w-20 h-20 object-cover rounded-md border" />
            </template>
          </div>
          <div class="text-gray-700 mt-4">
            <ul class="space-y-2">
              <li class="font-normal flex">
                <CheckBadgeIcon class="w-7 h-7 text-indigo-700 me-2" />
                Đảm bảo hàng chính hãng.
              </li>
              <li class="font-normal flex">
                <CheckBadgeIcon class="w-7 h-7 text-indigo-700 me-2" />
                Hoàn tiền 100% nếu phát hiện hàng giả.
              </li>
              <li class="font-normal flex">
                <CheckBadgeIcon class="w-7 h-7 text-indigo-700 me-2" />
                Giao hàng toàn quốc, chỉ từ 2h.
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="space-y-4 col-span-2">
        <div class="bg-white rounded-xl p-5">
          <div class="flex gap-2 mb-1">
            <img src="/topdeal2.png" alt="topdeal" width="80" />
            <img src="/chinhhang.png" alt="chinhhang" width="90" />
          </div>
          <h1 class="text-2xl font-semibold text-gray-900">{{ product.name }}</h1>
          <div class="flex my-2 items-center">
            <span class="me-2">4.8</span>
            <template v-for="i in 5">
              <StarIcon class="w-5 h-5 text-yellow-300" />
            </template>
            <span class="font-normal text-gray-500 ms-2">(2032)</span>
            <span class="w-[1px] h-[16px] mt-1 bg-gray-300 ms-3"></span>
            <span class="font-normal text-gray-500 ms-2">Đã bán {{ product.sold }}</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-red-700 font-semibold text-2xl">{{ formatCash(product.sale_price.toString()) }} đ</span>
            <span class="block p-1 rounded-lg bg-gray-100 font-normal text-sm">-{{ discount }}%</span>
          </div>
        </div>

        <div class="bg-white rounded-xl p-5">
          <div class="">
            <p class="text-gray-600">Thông tin vận chuyển</p>
            <div class="flex items-center justify-between">
              <p class="font-normal">Giao đến HCM, Việt Nam</p>
              <p class="text-indigo-700 cursor-pointer">Đổi</p>
            </div>
          </div>
          <hr class="my-3" />

          <!-- Shipping Option 1 -->
          <div class="flex items-center">
            <span class="text-red-700 font-bold mr-2">NOW</span>
            <div class="flex-1">
              <p class="text-gray-800">Giao siêu tốc 2h</p>
            </div>
          </div>
          <p class="text-gray-500 font-normal">Trước 12h hôm nay: 25.000₫</p>

          <!-- Shipping Option 2 -->
          <div class="flex items-center mt-2">
            <span class="text-indigo-700 mr-2">🌅</span>
            <div class="flex-1">
              <p class="text-gray-800">Giao đúng sáng mai</p>
            </div>
          </div>
          <p class="text-gray-500 font-normal">8h - 12h, 24/08: 16.500₫</p>
        </div>

        <div class="bg-white rounded-xl p-5">
          <h2 class="text-lg font-semibold mb-2">Thông tin chi tiết</h2>
          <div class="grid grid-cols-2 gap-y-2">
            <template v-for="(value, name) in JSON.parse(String(product.attributes))">
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">{{ name }}</div>
              <div class="py-2 border-b border-gray-200 font-normal">{{ value }}</div>
            </template>
          </div>
        </div>

        <div class="bg-white rounded-xl p-5 flex flex-col">
          <h2 class="text-lg font-semibold mb-2">Mô tả sản phẩm</h2>
          <div :class="{ blurred: !showFullDescription }" class="text-gray-700">
            <div class="space-y-2 text-justify font-normal">
              {{ product.description }}
            </div>
          </div>
          <button @click="toggleDescription" class="text-indigo-700 mt-2">
            {{ showFullDescription ? 'Thu gọn' : 'Xem thêm' }}
          </button>
        </div>

        <div class="bg-white rounded-xl p-5">
          <p class="text-xl mb-3">Sản phẩm tương tự</p>
          <div class="flex gap-3 lg:flex-wrap lg:overflow-hidden overflow-x-auto">
            <template v-for="p in similarProducts">
              <Product
                v-if="p.id !== product.id && p.category_id == product.category_id"
                class="basis-[130px]"
                :product="p"
              />
            </template>
          </div>
        </div>
      </div>
      <div class="col-span-1">
        <div class="bg-white rounded-xl p-5 sticky top-3">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
              <img src="/logo.svg" alt="Tiki Logo" class="w-20 mr-2" />
              <div>
                <p>BRTGo Trading</p>
                <div class="flex items-center">
                  <CheckBadgeIcon class="h-4 w-4 text-indigo-700 me-1"></CheckBadgeIcon>
                  <p class="text-indigo-700">OFFICIAL</p>
                  <div class="w-[1px] h-4 bg-gray-300 mx-2"></div>
                  <div class="flex items-center ml-2 text-gray-600">
                    <span class="text-sm">4.7</span>
                    <StarIcon class="h-3 w-3 text-yellow-300 mx-1"></StarIcon>
                    <span class="text-sm">(5.4tr+ đánh giá)</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quantity Selector -->
          <div class="mb-4">
            <p class="text-gray-600">Số Lượng</p>
            <div class="flex items-center mt-2">
              <button
                aria-label="decrease"
                @click.prevent="decreaseQuantity"
                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l"
              >
                <MinusIcon class="h-4 w-4 text-gray-600"></MinusIcon>
              </button>
              <input
                type="text"
                class="w-10 h-8 text-center border-t border-b border-gray-300"
                v-model="quantityInput"
              />
              <button
                aria-label="increase"
                @click.prevent="increaseQuantity"
                class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r"
              >
                <PlusIcon class="h-4 w-4 text-gray-600"></PlusIcon>
              </button>
            </div>
          </div>

          <!-- Subtotal -->
          <div class="my-5">
            <p class="text-lg mb-3">Tạm tính</p>
            <p class="text-red-700 font-semibold text-2xl">{{ formatCash(form.total.toString()) }} đ</p>
          </div>

          <!-- Action Buttons -->
          <div class="space-y-2">
            <button @click.prevent="buyNow" class="w-full bg-red-700 text-white py-2 rounded-lg">Mua ngay</button>
            <button @click.prevent="addToCart" class="w-full border border-indigo-700 text-indigo-700 py-2 rounded-lg">
              Thêm vào giỏ
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="flex flex-col items-center justify-center min-h-[50vh]">
    <h1 class="text-2xl font-semibold mb-4">Không tìm thấy sản phẩm</h1>
    <p class="text-gray-500 mb-6">Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
    <NuxtLink to="/" class="bg-indigo-600 text-white px-6 py-2 rounded-lg">Quay lại trang chủ</NuxtLink>
  </div>
  <Toast :message="message" :type="status as 'success' | 'error'" :show="showToast" />
</template>

<style scoped>
/* Blurred effect */
.blurred {
  position: relative;
  overflow: hidden;
  max-height: 250px;
}
.blurred::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 200px;
  background: linear-gradient(to top, #f9fafb, rgba(249, 250, 251, 0));
}
</style>
