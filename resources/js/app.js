import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// cut and pasted from bootstrap
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// ---

// const base_url = import.meta.env.VITE_SERVER_SUBDIR

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})