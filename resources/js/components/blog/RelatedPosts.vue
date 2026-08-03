<script setup>
import PostCard from './PostCard.vue'

defineProps({
    posts: { type: Array, required: true },
})
</script>

<template>
    <section v-if="posts.length" class="mt-16">
        <div class="mb-6">
            <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                Keep reading
            </span>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Related Posts</h2>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <PostCard v-for="post in posts" :key="post.id" :post="post" />
        </div>
    </section>
</template>
