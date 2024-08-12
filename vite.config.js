import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      ziggy: path.resolve(__dirname, 'vendor/tightenco/ziggy/dist/vue.es.js') // Chemin complet vers Ziggy
    },
  },
  server:{
    host: 'localhost',
    port: 8081
  },
  base: '/'
});
