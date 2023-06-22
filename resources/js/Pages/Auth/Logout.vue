<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Pages/Layout.vue'
import { baseUrl } from '@/base_url.js'

defineProps({
    user: Object
})

</script>

<template>
    <Head title="logout" />

<Layout page="Logout" :user="user">
    
<div class="text-red-500">Are you sure you want to log out ?</div>

<div class="m-auto w-full sm:w-2/3 lg:w-1/3 mt-16">
    <Link :href="baseUrl.base_url + 'logout'" method="post" as="button" class="my-4 mx-auto block w-full border-2 border-black rounded text-center p-2">LOGOUT</Link>
</div>

</Layout>
</template>
