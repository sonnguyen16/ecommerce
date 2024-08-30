<script setup lang="ts">
import MainLayout from "~/layouts/MainLayout.vue";
import {CheckBadgeIcon} from "@heroicons/vue/24/solid";
import {StarIcon} from "@heroicons/vue/24/solid";
import {onMounted, ref} from "vue";

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
  },
  {
    img: "/product3.png",
    name: "Túi nước giặt Omo 6kg hương mẫu ngẫu nhiên",
  },
  {
    img: "/product4.png",
    name: "[Mua 2 giảm 50K] Bộ 3 hộp sữa bột Ensure Gold 850g",
  },
]

const showFullDescription = ref<Boolean>(false);

function toggleDescription() {
  showFullDescription.value = !showFullDescription.value;
}

</script>

<template>
  <MainLayout>
    <div class="">
      <!-- Breadcrumb -->
      <div class="text-gray-600 mb-4 font-normal">
       Trang chủ > Nhà Sách Tiki > Sách tiếng Việt > Sách văn học > Tiểu Thuyết Phương Đông > Một Thoáng Ta Rực Rỡ Ở Nhân Gian
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Left Column -->
        <div class="col-span-1">
          <div class="bg-white rounded-xl p-5 space-y-4 sticky top-3">
            <div class="image-gallery">
                <img src="/bookcover.png" alt="Book Cover" class="w-full rounded-lg border">
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
                  Đánh giá cao từ các báo lớn: The New York Times.
                </li>
                <li class="font-normal flex">
                  <CheckBadgeIcon class="w-7 h-7 text-blue-500 me-2"/>
                  Câu chuyện cảm động về quá trình tự khám phá.
                </li>
                <li class="font-normal flex">
                  <CheckBadgeIcon class="w-7 h-7 text-blue-500 me-2"/>
                  Ngôn ngữ sâu sắc, thân thiện và gần gũi.
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
            <h1 class="text-2xl font-semibold text-gray-900">Một Thoáng Ta Rực Rỡ Ở Nhân Gian</h1>
            <div class="flex my-2 items-center">
              <span class="me-2">4.8</span>
              <template v-for="i in 5">
                <StarIcon class="w-5 h-5 text-yellow-300"/>
              </template>
              <span class="font-normal text-gray-500 ms-2">(2032)</span>
              <span class="w-[1px] h-[16px] mt-1 bg-gray-300 ms-3"></span>
              <span class="font-normal text-gray-500 ms-2">Đã bán 14.763</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-red-500 font-semibold text-2xl">108.000đ</span>
              <span class="block p-1 rounded-lg bg-gray-100 font-normal text-sm">-20%</span>
            </div>
          </div>

          <div class=" bg-white rounded-xl p-5">
            <div class="">
              <p class="text-gray-600">Thông tin vận chuyển</p>
              <div class="flex items-center justify-between">
                <p class="font-normal">Giao đến Q. 1, P. Bến Nghé, Hồ Chí Minh</p>
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
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">Phiên bản sách</div>
              <div class="py-2 border-b border-gray-200 font-normal">Phiên bản thường</div>
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">Công ty phát hành</div>
              <div class="py-2 border-b border-gray-200 font-normal">Nhà Nam</div>
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">Ngày xuất bản</div>
              <div class="py-2 border-b border-gray-200 font-normal">01/12/2021</div>
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">Kích thước</div>
              <div class="py-2 border-b border-gray-200 font-normal">14 x 20.5 cm</div>
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">Dịch Giả</div>
              <div class="py-2 border-b border-gray-200 font-normal">Khánh Nguyên</div>
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">Loại bìa</div>
              <div class="py-2 border-b border-gray-200 font-normal">Bìa mềm</div>
              <div class="text-gray-600 py-2 border-b border-gray-200 font-normal">Số trang</div>
              <div class="py-2 border-b border-gray-200 font-normal">304</div>
              <div class="text-gray-600 py-2 font-normal">Nhà xuất bản</div>
              <div class="py-2 font-normal">Nhà Xuất Bản Hội Nhà Văn</div>
            </div>
          </div>

          <div class="bg-white rounded-xl p-5 flex flex-col">
            <h2 class="text-lg font-semibold mb-2">Mô tả sản phẩm</h2>
            <div :class="{'blurred': !showFullDescription}" class="text-gray-700">
              <div class="space-y-2" style="">
                <p class="font-normal">“Vuong thực sự có thiên tài quan sát.” The New York Times</p>
                <p class="font-normal">“Với một xuất thân bên rìa hết sức xa lạ, Vuong đã viết nên một tác phẩm trữ tình về quá trình tự khám phá chính mình, vừa thành thật đến choáng váng, vừa phổ quát trong từng câu chữ.” The Washington Post</p>
                <p class="font-normal">“Bằng sự chính xác của một nhà thơ, Ocean Vuong xem xét liệu biến kinh nghiệm của chúng ta thành từ ngữ có thể chữa lành những vết thương trải hàng thế hệ hay không, và liệu tiếng nói của ta có thể nào thực sự được nghe bởi những người ta yêu thương nhất.” Celeste Ng</p>
                <p class="font-normal">Một thoáng ta rực rỡ ở nhân gian viết dưới dạng một lá thư của nhân vật chính, Chó Con, gửi cho người mẹ không biết chữ của mình. Dưới dạng những mẩu chuyện nhỏ, xen kẽ với những đoạn trữ tình ngoại đề, triết lý, và những bài thơ, cuốn sách kể câu chuyện đời không chỉ của Chó Con (tên gọi yêu do bà ngoại đặt cho, nhưng cũng là cách tất cả mọi người trong sách gọi cậu) từ thuở nhỏ đến lúc chớm trưởng thành, mà cả ba thế hệ từ bà, đến mẹ, đến cậu, một cuộc di cư dài từ làng quê Việt Nam sang đất Mỹ, cũng như câu chuyện của những thanh niên Mỹ thế hệ cậu mà đặc trưng là người bạn trai Trevor.</p>
                <p class="font-normal">Cuốn sách thường được đọc như một Bildungsroman (tiểu thuyết trưởng thành), nhưng cũng có nhiều người coi đây là một Künstlerroman (tiểu thuyết kể về quá trình một người nghệ sĩ trở thành nghệ sĩ).</p>
                <h3>TÁC GIẢ:</h3>
                <p class="font-normal">Ocean Vuong sinh năm 1988 ở Sài Gòn với tên Vương Quốc Vinh.</p>
                <p class="font-normal">Ocean Vuong - đã sáng tác thơ và ấp ủ mong muốn được làm nhà thơ từ những năm cấp 3 - dần dần nhập vào giới thơ ca ở New York, rồi tiếp đó được nhận vào trường Brooklyn College thuộc Đại học thành phố New York. Anh đã xuất bản hai tập thơ mỏng (chapbook) No (2013) và Burnings (2010) cũng như tham gia nhiều cuộc biểu diễn thơ (open mic) trước khi tập thơ đầu tay, Night Time with Exit Wounds, giành được giải thưởng trong một cuộc thi và được chính thức xuất bản, biến anh trong chớp mắt thành một ngôi sao. Tiểu thuyết đầu tay Một thoáng ta rực rỡ ở nhân gian được cả đống báo lớn kể tên trong danh sách các cuốn sách đáng mong chờ nhất năm 2019, khi ra đời lập tức nhảy vào danh sách Bestseller của New York Times cùng được bầu chọn là Cuốn sách của năm của vô số báo, tạp chí, trang văn học mạng, và càn quét cả đống giải thưởng (chi tiết xem ở đây: ). Anh sắp cho ra mắt tập thơ mới với tựa đề Time is a Mother, nhiều bài trong đó nói về nỗi đau mất mát khi mẹ anh qua đời năm 2020.</p>
                <p class="font-normal">Hiện nay, Ocean Vuong tham gia giảng dạy chuyên ngành sáng tác ở trình độ Thạc sĩ nghệ thuật (MFA) ở trường Đại học Massachusetts Amherst.</p>
                </div>
            </div>
            <button @click="toggleDescription" class="text-blue-500 mt-2">{{ showFullDescription ? 'Thu gọn' : 'Xem thêm' }}</button>
          </div>

          <div class=" bg-white rounded-xl p-5">
            <p class="text-xl mb-3">Sản phẩm tương tự</p>
            <div class="flex gap-3 overflow-x-auto">
              <template v-for="product in products">
                <Product class="basis-1/6" :product="product"/>
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
                <p class="text-2xl font-semibold text-gray-800">109.000₫</p>
              </div>

              <!-- Action Buttons -->
              <div class="space-y-2">
                <button class="w-full bg-red-500 text-white py-2 rounded-lg">Mua ngay</button>
                <button class="w-full border border-blue-500 text-blue-500 py-2 rounded-lg">Thêm vào giỏ</button>
                <button class="w-full border border-blue-500 text-blue-500 py-2 rounded-lg">Mua trước trả sau</button>
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

.hide-scrollbar::-webkit-scrollbar {
  display: none;
}

.hide-scrollbar {
  -ms-overflow-style: none; /* Internet Explorer 10+ */
  scrollbar-width: none; /* Firefox */
}
</style>