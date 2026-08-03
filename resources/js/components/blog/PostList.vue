<script setup>
import PostCard from './PostCard.vue'

defineProps({
    posts: { type: Array, required: true },
})
</script>

<template>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <PostCard v-for="post in posts" :key="post.id" :post="post" />
    </div>
</template>
