<script setup>
    import { Head, router } from '@inertiajs/vue3';
    import Layout from '@/Pages/Layout.vue'
    import { reactive } from 'vue'
    import { base_url } from '@/base_url.js'

    defineProps({
        errors: Object
    })

    const form = reactive({
        username_email: null,
        password: null,
        remember: false
    });

    function submit() {

        let username = null, email = null
        // check if they've given a username and an email
        if (form.username_email?.includes('@')) email = form.username_email
        else username = form.username_email

        router.post(base_url + 'login', {
            username: username,
            email: email,
            password: form.password,
            remember: form.remember
        })
    }
</script>

<template>

    <Head title="LOGIN | Laravel 10, Vue3, Inertia 1.0 template" />
    <Layout page="Login">

        <div v-if="errors.authentication" class="text-red-500">{{ errors.authentication }}</div>

        <form @submit.prevent="submit">
            <div class="m-auto w-full sm:w-2/3 lg:w-1/3 mt-16">
                <div class="mb-3">
                    Username or Email:
                    <br>
                    <input
                        type="text"
                        v-model="form.username_email"
                        class="block w-full border-2 border-black rounded p-1 text-black"
                        maxlength="96"
                        minlength="3"
                        required
                    >
                    <div v-if="errors.username" class="text-xs text-red-500">{{ errors.username }}</div>
                    <div v-if="errors.email" class="text-xs text-red-500">{{ errors.email }}</div>
                </div>

                <div class="mb-3">
                    Password:
                    <br>
                    <input
                        type="password"
                        v-model="form.password"
                        class="block w-full border-2 border-black rounded p-1 text-black"
                        maxlength="32"
                        minlength="8"
                        required
                    >
                    <div v-if="errors.password" maxlength="32" class="text-xs text-red-500">{{ errors.password }}</div>
                </div>

                <div class="mb-3">
                    <label>
                        <input type="checkbox" v-model="form.remember" class="mr-3 border border-black"> Remember me on this computer ?
                    </label>

                </div>

                <button class="border-2 border-black rounded py-1 px-5 mt-2 text-black text-2xl" type="submit">Login</button>
            </div>
        </form>

    </Layout>

</template>
