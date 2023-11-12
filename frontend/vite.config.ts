import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve, dirname, basename } from 'node:path'
import { fileURLToPath } from 'url'
import VueI18nPlugin from '@intlify/vite-plugin-vue-i18n'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue({
      template: {
        compilerOptions: {
          isCustomElement: (tag) => ['va-stepper'].includes(tag),
        },
      },
    }),
    VueI18nPlugin({
      include: resolve(dirname(fileURLToPath(import.meta.url)), './src/i18n/locales/**'),
    }),
  ],
  base: '/rosemeri-beauty/dist/',
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('.src', import.meta.url)),
    },
  },
})
