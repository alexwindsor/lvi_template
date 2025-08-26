<script setup>
import { Head, router } from '@inertiajs/vue3';
import Layout from '@/Pages/Layout.vue'
import { reactive, ref } from 'vue'
import { base_url } from '@/base_url.js'

const props = defineProps({
    errors: {
        type: Object,
        required: false
    },
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    }
})

const form = reactive({
    new_password: null,
    new_password_confirmation: null
});

function submit() {


router.post(base_url + 'reset-password', {
    email: props.email,
    token: props.token,
    new_password: form.new_password,
    new_password_confirmation: form.new_password_confirmation
})



}

</script>

<template>

    <Head title="LOGIN | Laravel 10, Vue3, Inertia 1.0 template" />
    <Layout page="Reset Password">

        <form @submit.prevent="submit">
            <div class="m-auto w-full sm:w-2/3 lg:w-1/3 mt-16">
                <div class="mb-3">
                    New Password:
                    <br>
                    <input
                        type="password"
                        v-model="form.new_password"
                        class="block w-full border-2 border-black rounded p-1 text-black"
                        maxlength="32"
                        minlength="8"
                        required
                    >
                    <div v-if="errors.new_password" class="text-xs text-red-500">
                        {{ errors.new_password }}
                    </div>
                </div>

                <div class="mb-3">
                    Confirm New Password:
                    <br>
                    <input
                        type="password"
                        v-model="form.new_password_confirmation"
                        class="block w-full border-2 border-black rounded p-1 text-black"
                        maxlength="32"
                        minlength="8"
                        required
                    >
                    <div v-if="errors.new_password_confirmation" class="text-xs text-red-500">
                        {{ errors.new_password_confirmation }}
                    </div>
                </div>

                <button
                    class="border-2 border-black rounded py-1 px-5 mt-2 text-black text-2xl"
                    type="submit"
                >
                    Reset Password
                </button>
            </div>
        </form>

    </Layout>

</template>
