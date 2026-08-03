<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'
import PostContent from '../components/blog/PostContent.vue'
import TableOfContents from '../components/blog/TableOfContents.vue'
import RelatedPosts from '../components/blog/RelatedPosts.vue'
import CommentSection from '../components/comments/CommentSection.vue'
import ShareButtons from '../components/blog/ShareButtons.vue'
import { formatDate } from '../utils'

const route = useRoute()
const post = ref(null)
const related = ref([])
const loading = ref(true)
const liked = ref(false)
const likesCount = ref(0)
const baseUrl = window.location.origin

const postSlug = computed(() => route.params.slug)

onMounted(async () => {
    await load()
    if (post.value) {
        document.title = `${post.value.title} · Blog`
        updateMeta()
    }
})

watch(postSlug, () => {
    loading.value = true
    load()
})

async function load() {
    loading.value = true
    try {
        const { data } = await api.get(`/posts/${postSlug.value}`)
        post.value = data.data
        liked.value = data.data.liked
        likesCount.value = data.data.likes_count
        const rel = await api.get(`/posts/${postSlug.value}/related`)
        related.value = rel.data.data
    } catch (e) {
        post.value = null
    } finally {
        loading.value = false
    }
}

async function toggleLike() {
    const { data } = await api.post(`/posts/${postSlug.value}/like`)
    liked.value = data.liked
    likesCount.value = data.likes_count
}

function updateMeta() {
    const meta = document.head.querySelector('meta[data-og-title]')
    meta?.setAttribute('content', post.value.title)
}
</script>

<template>
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
        <template v-if="post">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex items-center gap-2 text-sm text-slate-400 dark:text-slate-500">
                <RouterLink to="/" class="transition hover:text-indigo-600">Home</RouterLink>
                <span>/</span>
                <RouterLink v-if="post.category" :to="`/category/${post.category.slug}`" class="transition hover:text-indigo-600">
                    {{ post.category.name }}
                </RouterLink>
                <span>/</span>
                <span class="truncate text-slate-600 dark:text-slate-300">{{ post.title }}</span>
            </nav>

            <!-- Header -->
            <header class="max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <RouterLink
                        v-if="post.category"
                        :to="`/category/${post.category.slug}`"
                        class="rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 transition dark:bg-indigo-500/10 dark:text-indigo-400"
                    >
                        {{ post.category.name }}
                    </RouterLink>
                    <RouterLink
                        v-for="tag in post.tags"
                        :key="tag.slug"
                        :to="`/tag/${tag.slug}`"
                        class="rounded-full border border-slate-200 px-3 py-1 text-xs font-medium text-slate-500 transition hover:border-indigo-400 hover:text-indigo-600 dark:border-slate-700 dark:text-slate-400"
                    >
                        #{{ tag.name }}
                    </RouterLink>
                </div>

                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight text-slate-900 dark:text-white sm:text-5xl">
                    {{ post.title }}
                </h1>

                <div class="mt-7 flex flex-wrap items-center justify-between gap-4 border-y border-slate-200/70 py-5 dark:border-slate-800">
                    <RouterLink :to="`/author/${post.author?.id}`" class="group flex items-center gap-3">
                        <img v-if="post.author?.avatar" :src="post.author.avatar" class="h-11 w-11 rounded-full object-cover ring-2 ring-white dark:ring-slate-800" :alt="post.author.name" />
                        <span v-else class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-base font-bold text-indigo-600 ring-2 ring-white dark:from-indigo-900 dark:to-violet-900 dark:text-indigo-300 dark:ring-slate-800">
                            {{ post.author?.name?.charAt(0) }}
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-slate-900 group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400">{{ post.author?.name }}</p>
                            <p class="text-xs text-slate-400">{{ formatDate(post.published_at) }} · {{ post.reading_time }} min read</p>
                        </div>
                    </RouterLink>

                    <div class="flex items-center gap-4">
                        <button @click="toggleLike" class="flex items-center gap-1.5 rounded-full px-3 py-1.5 text-sm font-medium transition active:scale-95" :class="liked ? 'bg-red-50 text-red-500 dark:bg-red-500/10 dark:text-red-400' : 'text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800'">
                            <svg class="h-5 w-5" :fill="liked ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ likesCount }}
                        </button>
                        <ShareButtons :title="post.title" :url="`${baseUrl}/post/${post.slug}`" />
                    </div>
                </div>
            </header>

            <!-- Hero image -->
            <img
                v-if="post.featured_image"
                :src="post.featured_image"
                :alt="post.title"
                class="mt-8 max-h-[480px] w-full rounded-2xl object-cover shadow-xl shadow-slate-900/5"
                loading="lazy"
            />

            <!-- Content + TOC -->
            <div class="mt-12">
                <div class="mb-8 lg:hidden">
                    <TableOfContents :content="post.content" />
                </div>
                <div class="grid gap-10 lg:grid-cols-[280px_1fr]">
                    <div class="hidden lg:block">
                        <div class="sticky top-24">
                            <TableOfContents :content="post.content" />
                        </div>
                    </div>
                <div>
                    <PostContent :content="post.content" />

                    <div class="mt-8 flex items-center gap-2 text-sm text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        {{ post.views }} views
                    </div>
                </div>
                </div>
            </div>

            <div id="comments" class="mt-14">
                <CommentSection :post-slug="post.slug" />
            </div>

            <RelatedPosts :posts="related" />
        </template>

        <div v-else-if="loading" class="mx-auto max-w-4xl space-y-8">
            <div class="space-y-3">
                <div class="h-5 w-24 animate-pulse rounded-full bg-slate-200/80 dark:bg-slate-800/80"></div>
                <div class="h-10 w-3/4 animate-pulse rounded-xl bg-slate-200/80 dark:bg-slate-800/80"></div>
                <div class="h-10 w-1/2 animate-pulse rounded-xl bg-slate-200/80 dark:bg-slate-800/80"></div>
            </div>
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 animate-pulse rounded-full bg-slate-200/80 dark:bg-slate-800/80"></div>
                <div class="space-y-2">
                    <div class="h-3 w-32 animate-pulse rounded-full bg-slate-200/70 dark:bg-slate-800/70"></div>
                    <div class="h-3 w-20 animate-pulse rounded-full bg-slate-200/70 dark:bg-slate-800/70"></div>
                </div>
            </div>
            <div class="aspect-[16/7] animate-pulse rounded-2xl bg-slate-200/80 dark:bg-slate-800/80"></div>
            <div class="space-y-3">
                <div v-for="i in 5" :key="i" class="h-4 animate-pulse rounded-full" :class="i % 2 ? 'w-full bg-slate-200/70 dark:bg-slate-800/70' : 'w-2/3 bg-slate-200/60 dark:bg-slate-800/60'"></div>
            </div>
        </div>
    </div>
</template>
