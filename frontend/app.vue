<template>
    <div>
        <NuxtLayout>
           <NuxtPage/>
        </NuxtLayout>
    </div>
</template>
<script setup lang="ts">

const { title, app_url, icon, description } = useAppConfig()

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
  ]
})

useSeoMeta({
  title: title,
  description: description,
  ogTitle: title,
  ogDescription: description,
  ogImage: icon,
  ogUrl: app_url,
  twitterTitle: title,
  twitterDescription: description,
  twitterImage: icon,
  twitterCard: 'summary_large_image',
})

</script>

