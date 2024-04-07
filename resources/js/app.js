import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import router from './routes.js'

// cut and pasted from bootstrap
import axios from 'axios';
window.axios = axios;
window.axios.defaults.baseURL = 'api/'
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
        .use(router)
      .mount(el)
  },
})