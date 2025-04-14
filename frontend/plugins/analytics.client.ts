// Google Analytics integration
export default defineNuxtPlugin(() => {
  const { public: { googleAnalyticsId = 'G-MMTGWLG5P3' } } = useRuntimeConfig();

  useHead({
    script: [
      {
        src: `https://www.googletagmanager.com/gtag/js?id=${googleAnalyticsId}`,
        async: true
      },
      {
        innerHTML: `window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '${googleAnalyticsId}');`
      }
    ]
  });
});