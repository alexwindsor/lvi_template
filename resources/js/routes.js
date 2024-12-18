import { createWebHistory, createRouter } from 'vue-router'

const Home = () => import('./Pages/Home.vue')
const Register = () => import('./Pages/Auth/Register.vue')
const Login = () => import('./Pages/Auth/Login.vue')
const Logout = () => import('./Pages/Auth/Logout.vue')

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/',
        name: 'Register',
        component: Register
    },
    {
        path: '/',
        name: 'Login',
        component: Login
    },
    {
        path: '/',
        name: 'Logout',
        component: Logout
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
