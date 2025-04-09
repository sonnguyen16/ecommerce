import { ref } from 'vue'

// Tạo một ref để lưu số lượng sản phẩm trong giỏ hàng
const cartCount = ref(0)

export const useCartEvents = () => {
  // Phương thức lấy số lượng hiện tại
  const getCartCount = () => cartCount.value

  // Phương thức cập nhật số lượng
  const setCartCount = (count: number) => {
    cartCount.value = count
  }

  // Phương thức tăng số lượng
  const increaseCartCount = (quantity = 1) => {
    cartCount.value += quantity
  }

  // Phương thức giảm số lượng
  const decreaseCartCount = (quantity = 1) => {
    cartCount.value = Math.max(0, cartCount.value - quantity)
  }

  // Phương thức reset số lượng về 0
  const resetCartCount = () => {
    cartCount.value = 0
  }

  // Phương thức cập nhật số lượng từ localStorage hoặc API
  const updateCartCount = async () => {
    try {
      // Kiểm tra nếu người dùng đã đăng nhập
      const user = await useAuth().getUser()

      if (user) {
        // Lấy giỏ hàng từ API nếu đã đăng nhập
        const { data }: any = await useClientFetch('cart')
        cartCount.value = data.value?.length || 0
      } else if (typeof window !== 'undefined') {
        // Lấy giỏ hàng từ localStorage nếu chưa đăng nhập
        const cartLocal = JSON.parse(localStorage.getItem('cart') || '[]')
        cartCount.value = cartLocal.length || 0
      }
    } catch (error) {
      console.error('Lỗi khi lấy số lượng giỏ hàng:', error)
    }
  }

  return {
    cartCount,
    getCartCount,
    setCartCount,
    increaseCartCount,
    decreaseCartCount,
    resetCartCount,
    updateCartCount
  }
}
