<script setup>
import PostCardSkeleton from './PostCardSkeleton.vue'

defineProps({
    count: { type: Number, default: 6 },
})
</script>

<template>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <PostCardSkeleton v-for="i in count" :key="i" />
    </div>
</template>
