<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

const menu = [
    { to: '/admin', label: 'Dashboard', icon: 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z' },
    { to: '/admin/posts', label: 'Posts', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' },
    { to: '/admin/posts/create', label: 'New Post', icon: 'M12 4.5v15m7.5-7.5h-15' },
]

function isActive(to) {
    if (to === '/admin') return route.path === '/admin'
    return route.path.startsWith(to)
}
</script>

<template>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:py-10">
        <div class="grid gap-8 lg:grid-cols-[240px_1fr]">
            <aside>
                <div class="sticky top-24 flex gap-1 overflow-x-auto rounded-2xl border border-slate-200/60 bg-white/70 p-2 backdrop-blur lg:flex-col dark:border-slate-800/80 dark:bg-slate-900/50">
                    <RouterLink
                        v-for="item in menu"
                        :key="item.to"
                        :to="item.to"
                        :class="[
                            'flex shrink-0 items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition active:scale-95',
                            isActive(item.to)
                                ? 'bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400'
                                : 'text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white',
                        ]"
                    >
                        <svg v-if="item.icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path :d="item.icon" />
                        </svg>
                        {{ item.label }}
                    </RouterLink>

                    <div class="my-2 hidden border-t border-slate-200/70 lg:block dark:border-slate-800"></div>

                    <RouterLink
                        to="/"
                        class="flex shrink-0 items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        View site
                    </RouterLink>
                </div>
            </aside>

            <main>
                <RouterView />
            </main>
        </div>
    </div>
</template>
