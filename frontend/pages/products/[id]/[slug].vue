<script setup lang="ts">
import MainLayout from "~/layouts/MainLayout.vue";
import {CheckBadgeIcon} from "@heroicons/vue/24/solid";
import {StarIcon} from "@heroicons/vue/24/solid";
import {ref} from "vue";
import useFetchData from "~/composables/usePostData";
import {MEDIA_ENDPOINT} from "~/lib/constants";
import {formatCash} from "~/lib/utils";
import type {Product} from "~/lib/schema";

const { app_url, title } = useAppConfig()

const { data }: { data: Ref<Product[]> } = await useFetchData(`products`)

const showFullDescription = ref<Boolean>(false);

const route = useRoute()

const id = route.params.id

const product: Product = data.value.find((item: Product) => String(item.id ) === id) as Product

function toggleDescription() {
  showFullDescription.value = !showFullDescription.value;
}

useSeoMeta({
  title: product.seo_title,
  description: product.seo_description,
  ogTitle: product.seo_title,
  ogDescription: product.seo_description,
  ogImage: MEDIA_ENDPOINT + product.thumbnail,
  ogUrl: app_url + route.fullPath,
  ogSiteName: product.seo_title,
  ogType: 'article',
  twitterTitle: product.seo_title,
  twitterDescription: product.seo_description,
  twitterImage: MEDIA_ENDPOINT + product.thumbnail,
  twitterCard: 'summary_large_image'
})

</script>

