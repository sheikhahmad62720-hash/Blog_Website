<script setup>
import { onMounted } from 'vue'

onMounted(() => (document.title = 'About · Blog'))

const stats = [
    { label: 'Articles published', value: '40+' },
    { label: 'Topics covered', value: '4' },
    { label: 'Minutes of reading', value: '25k+' },
    { label: 'Free forever', value: '100%' },
]

const topics = [
    {
        title: 'Technology',
        description: 'Hands-on guides on Laravel, Vue, and modern web development — plus honest reviews of the tools that shape how we build.',
        icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    },
    {
        title: 'Lifestyle',
        description: 'Practical essays on wellness, habits, and everyday living — ideas you can actually use, not just admire.',
        icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
    },
    {
        title: 'Travel',
        description: 'Field guides, itineraries, and stories from the road — written by people who actually went there.',
        icon: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
    {
        title: 'Business',
        description: 'Clear thinking on startups, growth, and productivity — the small decisions that compound into real results.',
        icon: 'M3 21h18M5 21V7l7-4 7 4v14M9 9h1m-1 4h1m4-4h1m-1 4h1M9 17h6',
    },
]

const values = [
    {
        title: 'Written by humans',
        description: 'No filler, no autoplay, no clickbait. Every article is drafted, edited, and fact-checked by a real person who cares about the subject.',
    },
    {
        title: 'Clarity over noise',
        description: 'Short sentences, honest opinions, and a structure you can scan. If a paragraph does not earn its place, it does not make it in.',
    },
    {
        title: 'Respect for your attention',
        description: 'We publish when we have something worth saying. You will never see a countdown timer or a popup begging you to subscribe.',
    },
    {
        title: 'Privately yours',
        description: 'No invasive trackers, no selling your data. Reading here stays between you and the page.',
    },
]
</script>

<template>
    <div class="mx-auto max-w-4xl px-4 py-14 sm:px-6">
        <span class="inline-flex rounded-full bg-gradient-to-r from-indigo-50 to-violet-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:from-indigo-500/10 dark:to-violet-500/10 dark:text-indigo-400">
            About
        </span>
        <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">
            Stories that inform,<br />
            <span class="bg-gradient-to-r from-indigo-500 to-violet-600 bg-clip-text text-transparent">ideas that inspire.</span>
        </h1>
        <p class="mt-4 max-w-2xl text-lg text-slate-500 dark:text-slate-400">
            blog is a home for thoughtful writing on technology, lifestyle, travel, and the business of everyday life.
            We believe good ideas deserve to be shared clearly — and read without clutter.
        </p>

        <div class="mt-8 flex flex-wrap gap-3">
            <RouterLink to="/contact" class="rounded-full bg-gradient-to-r from-indigo-500 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95">
                Say hello
            </RouterLink>
            <RouterLink to="/latest" class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                Read the latest
            </RouterLink>
        </div>

        <div class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="stat in stats" :key="stat.label" class="rounded-2xl border border-slate-200/60 bg-white p-6 text-center shadow-sm transition hover:shadow-md dark:border-slate-800/80 dark:bg-slate-900/50">
                <p class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">{{ stat.value }}</p>
                <p class="mt-1 text-sm font-medium text-slate-500 dark:text-slate-400">{{ stat.label }}</p>
            </div>
        </div>

        <section class="mt-16">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Our mission</h2>
            <p class="mt-4 text-slate-600 dark:text-slate-300">
                The internet is full of noise — recycled headlines, autoplay videos, and content written to fill a quota.
                We started blog to be the opposite: a calm, considered corner where every article has a point of view,
                a clear structure, and a reason to exist.
            </p>
            <p class="mt-4 text-slate-600 dark:text-slate-300">
                We write for curious people. Whether you are debugging a stubborn bug, planning a trip, or thinking about
                how you spend your hours, our goal is simple — give you something worth your time.
            </p>
        </section>

        <section class="mt-16">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">What we write about</h2>
            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                <div v-for="topic in topics" :key="topic.title" class="rounded-2xl border border-slate-200/60 bg-gradient-to-br from-white to-slate-50 p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800/80 dark:from-slate-900/60 dark:to-slate-900/30">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/25">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="topic.icon" />
                        </svg>
                    </span>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">{{ topic.title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ topic.description }}</p>
                </div>
            </div>
        </section>

        <section class="mt-16">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-3xl">How we work</h2>
            <div class="mt-6 space-y-4">
                <div v-for="(value, i) in values" :key="value.title" class="flex gap-4 rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900/50">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-sm font-extrabold text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                        {{ i + 1 }}
                    </span>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">{{ value.title }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400">{{ value.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-16 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-fuchsia-600 p-8 text-center text-white shadow-xl shadow-indigo-500/25 sm:p-12">
            <h2 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Join the conversation</h2>
            <p class="mx-auto mt-3 max-w-xl text-indigo-100">
                Have a story idea, a correction, or just want to say hi? We read every message — and reply to most.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <RouterLink to="/contact" class="rounded-full bg-white px-6 py-2.5 text-sm font-semibold text-indigo-600 shadow-lg transition hover:bg-indigo-50 active:scale-95">
                    Contact us
                </RouterLink>
                <RouterLink to="/search" class="rounded-full border border-white/40 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10 active:scale-95">
                    Browse articles
                </RouterLink>
            </div>
        </section>
    </div>
</template>
