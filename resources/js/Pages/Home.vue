<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import Layout from '@/Pages/Layout.vue'

    defineProps({
        user: Object
    })
</script>

<template>

    <Head title="HOME | Laravel 10, Vue3, Inertia 1.0 template" />

    <Layout page="Home Page" :user="user">

        Welcome to my website <span v-if="user" class="font-bold">{{ user.name }}.</span> <span v-else>. You are not logged in.</span>

    </Layout>

</template>