<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../../store/authStore'
import SearchBar from '../ui/SearchBar.vue'
import ThemeToggle from '../ui/ThemeToggle.vue'

const auth = useAuthStore()
auth.fetchMe().catch(() => {})

const mobileOpen = ref(false)
const navLinks = [
    { to: '/', label: 'Home' },
    { to: '/category/technology', label: 'Technology' },
    { to: '/category/lifestyle', label: 'Lifestyle' },
    { to: '/category/travel', label: 'Travel' },
    { to: '/about', label: 'About' },
    { to: '/contact', label: 'Contact' },
]
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-slate-200/60 bg-white/70 backdrop-blur-xl transition-all duration-300 dark:border-slate-800/60 dark:bg-slate-950/70">
        <nav class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <RouterLink to="/" class="group flex items-center gap-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-lg font-extrabold text-white shadow-lg shadow-indigo-500/25">
                    B
                </span>
                <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    blog<span class="bg-gradient-to-r from-indigo-500 to-violet-600 bg-clip-text text-transparent">.</span>
                </span>
            </RouterLink>

            <div class="hidden items-center gap-7 md:flex">
                <RouterLink
                    v-for="link in navLinks"
                    :key="link.to"
                    :to="link.to"
                    class="text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white"
                >
                    {{ link.label }}
                </RouterLink>
            </div>

            <div class="flex items-center gap-3">
                <SearchBar class="hidden w-64 sm:block" />
                <ThemeToggle />
                <div v-if="auth.user" class="flex items-center gap-2">
                    <RouterLink
                        v-if="auth.isAdmin"
                        to="/admin"
                        class="hidden text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white sm:block"
                    >
                        Admin
                    </RouterLink>
                    <img
                        v-if="auth.user.avatar"
                        :src="auth.user.avatar"
                        class="h-8 w-8 rounded-full object-cover ring-2 ring-white dark:ring-slate-800"
                        alt="Avatar"
                    />
                    <span v-else class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-sm font-semibold text-indigo-600 ring-2 ring-white dark:from-indigo-900 dark:to-violet-900 dark:text-indigo-300 dark:ring-slate-800">
                        {{ auth.user.name?.charAt(0) }}
                    </span>
                </div>
                <div v-else class="hidden items-center gap-2 sm:flex">
                    <RouterLink to="/login" class="rounded-full px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">
                        Log in
                    </RouterLink>
                    <RouterLink to="/register" class="rounded-full bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-1.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:shadow-indigo-500/30 hover:brightness-110 active:scale-95">
                        Get started
                    </RouterLink>
                </div>

                <button
                    @click="mobileOpen = !mobileOpen"
                    class="rounded-lg p-2 text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800 md:hidden"
                    aria-label="Menu"
                >
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </nav>

        <transition
            enter-active-class="transition-all duration-200 ease-out"
            leave-active-class="transition-all duration-150 ease-in"
            enter-from-class="opacity-0 -translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="mobileOpen" class="border-t border-slate-200/60 bg-white/95 px-4 py-3 backdrop-blur-xl md:hidden dark:border-slate-800/60 dark:bg-slate-950/95">
                <div class="flex flex-col gap-1">
                    <RouterLink
                        v-for="link in navLinks"
                        :key="link.to"
                        :to="link.to"
                        @click="mobileOpen = false"
                        class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800"
                    >
                        {{ link.label }}
                    </RouterLink>
                </div>
            </div>
        </transition>
    </header>
</template>
