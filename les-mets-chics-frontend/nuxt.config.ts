export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxtjs/google-fonts',
    '@nuxtjs/color-mode',
    '@nuxt/icon',
    '@vueuse/motion/nuxt'
  ],

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api',
    }
  },

  googleFonts: {
    families: {
      'Playfair Display': [400, 500, 600, 700],
      'Inter': [300, 400, 500, 600, 700]
    }
  },

  colorMode: {
    classSuffix: ''
  },

  app: {
    head: {
      title: 'Les Mets Chics - Recettes Gastronomiques',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Découvrez des recettes gastronomiques raffinées' }
      ],
      link: [
        { rel: 'icon', type: 'image/png', href: '/lmc.png' }
      ]
    }
  }
})
