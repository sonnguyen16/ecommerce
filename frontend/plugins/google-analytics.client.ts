export default defineNuxtPlugin((nuxtApp) => {
  nuxtApp.hook('app:mounted', () => {
    const router = useRouter()

    const script = document.createElement('script')
    script.async = true
    script.src = 'https://www.googletagmanager.com/gtag/js?id=G-MMTGWLG5P3'
    document.head.appendChild(script)

    window.dataLayer = window.dataLayer || []
    function gtag(...args: any[]) {
      window.dataLayer.push(args)
    }
    gtag('js', new Date())
    gtag('config', 'G-MMTGWLG5P3')

    router.afterEach((to) => {
      gtag('config', 'G-MMTGWLG5P3', {
        page_path: to.fullPath,
        page_location: window.location.origin + to.fullPath
      })
    })
  })
})

// Định nghĩa thêm cho TypeScript
declare global {
  interface Window {
    dataLayer: any[]
  }
}
