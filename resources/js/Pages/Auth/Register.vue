<script setup>
    import { Head, router } from '@inertiajs/vue3';
    import Layout from '@/Pages/Layout.vue'
    import { reactive } from 'vue'
    import { base_url } from '@/base_url.js'

    defineProps({
        errors: Object
    })

    const form = reactive({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    function submit() {
        router.post(base_url + 'register', form)
    }
</script>

<template>
    <Head title="REGISTER | Laravel 10, Vue3, Inertia 1.0 template" />

    <Layout page="Register">
        
    <form @submit.prevent="submit">
        <div class="mx-auto mt-16 w-full sm:w-2/3 lg:w-1/3">
            <div class="mb-3">
                Name:
                <br>
                <input type="text" v-model="form.name" class="block w-full border-2 border-black rounded p-1 text-black">
                <div v-if="errors.name" class="text-xs text-red-500">{{ errors.name }}</div>
            </div>

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

            <div class="mb-3">
                Confirm Password:
                <br>
                <input type="password" v-model="form.password_confirmation" class="block w-full border-2 border-black rounded p-1 text-black">
                <div v-if="errors.password_confirmation" class="text-xs text-red-500">{{ errors.password_confirmation }}</div>
            </div>

            <button class="border-2 border-black rounded py-1 px-5 mt-2 text-black text-2xl" type="submit">Register</button>
        </div>
    </form>
    


    </Layout>
</template>
