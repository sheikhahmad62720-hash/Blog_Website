<script setup>
import { onMounted, onBeforeUnmount, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import { debounce } from '../../utils'

const router = useRouter()
const query = ref('')
const results = ref([])

const search = debounce(async (q) => {
    if (!q || q.length < 2) {
        results.value = []
        return
    }
    const { data } = await api.get('/posts', { params: { search: q, per_page: 6 } })
    results.value = data.data
}, 300)

watch(query, search)

function onKeydown(e) {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault()
        const el = document.querySelector('input[type="search"]')
        el?.focus()
    }
}

onMounted(() => window.addEventListener('keydown', onKeydown))
onBeforeUnmount(() => window.removeEventListener('keydown', onKeydown))

function submit() {
    if (!query.value.trim()) return
    router.push({ name: 'search', query: { q: query.value } })
    results.value = []
}

function go(post) {
    router.push({ name: 'post', params: { slug: post.slug } })
    results.value = []
    query.value = ''
}
</script>

<template>
    <div class="relative">
        <form @submit.prevent="submit" class="relative">
            <svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                v-model="query"
                type="search"
                placeholder="Search articles..."
                class="w-full rounded-full border border-slate-200/80 bg-white py-2 pl-10 pr-16 text-sm text-slate-700 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
            />
            <button
                type="submit"
                class="pointer-events-none absolute right-1 top-1/2 hidden -translate-y-1/2 rounded-full border border-slate-200 bg-slate-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-500 lg:block"
            >
                Ctrl K
            </button>
        </form>

        <transition
            enter-active-class="transition-opacity duration-150"
            leave-active-class="transition-opacity duration-150"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="results.length"
                class="absolute left-0 right-0 top-full z-30 mt-2 max-h-96 overflow-auto rounded-2xl border border-slate-200/80 bg-white/95 p-1.5 shadow-xl shadow-slate-900/5 backdrop-blur-xl dark:border-slate-700 dark:bg-slate-900/95"
            >
                <button
                    v-for="post in results"
                    :key="post.id"
                    @click="go(post)"
                    class="block w-full rounded-xl px-4 py-3 text-left transition hover:bg-slate-50 dark:hover:bg-slate-800/60"
                >
                    <p class="text-sm font-medium text-slate-800 dark:text-slate-100">{{ post.title }}</p>
                    <p class="mt-0.5 text-xs text-slate-400">{{ post.reading_time }} min read</p>
                </button>
            </div>
        </transition>
    </div>
</template>
