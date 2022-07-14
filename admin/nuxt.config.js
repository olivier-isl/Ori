export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'admin',
    htmlAttrs: {
      lang: 'fr'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: "preconnect", href: "https://fonts.googleapis.com"},
      { rel: "preconnect", href: "https://fonts.gstatic.com", crossorigin: "true"},
      { href: "https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;600;700&display=swap", rel: "stylesheet"},
      { href: "https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.14/semantic.min.css", rel: "stylesheet"},
      { href: "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css", rel: "stylesheet"}
    ]
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    "~/assets/scss/main.scss",
    "@sweetalert2/theme-dark"
  ],

  styleResources: {
    scss: ['~/node_modules/bootstrap/scss/bootstrap.scss', '~/assets/scss/*.scss'],
  },

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    { src: '~plugins/quill.js', ssr: false },
    { src: '~/plugins/vue-moment.js', ssr: false },
    { src: '~/plugins/filters.js', ssr: false },
    { src: '~/plugins/vuedraggable.js', ssr: false },
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    '@nuxtjs/eslint-module',
    // https://go.nuxtjs.dev/stylelint
    '@nuxtjs/stylelint-module',
    '@nuxtjs/fontawesome',
  ],

  fontawesome: {
    icons: {
      solid: true
    },
    component: 'Fa',
    suffix: true
  },

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    'bootstrap-vue/nuxt',
    'vue-sweetalert2/nuxt',
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/i18n',
    'nuxt-vue-select',
  ],

  i18n: {
    locales: [
      { code: 'en', iso: 'en-US', file: 'en.json' },
      { code: 'fr', iso: 'fr-FR', file: 'fr.json' },
    ],
    lazy: true,
    defaultLocale: 'fr',
    langDir : "~/static/lang/",
    vueI18n: {
      fallbackLocale: 'fr',
    }
  },
  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    BaseURL: 'http://127.0.0.1:8000/api/'
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },

  compilerOptions: {
    types: [
      "@nuxt/types",
      "@nuxtjs/axios"
    ]
  }
}
