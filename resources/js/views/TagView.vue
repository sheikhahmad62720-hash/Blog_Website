<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import PostList from '../components/blog/PostList.vue'
import PostListSkeleton from '../components/blog/PostListSkeleton.vue'
import Pagination from '../components/ui/Pagination.vue'

const route = useRoute()
const router = useRouter()
const tag = ref(null)
const posts = ref([])
const meta = ref({ current_page: 1, last_page: 1, total: 0 })
const loading = ref(true)

onMounted(() => load())
watch(() => route.params.slug, load)

async function load() {
    loading.value = true
    const [tags, postRes] = await Promise.all([
        api.get('/tags'),
        api.get('/posts', { params: { tag: route.params.slug, per_page: 9 } }),
    ])
    tag.value = tags.data.find((t) => t.slug === route.params.slug) || null
    posts.value = postRes.data.data
    meta.value = postRes.data.meta
    loading.value = false
    document.title = `#${tag.value?.name || ''} · Blog`
}

function onPage(p) {
    api.get('/posts', { params: { tag: route.params.slug, page: p, per_page: 9 } }).then(({ data }) => {
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
            <span class="text-slate-600 dark:text-slate-300">#{{ tag?.name }}</span>
        </nav>

        <header class="mb-10">
            <span class="inline-flex rounded-full bg-violet-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                Tag
            </span>
            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">#{{ tag?.name }}</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">{{ meta.total }} article(s)</p>
        </header>

        <PostListSkeleton v-if="loading" :count="6" />
        <PostList v-else :posts="posts" />

        <Pagination v-if="meta.last_page > 1" :current="meta.current_page" :last="meta.last_page" @change="onPage" class="mt-10" />
    </div>
</template>
