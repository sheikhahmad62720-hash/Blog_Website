<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import PostList from '../components/blog/PostList.vue'
import PostListSkeleton from '../components/blog/PostListSkeleton.vue'
import Pagination from '../components/ui/Pagination.vue'
import NewsletterForm from '../components/ui/NewsletterForm.vue'
import { usePostStore } from '../store/postStore'
import { formatDate } from '../utils'

const route = useRoute()
const router = useRouter()
const store = usePostStore()

const posts = ref([])
const page = ref(parseInt(route.query.page || '1', 10))
const lastPage = ref(1)
const categories = ref([])
const featured = ref(null)
const loading = ref(true)

const heroStats = [
    { value: '50+', label: 'Articles' },
    { value: '4', label: 'Categories' },
    { value: '5', label: 'Min avg read' },
    { value: '100%', label: 'Human written' },
]

const gradients = [
    'bg-gradient-to-br from-indigo-500 to-violet-600',
    'bg-gradient-to-br from-violet-500 to-fuchsia-600',
    'bg-gradient-to-br from-rose-500 to-orange-500',
    'bg-gradient-to-br from-emerald-500 to-teal-600',
]

const categoryIcons = [
    'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
    'M4.5 12.75l6 6 9-13.5',
    'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75',
    'M9 9h.01M15 9h.01M9 12h.01M15 12h.01M9 15h.01M15 15h.01M21 12c0 4.97-4.03 9-9 9-1.08 0-2.12-.19-3.08-.54L3 21l.54-5.92A8.98 8.98 0 013 12c0-4.97 4.03-9 9-9s9 4.03 9 9z',
]

onMounted(async () => {
    document.title = 'Blog'
    store.fetchCategories().then(() => (categories.value = store.categories))
    store.fetchFeatured().then(() => (featured.value = store.featured))
    await loadPosts()
})

async function loadPosts() {
    loading.value = true
    const { data } = await api.get('/posts', { params: { page: page.value, per_page: 9 } })
    posts.value = data.data
    lastPage.value = data.meta.last_page
    loading.value = false
}

