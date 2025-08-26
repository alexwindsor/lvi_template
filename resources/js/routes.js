import { createWebHistory, createRouter } from 'vue-router'

const Home = () => import('./Pages/Home.vue')
const Register = () => import('./Pages/Auth/Register.vue')
const EditProfile = () => import('./Pages/Auth/EditProfile.vue')
const VerifyEmail = () => import('./Pages/Auth/VerifyEmail.vue')
const Login = () => import('./Pages/Auth/Login.vue')
const Logout = () => import('./Pages/Auth/Logout.vue')

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/edit_profile',
        name: 'edit_profile',
        component: EditProfile
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/logout',
        name: 'logout',
        component: Logout
    },
    {
        path: '/verify_email',
        name: 'verification.notice',
        component: VerifyEmail
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




