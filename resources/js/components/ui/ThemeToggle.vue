<script setup>
import { useTheme } from '../../composables/useTheme'

const { theme, toggle } = useTheme()
</script>

<template>
    <button
        @click="toggle"
        :aria-label="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
        class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white active:scale-90"
    >
        <transition name="theme-icon" mode="out-in">
            <svg v-if="theme === 'dark'" key="sun" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else key="moon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </transition>
    </button>
</template>

<style scoped>
.theme-icon-enter-active {
    transition: transform 0.3s ease, opacity 0.3s ease;
}
.theme-icon-leave-active {
    transition: transform 0.3s ease, opacity 0.3s ease;
}
.theme-icon-enter-from {
    transform: rotate(-120deg) scale(0.6);
    opacity: 0;
}
.theme-icon-leave-to {
    transform: rotate(120deg) scale(0.6);
    opacity: 0;
}
</style>
