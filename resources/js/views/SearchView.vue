<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import PostList from '../components/blog/PostList.vue'
import PostListSkeleton from '../components/blog/PostListSkeleton.vue'
import Pagination from '../components/ui/Pagination.vue'

const route = useRoute()
const router = useRouter()
const query = ref(route.query.q || '')
const posts = ref([])
const meta = ref({ current_page: 1, last_page: 1, total: 0 })
const loading = ref(false)

onMounted(() => load())
watch(() => route.query.q, (q) => {
    query.value = q || ''
    load()
})

async function load() {
    loading.value = true
    const { data } = await api.get('/posts', {
        params: { search: query.value, page: route.query.page || 1, per_page: 9 },
    })
    posts.value = data.data
    meta.value = data.meta
    loading.value = false
    document.title = `Search · Blog`
}

function onPage(p) {
    router.push({ query: { q: query.value, page: p } })
    load()
    window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <header class="mb-10">
            <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                Search
            </span>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">
                {{ query ? `Results for "${query}"` : 'Search' }}
            </h1>
            <p v-if="meta.total" class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ meta.total }} result(s) found</p>
        </header>

        <PostListSkeleton v-if="loading" :count="6" />
        <PostList v-else :posts="posts" />

        <p v-if="!loading && !posts.length" class="py-10 text-center text-slate-500 dark:text-slate-400">
            No posts match your search.
        </p>

        <Pagination v-if="meta.last_page > 1" :current="meta.current_page" :last="meta.last_page" @change="onPage" class="mt-10" />
    </div>
</template>
