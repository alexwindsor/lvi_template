<script setup>
    import { Head, Link, router } from '@inertiajs/vue3';
    import Layout from '@/Pages/Layout.vue'
    import { reactive } from 'vue'
    import { base_url } from '@/base_url.js'

    const props = defineProps({
        user: Object,
        errors: Object
    })

    const form = reactive({
        name: props.user.name,
        email: props.user.email,
        password: '',
        new_password: '',
        new_password_confirmation: '',
    });

    function submit(delete_account) {

        if (delete_account) {

            if (confirm('Are you sure you want to permanently delete your account?\n\nThis cannot be undone.')) {
                router.put(base_url + 'delete_account', form) // inertia doesn't allow sending data with router.delete unfortunately
            }
            else return false
        }

        else {
            router.put(base_url + 'update_profile', form)
        }
    }
</script>

<template>
    <Head title="EDIT PROFILE | Laravel 10, Vue3, Inertia 1.0 template" />

    <Layout page="Edit Profile" :user="user">
            
        <form @submit.prevent="submit(false)">
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
                    New Password <span class="text-xs">(leave blank unless you are changing your password)</span>:
                    <br>
                    <input type="password" v-model="form.new_password" class="block w-full border-2 border-black rounded p-1 text-black">
                    <div v-if="errors.new_password" class="text-xs text-red-500">{{ errors.new_password }}</div>
                </div>

                <div class="mb-3" v-if="form.new_password.length > 0">
                    Confirm New Password:
                    <br>
                    <input type="password" v-model="form.new_password_confirmation" class="block w-full border-2 border-black rounded p-1 text-black">
                    <div v-if="errors.new_password_confirmation" class="text-xs text-red-500">{{ errors.new_password_confirmation }}</div>
                </div>

                <button class="border-2 border-black rounded p-1 mt-2 text-black text-base sm:text-2xl" type="submit">
                    Update Profile
                </button>

                <button type="button" class="border-2 border-red-500 rounded p-1 mt-2 ml-5 text-red-500 text-base sm:text-2xl" @click="submit(true)">
                    Delete Account
                </button>
            </div>
        </form>

    </Layout>
</template>
