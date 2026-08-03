<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import PostListSkeleton from '../components/blog/PostListSkeleton.vue'
import Pagination from '../components/ui/Pagination.vue'
import { formatDate } from '../utils'

const route = useRoute()
const router = useRouter()

const posts = ref([])
const loading = ref(true)
const meta = ref({ current_page: 1, last_page: 1, per_page: 10 })

onMounted(() => {
    document.title = 'Latest Articles · Blog'
    load()
})

watch(() => route.query.page, () => load())

async function load() {
    loading.value = true
    const page = parseInt(route.query.page || '1', 10)
    const { data } = await api.get('/posts', { params: { page, per_page: 10 } })
    posts.value = data.data
    meta.value = data.meta
    loading.value = false
}

function onPage(p) {
    router.push({ query: { page: p } })
    window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
    <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6">
        <span class="inline-flex rounded-full bg-gradient-to-r from-indigo-50 to-violet-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:from-indigo-500/10 dark:to-violet-500/10 dark:text-indigo-400">
            Latest
        </span>
        <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
            All articles,<br />
            <span class="bg-gradient-to-r from-indigo-500 to-violet-600 bg-clip-text text-transparent">newest first.</span>
        </h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-500 dark:text-slate-400">
            Every story we've published so far — fresh perspectives on technology, lifestyle, travel, and business.
        </p>

        <div class="mt-10 space-y-3">
            <PostListSkeleton v-if="loading" :count="8" />
            <template v-else>
                <article
                    v-for="(post, i) in posts"
                    :key="post.id"
                    class="group relative rounded-2xl border border-slate-200/60 bg-white p-5 transition-all duration-300 hover:-translate-y-0.5 hover:border-indigo-400/50 hover:shadow-lg hover:shadow-indigo-500/10 sm:p-6 dark:border-slate-800/80 dark:bg-slate-900/50"
                >
                    <RouterLink :to="`/post/${post.slug}`" class="flex items-start gap-5">
                        <span class="hidden select-none text-4xl font-black leading-none text-slate-100 transition-colors group-hover:text-indigo-100 sm:block dark:text-slate-800 dark:group-hover:text-indigo-500/20">
                            {{ String((meta.current_page - 1) * meta.per_page + i + 1).padStart(2, '0') }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <RouterLink
                                    v-if="post.category"
                                    :to="`/category/${post.category.slug}`"
                                    class="rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 transition hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400"
                                >
                                    {{ post.category.name }}
                                </RouterLink>
                                <span class="text-xs text-slate-400">
                                    {{ formatDate(post.published_at) }} · {{ post.reading_time }} min read
                                </span>
                            </div>
                            <h2 class="mt-2 text-xl font-bold leading-snug text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400">
                                {{ post.title }}
                            </h2>
                            <p class="mt-2 line-clamp-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                                {{ post.excerpt }}
                            </p>
                            <div class="mt-4 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                <span v-if="post.author?.avatar" class="flex h-6 w-6 items-center justify-center overflow-hidden rounded-full">
                                    <img :src="post.author.avatar" class="h-full w-full object-cover" :alt="post.author.name" />
                                </span>
                                <span v-else class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-xs font-bold text-indigo-600 dark:from-indigo-900 dark:to-violet-900 dark:text-indigo-300">
                                    {{ post.author?.name?.charAt(0) }}
                                </span>
                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ post.author?.name }}</span>
                            </div>
                        </div>
                        <svg class="mt-3 hidden h-5 w-5 shrink-0 text-slate-300 transition-all group-hover:translate-x-1 group-hover:text-indigo-500 sm:block dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </RouterLink>
                </article>

                <div v-if="!posts.length" class="rounded-2xl border border-dashed border-slate-300 p-16 text-center dark:border-slate-700">
                    <p class="text-slate-500 dark:text-slate-400">No articles published yet. Check back soon!</p>
                </div>
            </template>
        </div>

        <Pagination :current="meta.current_page" :last="meta.last_page" @change="onPage" class="mt-10" />
    </div>
</template>
