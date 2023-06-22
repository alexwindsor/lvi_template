<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Pages/Layout.vue'

defineProps({
    user: Object
})

</script>

<template>
    <Head title="Laravel10|Vue3|Inertia1.0 Template" />
    <Layout page="Home Page" :user="user">

        Welcome to my website

        <br><br>

        <div v-if="user">
            {{ user }}
        </div>

        <div v-if="! user">
            You are not logged in
        </div>
        

    </Layout>


</template>