<template>
  <MainLayout>
    <div class="">
      <!-- Breadcrumb -->
      <div class="text-gray-600 mb-4 font-normal">
       Trang chủ > {{ product.name }}
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Left Column -->
        <div class="col-span-1">
          <div class="bg-white rounded-xl p-5 space-y-4 sticky top-3">
            <div class="image-gallery">
                <img :src="MEDIA_ENDPOINT + product.thumbnail" alt="Book Cover" class="w-full rounded-lg border">
            </div>
            <div class="flex space-x-2">
              <img src="/thumbnail1.png" alt="Thumbnail 1" class="w-20 h-20 object-cover rounded-md border-blue-500 border-2">
              <img src="/thumbnail2.png" alt="Thumbnail 2" class="w-20 h-20 object-cover rounded-md border">
              <img src="/thumbnail3.png" alt="Thumbnail 2" class="w-20 h-20 object-cover rounded-md border">
            </div>
            <div class="text-gray-700 mt-4">
              <ul class="space-y-2">
                <li class="font-normal flex">
                  <CheckBadgeIcon class="w-7 h-7 text-blue-500 me-2"/>
                  Đảm bảo hàng chính hãng.
                </li>
                <li class="font-normal flex">
                  <CheckBadgeIcon class="w-7 h-7 text-blue-500 me-2"/>
                  Hoàn tiền 100% nếu phát hiện hàng giả.
                </li>
                <li class="font-normal flex">
                  <CheckBadgeIcon class="w-7 h-7 text-blue-500 me-2"/>
                  Giao hàng toàn quốc, chỉ từ 2h.
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-4 col-span-2">
          <div class=" bg-white rounded-xl p-5">
            <div class="flex gap-2 mb-1">
              <img src="/topdeal2.png" alt="topdeal" width="80">
              <img src="/chinhhang.png" alt="chinhhang" width="90">
            </div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ product.name }}</h1>
            <div class="flex my-2 items-center">
              <span class="me-2">4.8</span>
              <template v-for="i in 5">
                <StarIcon class="w-5 h-5 text-yellow-300"/>
              </template>
              <span class="font-normal text-gray-500 ms-2">(2032)</span>
              <span class="w-[1px] h-[16px] mt-1 bg-gray-300 ms-3"></span>
              <span class="font-normal text-gray-500 ms-2">Đã bán {{ product.sold }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-red-500 font-semibold text-2xl">{{ formatCash(Math.round(product.price - product.price / 100 * product.discount).toString()) }} đ</span>
              <span v-if="product.discount" class="block p-1 rounded-lg bg-gray-100 font-normal text-sm">-{{product.discount}}%</span>
            </div>
          </div>

          <div class=" bg-white rounded-xl p-5">
            <div class="">
              <p class="text-gray-600">Thông tin vận chuyển</p>
              <div class="flex items-center justify-between">
                <p class="font-normal">Giao đến Hà Nội, Việt Nam</p>
                <p class="text-blue-500 cursor-pointer">Đổi</p>
              </div>
            </div>
            <hr class="my-3">

            <!-- Shipping Option 1 -->
            <div class="flex items-center">
              <span class="text-red-500 font-bold mr-2">NOW</span>
              <div class="flex-1">
                <p class="text-gray-800">Giao siêu tốc 2h</p>
              </div>
            </div>
            <p class="text-gray-500 font-normal">Trước 12h hôm nay: 25.000₫</p>

            <!-- Shipping Option 2 -->
            <div class="flex items-center mt-2">
              <span class="text-blue-500 mr-2">🌅</span>
              <div class="flex-1">
                <p class="text-gray-800">Giao đúng sáng mai</p>
              </div>
            </div>
            <p class="text-gray-500 font-normal">8h - 12h, 24/08: 16.500₫</p>
          </div>

          <div class=" bg-white rounded-xl p-5">
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
            <div :class="{'blurred': !showFullDescription}" class="text-gray-700">
              <div class="space-y-2 text-justify font-normal">
                {{ product.description }}
              </div>
            </div>
            <button @click="toggleDescription" class="text-blue-500 mt-2">{{ showFullDescription ? 'Thu gọn' : 'Xem thêm' }}</button>
          </div>

          <div class=" bg-white rounded-xl p-5">
            <p class="text-xl mb-3">Sản phẩm tương tự</p>
            <div class="flex gap-3 lg:flex-wrap lg:overflow-hidden overflow-x-auto">
              <template v-for="p in data.slice(0, 5)">
                <Product v-if="p.id != id && p.category_id == product.category_id" class="basis-[130px]" :product="p"/>
              </template>
            </div>
          </div>
        </div>
          <div class="col-span-1">
            <div class=" bg-white rounded-xl p-5 sticky top-3">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <img src="/tiki.png" alt="Tiki Logo" class="h-10 mr-2">
                  <div>
                    <p>Tiki Trading</p>
                    <div class="flex items-center">
                      <CheckBadgeIcon class="h-4 w-4 text-blue-500 me-1"></CheckBadgeIcon>
                      <p class="text-blue-500"> OFFICIAL</p>
                      <div class="w-[1px] h-4 bg-gray-300 mx-2"></div>
                      <div class="flex items-center ml-2 text-gray-600">
                        <span class="text-sm">4.7</span>
                        <svg class="h-4 w-4 text-yellow-400 mx-1" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 17.27l5.18 3.73-1.64-5.81L20 9.91h-6.05L12 4 10.05 9.91H4l4.46 4.29-1.64 5.81z"/>
                        </svg>
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
                  <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-l">
                    <svg class="h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                    </svg>
                  </button>
                  <input type="text" class="w-10 h-8 text-center border-t border-b border-gray-300" value="1">
                  <button class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-r">
                    <svg class="h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Subtotal -->
              <div class="my-5">
                <p class="text-lg mb-3">Tạm tính</p>
                <p class="text-2xl font-semibold text-red-500">{{ formatCash(Math.round(product.price - product.price / 100 * product.discount).toString()) }} đ</p>
              </div>

              <!-- Action Buttons -->
              <div class="space-y-2">
                <button class="w-full bg-red-500 text-white py-2 rounded-lg">Mua ngay</button>
                <button class="w-full border border-blue-500 text-blue-500 py-2 rounded-lg">Thêm vào giỏ</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  </MainLayout>
  <div class="bg-white container my-8 lg:block hidden">
    <Footer/>
  </div>
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