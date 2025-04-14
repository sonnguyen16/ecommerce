import { defineNuxtPlugin } from 'nuxt/app'

export default defineNuxtPlugin((nuxtApp) => {
  // Lắng nghe sự kiện khi điều hướng trang để theo dõi pageviews trong SPA
  nuxtApp.hook('page:finish', () => {
    // Đảm bảo gtag đã được định nghĩa trước khi sử dụng
    if (typeof window !== 'undefined' && window.gtag) {
      window.gtag('event', 'page_view', {
        page_path: window.location.pathname,
        page_location: window.location.href,
        page_title: document.title
      })
    }
  })
})
