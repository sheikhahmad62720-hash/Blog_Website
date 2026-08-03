<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'

onMounted(async () => {
    document.title = 'Dashboard · Blog'
    await load()
})

const stats = ref([
    { label: 'Total Posts', value: 0 },
    { label: 'Published', value: 0 },
    { label: 'Drafts', value: 0 },
    { label: 'Scheduled', value: 0 },
])
const recent = ref([])

async function load() {
    const { data } = await api.get('/admin/posts', { params: { per_page: 100 } })
    const posts = data.data
    recent.value = posts.slice(0, 6)
    stats.value = [
        { label: 'Total Posts', value: data.meta.total },
        { label: 'Published', value: posts.filter((p) => p.status === 'published').length },
        { label: 'Drafts', value: posts.filter((p) => p.status === 'draft').length },
        { label: 'Scheduled', value: posts.filter((p) => p.status === 'scheduled').length },
    ]
}

async function remove(post) {
    if (!confirm(`Delete "${post.title}"?`)) return
    await api.delete(`/posts/${post.id}`)
    await load()
}

function statusClass(status) {
    return {
        published: 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
        draft: 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
        scheduled: 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
    }[status]
}
</script>

<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Overview of your blog's activity.</p>
            </div>
            <RouterLink to="/admin/posts/create" class="rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95">
                New Post
            </RouterLink>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="stat in stats" :key="stat.label" class="rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-slate-800/80 dark:bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-500 dark:bg-indigo-500/10 dark:text-indigo-400">
                        <span class="h-2.5 w-2.5 rounded-full bg-gradient-to-r from-indigo-500 to-violet-500"></span>
                    </span>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ stat.label }}</p>
                </div>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ stat.value }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900/50">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">Recent Posts</h2>
                <RouterLink to="/admin/posts" class="text-sm font-semibold text-indigo-600 transition hover:underline">View all</RouterLink>
            </div>
            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                <li v-for="post in recent" :key="post.id" class="flex items-center justify-between gap-4 py-3.5">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-200">{{ post.title }}</p>
                        <div class="mt-0.5 flex items-center gap-2">
                            <span :class="statusClass(post.status)" class="rounded-full px-2 py-0.5 text-[11px] font-semibold capitalize">
                                {{ post.status }}
                            </span>
                            <span class="text-xs text-slate-400">{{ post.author?.name }}</span>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-3">
                        <RouterLink :to="`/admin/posts/${post.slug}/edit`" class="text-sm font-semibold text-indigo-600 transition hover:underline">
                            Edit
                        </RouterLink>
                        <button
                            @click="remove(post)"
                            class="rounded-lg px-3 py-1.5 text-sm font-semibold text-red-500 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10"
                        >
                            Delete
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
