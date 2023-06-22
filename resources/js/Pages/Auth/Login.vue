<script setup>
import { Head, router } from '@inertiajs/vue3';
import Layout from '@/Pages/Layout.vue'
import { baseUrl } from '@/base_url.js'
import { reactive } from 'vue'

defineProps({
    errors: Object
})

const form = reactive({
    email: 'alexwindsormusic@gmail.com',
    password: 'password'
});

function submit() {
    router.post(baseUrl.base_url + 'login', form)
}

</script>

<template>
    <Head title="login" />

<Layout page="Login">
    
    <div v-if="errors.authentication" class="text-red-500">{{ errors.authentication }}</div>

<form @submit.prevent="submit">
    <div class="m-auto w-full sm:w-2/3 lg:w-1/3 mt-16">
        <div class="mb-3">
            Email:
            <br>
            <input type="email" v-model="form.email" class="block w-full border-2 border-black rounded p-1 text-black">
            <div v-if="errors.email" class="text-xs text-red-500">{{ errors.email }}</div>
        </div>

        <div class="mb-3">
            Password:
            <br>
            <input type="password" v-model="form.password" class="block w-full border-2 border-black rounded p-1 text-black">
            <div v-if="errors.password" class="text-xs text-red-500">{{ errors.password }}</div>
        </div>
        <br>
        <button class="border-2 border-black rounded p-1 text-black text-2xl" type="submit">Login</button>
    </div>
</form>



</Layout>
</template>