function onPageChange(p) {
    page.value = p
    router.push({ query: { page: p } })
    loadPosts()
    window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
    <div>
        <!-- Hero intro -->
        <section class="relative overflow-hidden bg-slate-900 dark:bg-black">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600"></div>
            <div class="absolute -left-24 -top-24 h-96 w-96 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-fuchsia-400/20 blur-3xl"></div>
            <div class="relative mx-auto max-w-6xl px-4 py-20 text-center sm:px-6 lg:py-24">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-white ring-1 ring-white/20 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Fresh perspectives
                </span>
                <h1 class="mx-auto mt-6 max-w-3xl text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-6xl">
                    Ideas worth reading, written for curious minds.
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-indigo-100/90">
                    Deep dives on technology, thoughtful lifestyle essays, and honest takes on travel — published by people who care about the craft.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="#latest" class="rounded-full bg-white px-6 py-3 text-sm font-bold text-indigo-600 shadow-xl shadow-black/10 transition hover:bg-indigo-50 active:scale-95">
                        Read latest
                    </a>
                    <a href="#categories" class="rounded-full border border-white/30 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10 active:scale-95">
                        Browse categories
                    </a>
                </div>

                <div class="mx-auto mt-14 grid max-w-3xl grid-cols-2 gap-4 sm:grid-cols-4">
                    <div v-for="stat in heroStats" :key="stat.label" class="rounded-2xl bg-white/10 p-5 ring-1 ring-white/15 backdrop-blur">
                        <p class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ stat.value }}</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wider text-indigo-100/80">{{ stat.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured post -->
        <section v-if="featured" class="mx-auto max-w-6xl px-4 pt-12 sm:px-6">
            <div class="mb-6">
                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                    Featured
                </span>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Top story this week</h2>
            </div>
            <RouterLink
                :to="`/post/${featured.slug}`"
                class="group relative grid overflow-hidden rounded-3xl border border-slate-200/60 bg-white shadow-xl shadow-indigo-500/5 transition-shadow hover:shadow-2xl hover:shadow-indigo-500/15 lg:grid-cols-2 dark:border-slate-800/80 dark:bg-slate-900/50"
            >
                <div
                    class="pointer-events-none absolute -top-24 -left-24 h-72 w-72 rounded-full bg-gradient-to-br from-indigo-500/20 to-violet-500/20 blur-3xl"
                ></div>
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 lg:aspect-auto dark:bg-slate-800">
                    <img v-if="featured.featured_image" :src="featured.featured_image" :alt="featured.title" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105" loading="lazy" />
                    <div v-else class="flex h-full items-center justify-center bg-gradient-to-br from-indigo-50 to-violet-100 text-7xl font-black text-indigo-200 dark:from-slate-800 dark:to-slate-900 dark:text-slate-700">B</div>
                </div>
                <div class="relative flex flex-col justify-center p-8 lg:p-12">
                    <RouterLink
                        v-if="featured.category"
                        :to="`/category/${featured.category.slug}`"
                        class="w-fit rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 transition hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400"
                    >
                        {{ featured.category.name }}
                    </RouterLink>
                    <h3 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-slate-900 transition group-hover:text-indigo-600 dark:text-white lg:text-4xl dark:group-hover:text-indigo-400">
                        {{ featured.title }}
                    </h3>
                    <p class="mt-4 line-clamp-3 text-base leading-relaxed text-slate-500 dark:text-slate-400">{{ featured.excerpt }}</p>
                    <div class="mt-7 flex items-center gap-3 text-sm text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-2">
                            <img
                                v-if="featured.author?.avatar"
                                :src="featured.author.avatar"
                                class="h-8 w-8 rounded-full object-cover"
                                :alt="featured.author.name"
                            />
                            <span v-else class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-sm font-semibold text-indigo-600 dark:from-indigo-900 dark:to-violet-900 dark:text-indigo-300">
                                {{ featured.author?.name?.charAt(0) }}
                            </span>
                        </span>
                        <span class="font-medium text-slate-700 dark:text-slate-200">{{ featured.author?.name }}</span>
                        <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span>{{ formatDate(featured.published_at) }}</span>
                        <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                        <span>{{ featured.reading_time }} min read</span>
                    </div>
                </div>
            </RouterLink>
        </section>

        <!-- Latest posts -->
        <section id="latest" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-12 sm:px-6">
            <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                        New on the blog
                    </span>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Latest Posts</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Fresh articles, written for curious minds.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <RouterLink
                        v-for="cat in categories.slice(0, 4)"
                        :key="cat.slug"
                        :to="`/category/${cat.slug}`"
                        class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-500 transition hover:border-indigo-400 hover:text-indigo-600 dark:border-slate-700 dark:text-slate-300 dark:hover:text-indigo-400"
                    >
                        {{ cat.name }}
                    </RouterLink>
                </div>
            </div>

            <PostListSkeleton v-if="loading" :count="6" />
            <PostList v-else :posts="posts" />

            <Pagination :current="meta.current_page" :last="meta.last_page" @change="onPageChange" class="mt-10" />
        </section>

        <!-- Categories showcase -->
        <section id="categories" class="border-y border-slate-200/60 bg-white/60 py-14 dark:border-slate-800/60 dark:bg-slate-900/30">
            <div class="mx-auto max-w-6xl scroll-mt-20 px-4 sm:px-6">
                <div class="mb-8 max-w-2xl">
                    <span class="inline-flex rounded-full bg-violet-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-violet-600 dark:bg-violet-500/10 dark:text-violet-400">
                        Explore
                    </span>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Browse by category</h2>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Find your next favorite read across our curated topics.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <RouterLink
                        v-for="(cat, i) in categories"
                        :key="cat.slug"
                        :to="`/category/${cat.slug}`"
                        class="group rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-400/50 hover:shadow-xl hover:shadow-indigo-500/10 dark:border-slate-800/80 dark:bg-slate-900/50"
                    >
                        <span :class="`flex h-11 w-11 items-center justify-center rounded-xl ${gradients[i % gradients.length]} text-white shadow-md`">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="categoryIcons[i % categoryIcons.length]" />
                            </svg>
                        </span>
                        <h3 class="mt-4 font-bold text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400">{{ cat.name }}</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ cat.description || 'Thoughtful articles and fresh perspectives.' }}</p>
                        <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-indigo-600 transition group-hover:gap-2 dark:text-indigo-400">
                            Explore
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                    </RouterLink>
                </div>
            </div>
        </section>

        <!-- Newsletter CTA -->
        <section class="relative overflow-hidden bg-slate-900 dark:bg-black">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600"></div>
            <div class="absolute -left-20 top-0 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="relative mx-auto max-w-3xl px-4 py-16 text-center sm:px-6">
                <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Never miss a story</h2>
                <p class="mx-auto mt-3 max-w-xl text-indigo-100/90">
                    Join the newsletter and get the latest articles delivered straight to your inbox. No spam, ever.
                </p>
                <NewsletterForm light class="mx-auto mt-8 max-w-md" />
            </div>
        </section>
    </div>
</template>
