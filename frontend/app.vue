<template>
  <div>
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </div>
</template>
<script setup lang="ts">
const { title, icon, description } = useAppConfig()
const { appUrl, googleAnalyticsId = 'G-MMTGWLG5P3' } = useRuntimeConfig().public

useHead({
  title: title,
  htmlAttrs: {
    lang: 'vi'
  },
  link: [
    {
      rel: 'icon',
      type: 'image/x-icon',
      href: icon
    }
  ],
  script: [
    {
      src: `https://www.googletagmanager.com/gtag/js?id=${googleAnalyticsId}`,
      async: true
    },
    {
      innerHTML: `window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '${googleAnalyticsId}');`,
      type: 'text/javascript'
    }
  ]
})

useSeoMeta({
  title: title,
  description: description,
  ogTitle: title,
  ogDescription: description,
  ogImage: icon,
  ogUrl: appUrl,
  twitterTitle: title,
  twitterDescription: description,
  twitterImage: icon,
  twitterCard: 'summary_large_image'
})
</script>

<style scoped>
* {
  scrollbar-width: thin;
  scrollbar-color: #f3f4f6 #d1d5db;
}
</style>
