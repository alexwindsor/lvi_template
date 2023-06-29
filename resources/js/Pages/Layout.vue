<script setup>
  import { Link } from '@inertiajs/vue3'
  import { base_url } from '@/base_url.js'

  defineProps({
      page: String,
      user: Object
  })
</script>

<template>

  <div class="m-8">

    <h1 class="text-center text-5xl">
      <Link :href="base_url">My Project</Link>
    </h1>

    <h2 class="text-3xl">{{ page }}</h2>

    <div class="text-sm m-3">
      <div v-if="user">
        <Link :href="base_url + 'edit_profile'">Edit Profile</Link> | <Link :href="base_url + 'logout'">Logout</Link>
      </div>

      <div v-else>
        <Link :href="base_url + 'login'">Login</Link> | <Link :href="base_url + 'register'">Register</Link>
      </div>
    </div>

    <div class="m-12 text-base">
      <slot />
    </div>

  </div>

</template>