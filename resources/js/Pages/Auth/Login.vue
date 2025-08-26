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
        forgot_password: {
            type: Boolean,
            default: false
        },
        reset_link_sent: {
            type: Boolean,
            default: false
        },
        email: {
            type: String,
            default: null
        }
    })

    const password_reset_no_email_error = ref(null)

    const form = reactive({
        username_email: props.email,
        password: null,
        remember: false,
        forgot_password: props.forgot_password
    });

    function submit() {

        let username = null, email = null
        // check if they've given a username and an email
        if (form.username_email?.includes('@')) email = form.username_email
        else username = form.username_email

        if (form.forgot_password && ! email) {
            password_reset_no_email_error.value = 'Please enter your email address so that we can send you a link to reset your password'
            return
        }

        else if (form.forgot_password) {
            router.post(base_url + 'forgot-password', {
                email: email
            })
        }

        else {
            router.post(base_url + 'login', {
                username: username,
                email: email,
                password: form.password,
                remember: form.remember
            })
        }
    }

    function forgotPassword() {
        password_reset_no_email_error.value = null
        form.remember = false
        form.password = ''
    }
</script>

<template>

    <Head title="LOGIN | Laravel 10, Vue3, Inertia 1.0 template" />
    <Layout page="Login">

        <div v-if="reset_link_sent">
            We emailed you a link to reset your password - check your spam folder if you don't see it.
        </div>

        <div v-else>

            <div v-if="errors.authentication" class="text-red-500">
                {{ errors.authentication }}
            </div>

            <div v-if="forgot_password" class="text-red-500">
                Your password reset token was invalid or has expired - please try again
            </div>

            <form @submit.prevent="submit">
                <div class="m-auto w-full sm:w-2/3 lg:w-1/3 mt-16">
                    <div class="mb-3">
                        {{ form.forgot_password ? '' : 'Username or ' }}Email:
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
                        <div v-if="password_reset_no_email_error" class="text-xs text-red-500">{{ password_reset_no_email_error }}</div>
                    </div>

                    <div
                        class="mb-3"
                        :class="{'text-gray-500': form.forgot_password}"
                    >
                        Password:
                        <br>
                        <input
                            type="password"
                            v-model="form.password"
                            class="block w-full border-2 rounded p-1 text-black"
                            :class="{
                            'bg-white border-black': ! form.forgot_password,
                            'bg-gray-300 border-gray-500': form.forgot_password,
                        }"
                            maxlength="32"
                            minlength="8"
                            :disabled="form.forgot_password"
                            required
                        >
                        <div v-if="errors.password" maxlength="32" class="text-xs text-red-500">{{ errors.password }}</div>
                    </div>

                    <div class="mb-3">
                        <label
                            class="block mb-3"
                            :class="{'text-gray-500': form.forgot_password}"
                        >
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="mr-3 border border-black"
                                :disabled="form.forgot_password"
                            /> Remember me on this computer ?
                        </label>

                        <label class="block">
                            <input
                                type="checkbox"
                                v-model="form.forgot_password"
                                class="mr-3 border border-black"
                                @click="forgotPassword"
                            /> Forgot password - email a password reset link ?
                        </label>

                    </div>

                    <button
                        class="border-2 border-black rounded py-1 px-5 mt-2 text-black text-2xl"
                        type="submit"
                    >
                        {{ form.forgot_password ? 'Email Password Reset Link' : 'Login' }}
                    </button>
                </div>
            </form>
        </div>

    </Layout>

</template>
