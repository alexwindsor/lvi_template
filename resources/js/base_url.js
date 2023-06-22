import { reactive } from "vue";

export let baseUrl = reactive({
    base_url: import.meta.env.VITE_SERVER_SUBDIR
})
