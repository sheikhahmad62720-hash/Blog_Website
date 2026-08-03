<script setup>
import { onMounted } from 'vue'

onMounted(() => (document.title = 'Not Found · Blog'))
</script>

<template>
    <div class="mx-auto max-w-md px-4 py-24 text-center sm:px-6">
        <p class="bg-gradient-to-r from-indigo-500 to-violet-600 bg-clip-text text-7xl font-black text-transparent">404</p>
        <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Page not found</h1>
        <p class="mt-2 text-slate-500 dark:text-slate-400">The page you're looking for doesn't exist or was moved.</p>
        <RouterLink to="/" class="mt-6 inline-block rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95">
            Back to home
        </RouterLink>
    </div>
</template>
