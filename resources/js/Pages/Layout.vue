<script setup>
import { Link } from '@inertiajs/vue3'
import { baseUrl } from '@/base_url.js'

defineProps({
    page: String,
    user: Object
})

</script>

<template>

<h1 class="text-5xl">{{ page }}</h1>

<br><br>

<div v-if="user">
  <Link :href="baseUrl.base_url + 'edit_profile'">Edit Profile</Link> | <Link :href="baseUrl.base_url + 'logout'">Logout</Link>
</div>

<div v-if="! user">
  <Link :href="baseUrl.base_url + 'login'">Login</Link> | <Link :href="baseUrl.base_url + 'register'">Register</Link>
</div>

<br><br>

<slot />


</template>