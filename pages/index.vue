<template>
  <MainLayout>
    <div class="grid grid-cols-6 gap-8">
      <div class="col-span-1 xl:block hidden">
          <Sidebar/>
      </div>
      <div class="xl:col-span-5 col-span-full space-y-5">
        <div class="rounded-xl lg:bg-white lg:p-5">
          <Carousel/>
        </div>
        <div class="rounded-xl bg-white p-4">
          <div class="grid lg:grid-cols-10 gap-4 grid-cols-5">
            <div v-for="category in categories" :key="category.name" class="flex flex-col items-center">
              <img :src="category.img" alt="category" class="w-16 h-16 rounded-xl"/>
              <span class="lg:text-sm text-[10px] text-gray-700">{{ category.name }}</span>
            </div>
          </div>
        </div>
        <div class="rounded-xl bg-white p-5 py-4">
          <div class="flex justify-between items-center mb-4">
            <img src="/topdeal3.png" alt="topdeal" class="w-[200px]">
            <span class="text-blue-500 font-semibold">Xem thêm</span>
          </div>
          <div class="flex gap-3 overflow-x-auto">
            <template v-for="product in products">
                <Product class="basis-1/6" :product="product"/>
            </template>
          </div>
        </div>
        <div class="rounded-xl bg-white p-5 py-4">
          <div class="flex justify-between items-center  mb-4">
            <h3 class="">Hàng ngoại giá hot</h3>
            <span class="text-blue-500 font-semibold">Xem thêm</span>
          </div>
          <div class="flex gap-3 overflow-x-auto scrollb">
            <template v-for="product in products1">
              <Product class="basis-1/6" :product="product"/>
            </template>
          </div>
        </div>
        <div class="rounded-xl bg-white p-5">
          <div class="grid lg:grid-cols-6 grid-cols-3  gap-3 overflow-x-auto scrollb">
            <template v-for="index in 12">
              <img :src="`/banner${index}.png`" alt="banner" class=" rounded-xl"/>
            </template>
          </div>
        </div>
        <div class="rounded-xl bg-white p-5 lg:block hidden">
          <Footer/>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
<script setup lang="ts">
import MainLayout from "~/layouts/MainLayout.vue";

const categories = [
  {
    img: "/topdeal.png",
    name: "TOP DEAL"
  },
  {
    img: "/trading.png",
    name: "Tiki Trading"
  },
  {
    img: "/hot.png",
    name: "Coupon siêu hot"
  },
  {
    img: "/sale.png",
    name: "Siêu sale"
  },
  {
    img: "/hot2.png",
    name: "Hàng quốc tế"
  },
  {
    img: "/topdeal.png",
    name: "TOP DEAL"
  },
  {
    img: "/trading.png",
    name: "Tiki Trading"
  },
  {
    img: "/hot.png",
    name: "Coupon siêu hot"
  },
  {
    img: "/sale.png",
    name: "Siêu sale"
  },
  {
    img: "/hot2.png",
    name: "Hàng quốc tế"
  }
]

const products = [
  {
    img: "/product1.png",
    name: "Một thoáng ta rực rỡ ở nhân gian",
  },
  {
    img: "/product2.png",
    name: "Máy đọc sách New Kindle chính hãng",
  },
  {
    img: "/product3.png",
    name: "Túi nước giặt Omo 6kg hương mẫu ngẫu nhiên",
  },
  {
    img: "/product4.png",
    name: "[Mua 2 giảm 50K] Bộ 3 hộp sữa bột Ensure Gold 850g",
  },
  {
    img: "/product5.png",
    name: "Chảo inox nguyên khối Sunhouse 24cm",
  },
  {
    img: "/product6.png",
    name: "Máy ép chậm Kuvings B6000 chính hãng",
  }
]

const products1 = [
  {
    img: "/product7.png",
    name: "Mặt nạ hỗ trợ trị mụn và dưỡng ẩm Some By Mi Yuja Niacin 30 Days Blemish Care Serum Mask",
  },
  {
    img: "/product8.png",
    name: "Loa Harman Kardon Onyx Studio 6 chính hãng",
  },
  {
    img: "/product9.png",
    name: "Nước cân bằng da Some By Mi Yuja Niacin 30 Days Brightening Toner",
  },
  {
    img: "/product10.png",
    name: "Sữa rửa mặt Some By Mi Yuja Niacin 30 Days Brightening Face Wash",
  },
  {
    img: "/product11.png",
    name: "Siêu mặt nạ Some By Mi Yuja Niacin 30 Days Brightening Sleeping Mask",
  },
  {
    img: "/product12.png",
    name: "Sữa Meji 0% đường 200ml hương vani hộp 6 lon",
  }
]

</script>
