<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import PostList from '../components/blog/PostList.vue'
import PostListSkeleton from '../components/blog/PostListSkeleton.vue'
import Pagination from '../components/ui/Pagination.vue'

const route = useRoute()
const router = useRouter()
const category = ref(null)
const posts = ref([])
const meta = ref({ current_page: 1, last_page: 1 })
const loading = ref(true)

onMounted(() => load())
watch(() => route.params.slug, load)

async function load() {
    loading.value = true
    const [cats, postRes] = await Promise.all([
        api.get('/categories'),
        api.get('/posts', { params: { category: route.params.slug, per_page: 9 } }),
    ])
    category.value = cats.data.find((c) => c.slug === route.params.slug) || null
    posts.value = postRes.data.data
    meta.value = postRes.data.meta
    loading.value = false
    document.title = `${category.value?.name || 'Category'} · Blog`
}

function onPage(p) {
    api.get('/posts', { params: { category: route.params.slug, page: p, per_page: 9 } }).then(({ data }) => {
        posts.value = data.data
        meta.value = data.meta
    })
    window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <nav class="mb-6 flex items-center gap-2 text-sm text-slate-400">
            <RouterLink to="/" class="transition hover:text-indigo-600">Home</RouterLink>
            <span>/</span>
            <span class="text-slate-600 dark:text-slate-300">{{ category?.name || 'Category' }}</span>
        </nav>

        <header class="mb-10">
            <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                Category
            </span>
            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">{{ category?.name }}</h1>
            <p v-if="category?.description" class="mt-3 max-w-2xl text-slate-500 dark:text-slate-400">{{ category.description }}</p>
        </header>

        <PostListSkeleton v-if="loading" :count="6" />
        <PostList v-else :posts="posts" />

        <Pagination v-if="meta.last_page > 1" :current="meta.current_page" :last="meta.last_page" @change="onPage" class="mt-10" />
    </div>
</template>
