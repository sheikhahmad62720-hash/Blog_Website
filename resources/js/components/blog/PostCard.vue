<script setup>
import { formatDate } from '../../utils'

defineProps({
    post: { type: Object, required: true },
})
</script>

<template>
    <article class="group overflow-hidden rounded-2xl border border-slate-200/60 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-indigo-500/40 hover:shadow-xl hover:shadow-indigo-500/10 dark:border-slate-800/80 dark:bg-slate-900/50">
        <RouterLink :to="`/post/${post.slug}`" class="block">
            <div class="relative aspect-[16/9] overflow-hidden bg-slate-100 dark:bg-slate-800">
                <img
                    v-if="post.featured_image"
                    :src="post.featured_image"
                    :alt="post.title"
                    loading="lazy"
                    class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                />
                <div v-else class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 to-violet-100 dark:from-slate-800 dark:to-slate-900">
                    <svg class="h-10 w-10 text-indigo-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <span
                    v-if="post.category"
                    class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wider text-slate-700 shadow-sm backdrop-blur dark:bg-slate-900/80 dark:text-slate-200"
                >
                    {{ post.category.name }}
                </span>
            </div>
        </RouterLink>

        <div class="flex flex-col p-5">
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span>{{ formatDate(post.published_at) }}</span>
                <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-600"></span>
                <span>{{ post.reading_time }} min read</span>
            </div>

            <RouterLink :to="`/post/${post.slug}`" class="mt-2.5 block">
                <h2 class="text-lg font-bold leading-snug tracking-tight text-slate-900 transition group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400">
                    {{ post.title }}
                </h2>
            </RouterLink>

            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                {{ post.excerpt }}
            </p>

            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4 dark:border-slate-800/80">
                <div class="flex items-center gap-2.5">
                    <img
                        v-if="post.author?.avatar"
                        :src="post.author.avatar"
                        class="h-7 w-7 rounded-full object-cover ring-2 ring-slate-50 dark:ring-slate-800"
                        :alt="post.author.name"
                    />
                    <span v-else class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-xs font-semibold text-indigo-600 ring-2 ring-slate-50 dark:from-indigo-900 dark:to-violet-900 dark:text-indigo-300 dark:ring-slate-800">
                        {{ post.author?.name?.charAt(0) }}
                    </span>
                    <span class="text-xs font-medium text-slate-500 dark:text-slate-300">{{ post.author?.name }}</span>
                </div>
                <span class="flex items-center gap-1 text-xs text-slate-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ post.views }}
                </span>
            </div>
        </div>
    </article>
</template>
