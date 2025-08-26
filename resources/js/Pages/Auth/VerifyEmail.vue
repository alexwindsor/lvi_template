<script setup>
import { Head, router } from '@inertiajs/vue3'
import Layout from '@/Pages/Layout.vue'
import { ref } from 'vue'

defineProps({
    user: Object
})

const new_email_sent = ref(false)

function resendEmail() {

    if (new_email_sent.value) return;

    console.log('sadfsf')

    router.post('/email/verification-notification')

    new_email_sent.value = true

    setTimeout(() => {
        new_email_sent.value = false
    }, 2000)
}

</script>

<template>

    <Layout :user="user" page="Verify Email">

        <p class="mb-4">
            An email has been sent to the email address you used to register with. Please click on the verification link in that email in order to complete registration.
        </p>

        <p class="mb-4">
            Can't find the email? Check your spam folder or
            <span
                class="underline cursor-pointer"
                :class="{'text-gray-300': new_email_sent}"
                @click="resendEmail()"
            >
                resend email verification
            </span>.
        </p>

        <div v-if="new_email_sent">
            <i class="ml-4 text-lg">New verification email sent</i>
        </div>


    </Layout>
    <Head title="VERIFY EMAIL | Laravel 10, Vue3, Inertia 1.0 template" />

</template>
