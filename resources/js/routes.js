import { createWebHistory, createRouter } from 'vue-router'

const Home = () => import('./Pages/Home.vue')

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },

]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router



// {
//     path: '/:pathMatch(.*)*',
//     name: 'NotFound',
//     component: NotFound,
// },
