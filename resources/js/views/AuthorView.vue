<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import PostList from '../components/blog/PostList.vue'
import PostListSkeleton from '../components/blog/PostListSkeleton.vue'
import Pagination from '../components/ui/Pagination.vue'

const route = useRoute()
const author = ref(null)
const posts = ref([])
const meta = ref({ current_page: 1, last_page: 1, total: 0 })
const loading = ref(true)

onMounted(() => load())
watch(() => route.params.id, load)

async function load() {
    loading.value = true
    const { data } = await api.get(`/authors/${route.params.id}`, { params: { per_page: 9 } })
    author.value = data.author
    posts.value = data.posts.data
    meta.value = data.posts.meta
    loading.value = false
    document.title = `${data.author.name} · Blog`
}

function onPage(p) {
    api.get(`/authors/${route.params.id}`, { params: { page: p, per_page: 9 } }).then(({ data }) => {
        posts.value = data.posts.data
        meta.value = data.posts.meta
    })
    window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <div class="mb-10 flex flex-col gap-5 sm:flex-row sm:items-center">
            <img v-if="author?.avatar" :src="author.avatar" class="h-20 w-20 rounded-2xl object-cover ring-2 ring-slate-100 dark:ring-slate-800" :alt="author.name" />
            <span v-else class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-100 to-violet-100 text-3xl font-extrabold text-indigo-600 ring-2 ring-slate-100 dark:from-indigo-900 dark:to-violet-900 dark:text-indigo-300 dark:ring-slate-800">
                {{ author?.name?.charAt(0) }}
            </span>
            <div>
                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                    Author
                </span>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-4xl">{{ author?.name }}</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ meta.total }} published article(s)</p>
            </div>
        </div>

        <p v-if="author?.bio" class="mb-10 max-w-3xl leading-relaxed text-slate-600 dark:text-slate-300">{{ author.bio }}</p>

        <PostListSkeleton v-if="loading" :count="6" />
        <PostList v-else :posts="posts" />

        <Pagination v-if="meta.last_page > 1" :current="meta.current_page" :last="meta.last_page" @change="onPage" class="mt-10" />
    </div>
</template>
