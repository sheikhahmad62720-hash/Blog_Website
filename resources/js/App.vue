<script setup>
import NavBar from './components/layout/NavBar.vue'
import Footer from './components/layout/Footer.vue'
</script>

<template>
    <div class="flex min-h-screen flex-col bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-surface-dark dark:text-slate-100">
        <NavBar />
        <main class="flex-1">
            <RouterView v-slot="{ Component }">
                <transition
                    name="fade"
                    mode="out-in"
                >
                    <component :is="Component" />
                </transition>
            </RouterView>
        </main>
        <Footer />
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from {
    opacity: 0;
    transform: translateY(6px);
}
.fade-leave-to {
    opacity: 0;
}
</style>
