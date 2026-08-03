<script setup>
import { onMounted, ref } from 'vue'
import api from '../../services/api'
import NewsletterForm from '../ui/NewsletterForm.vue'

const categories = ref([])
onMounted(async () => {
    const { data } = await api.get('/categories')
    categories.value = data
})

const exploreLinks = [
    { to: '/', label: 'Home' },
    { to: '/about', label: 'About' },
    { to: '/contact', label: 'Contact' },
    { to: '/search', label: 'Search' },
]

const socials = [
    {
        name: 'Twitter',
        href: 'https://twitter.com',
        icon: 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
    },
    {
        name: 'LinkedIn',
        href: 'https://linkedin.com',
        icon: 'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z',
    },
    {
        name: 'Facebook',
        href: 'https://facebook.com',
        icon: 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
    },
]
</script>

<template>
    <footer class="relative border-t border-slate-200/60 bg-white/80 backdrop-blur dark:border-slate-800/60 dark:bg-slate-950/80">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-indigo-500/40 to-transparent"></div>
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6">
            <div class="grid gap-10 md:grid-cols-4">
                <div class="md:col-span-1">
                    <RouterLink to="/" class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-lg font-extrabold text-white shadow-lg shadow-indigo-500/25">
                            B
                        </span>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                            blog<span class="bg-gradient-to-r from-indigo-500 to-violet-600 bg-clip-text text-transparent">.</span>
                        </span>
                    </RouterLink>
                    <p class="mt-4 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                        Thoughtful articles on technology, lifestyle, and everything in between.
                    </p>
                    <div class="mt-5 flex gap-2">
                        <a v-for="social in socials" :key="social.name" :href="social.href" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-gradient-to-br hover:from-indigo-500 hover:to-violet-600 hover:text-white active:scale-95 dark:bg-slate-800 dark:text-slate-300" :aria-label="social.name">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path :d="social.icon" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-white">Explore</h3>
                    <ul class="mt-4 space-y-2.5">
                        <li v-for="link in exploreLinks" :key="link.to">
                            <RouterLink :to="link.to" class="text-sm text-slate-500 transition hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">
                                {{ link.label }}
                            </RouterLink>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-white">Categories</h3>
                    <ul class="mt-4 space-y-2.5">
                        <li v-for="cat in categories" :key="cat.slug">
                            <RouterLink :to="`/category/${cat.slug}`" class="text-sm text-slate-500 transition hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">
                                {{ cat.name }}
                            </RouterLink>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-900 dark:text-white">Newsletter</h3>
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                        Get the latest posts delivered to your inbox.
                    </p>
                    <NewsletterForm class="mt-3" />
                </div>
            </div>

            <div class="mt-12 border-t border-slate-200/60 pt-6 dark:border-slate-800/60">
                <p class="text-center text-xs text-slate-400">
                    © {{ new Date().getFullYear() }} Blog. Built with Laravel & Vue.
                </p>
            </div>
        </div>
    </footer>
</template>
