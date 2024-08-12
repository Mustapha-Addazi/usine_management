import './bootstrap';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy';
import { createInertiaApp } from '@inertiajs/vue3';
import VueDOMPurifyHTML from 'vue-dompurify-html';
import MainLayout from '@/Pages/Layout/MainLayout.vue'; // Utilisation de l'alias '@'
import { InertiaProgress } from '@inertiajs/progress';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    let page = pages[`./Pages/${name}.vue`];
    if (!page) {
      console.error(`Le composant pour la page "${name}" est introuvable.`);
      return;
    }
    page.default.layout = page.default.layout || MainLayout;
    return page;
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(VueDOMPurifyHTML)
      .use(ZiggyVue, Ziggy)
      .mount(el);
  },
});

InertiaProgress.init();
