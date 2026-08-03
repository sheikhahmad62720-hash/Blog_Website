<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'
import Pagination from '../../components/ui/Pagination.vue'

onMounted(() => {
    document.title = 'Posts · Blog'
    load()
})

const posts = ref([])
const meta = ref({ current_page: 1, last_page: 1 })
const filter = ref('all')

async function load() {
    const params = { page: meta.value.current_page }
    if (filter.value !== 'all') params.status = filter.value
    const { data } = await api.get('/admin/posts', { params })
    posts.value = data.data
    meta.value = data.meta
}

function onPage(p) {
    meta.value.current_page = p
    load()
    window.scrollTo({ top: 0, behavior: 'smooth' })
}

async function remove(post) {
    if (!confirm(`Delete "${post.title}"?`)) return
    await api.delete(`/posts/${post.id}`)
    load()
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
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">Posts</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage all your published content.</p>
            </div>
            <RouterLink to="/admin/posts/create" class="rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95">
                New Post
            </RouterLink>
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
            <button
                v-for="status in ['all', 'published', 'draft', 'scheduled']"
                :key="status"
                @click="filter = status; load()"
                :class="[
                    'rounded-full px-4 py-1.5 text-sm font-semibold capitalize transition active:scale-95',
                    filter === status
                        ? 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/25'
                        : 'bg-white text-slate-600 hover:bg-slate-100 dark:bg-slate-900/60 dark:text-slate-300 dark:hover:bg-slate-800',
                ]"
            >
                {{ status }}
            </button>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm dark:border-slate-800/80 dark:bg-slate-900/50">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200/70 bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-slate-800 dark:bg-slate-800/40 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Author</th>
                        <th class="px-4 py-3">Views</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr v-for="post in posts" :key="post.id" class="transition hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                        <td class="max-w-xs truncate px-4 py-4 font-semibold text-slate-800 dark:text-slate-200">
                            {{ post.title }}
                        </td>
                        <td class="px-4 py-4">
                            <span :class="statusClass(post.status)" class="rounded-full px-2 py-0.5 text-xs font-semibold capitalize">
                                {{ post.status }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-slate-500 dark:text-slate-400">{{ post.author?.name }}</td>
                        <td class="px-4 py-4 text-slate-500 dark:text-slate-400">{{ post.views }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <RouterLink :to="`/post/${post.slug}`" class="rounded-lg px-2.5 py-1 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-500/10">View</RouterLink>
                                <RouterLink :to="`/admin/posts/${post.slug}/edit`" class="rounded-lg px-2.5 py-1 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-500/10">Edit</RouterLink>
                                <button @click="remove(post)" class="rounded-lg px-2.5 py-1 text-sm font-semibold text-red-500 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination v-if="meta.last_page > 1" :current="meta.current_page" :last="meta.last_page" @change="onPage" class="mt-6" />
    </div>
</template>
