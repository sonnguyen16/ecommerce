export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: false },
  modules: ['@nuxtjs/tailwindcss', '@nuxt/scripts'],
  scripts: {
    registry: {
      googleAnalytics: {
        id: 'G-MMTGWLG5P3'
      }
    }
  },
  runtimeConfig: {
    public: {
      appUrl: process.env.APP_URL,
      mediaUrl: process.env.MEDIA_URL,
      cookieDomain: process.env.COOKIE_DOMAIN
    },
    private: {
      apiUrl: process.env.API_URL
    }
  }
})
