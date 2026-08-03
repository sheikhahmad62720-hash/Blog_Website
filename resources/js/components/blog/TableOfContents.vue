<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'

const props = defineProps({
    content: { type: String, required: true },
})

const activeId = ref(null)
let observer = null
let retryTimer = null

const headings = computed(() => {
    const matches = [...props.content.matchAll(/<h([2-3])[^>]*>(.*?)<\/h\1>/g)]
    return matches.map((m, i) => ({
        id: `heading-${i}`,
        level: parseInt(m[1]),
        text: m[2].replace(/<[^>]+>/g, ''),
    }))
})

function observe() {
    observer?.disconnect()
    observer = null
    clearTimeout(retryTimer)

    if (!headings.value.length) return

    const targets = headings.value
        .map((h) => document.getElementById(h.id))
        .filter(Boolean)

    if (!targets.length) {
        retryTimer = setTimeout(observe, 100)
        return
    }

    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) activeId.value = entry.target.id
            })
        },
        { rootMargin: '-15% 0px -75% 0px', threshold: 0 }
    )
    targets.forEach((el) => observer.observe(el))
    if (!activeId.value) activeId.value = targets[0].id
}

onMounted(() => observe())

watch(
    () => props.content,
    async () => {
        activeId.value = null
        await nextTick()
        observe()
    }
)

onBeforeUnmount(() => {
    observer?.disconnect()
    clearTimeout(retryTimer)
})

function scrollTo(id) {
    document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}
</script>

<template>
    <nav v-if="headings.length" class="rounded-2xl border border-slate-200/60 bg-white/70 p-6 backdrop-blur dark:border-slate-800/80 dark:bg-slate-900/40">
        <h3 class="text-base font-bold tracking-wide text-slate-900 dark:text-white">Table of Contents</h3>
        <ul class="mt-4 space-y-2.5 border-l border-slate-200 dark:border-slate-700">
            <li v-for="heading in headings" :key="heading.id">
                <a
                    :href="`#${heading.id}`"
                    :style="{ paddingLeft: (heading.level - 2) * 16 + 'px' }"
                    :class="[
                        '-ml-px block border-l-2 py-1 text-base leading-snug transition-all duration-200',
                        activeId === heading.id
                            ? 'border-indigo-500 font-bold text-indigo-600 dark:border-indigo-400 dark:text-indigo-400'
                            : 'border-transparent text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400',
                    ]"
                    @click.prevent="scrollTo(heading.id)"
                >
                    {{ heading.text }}
                </a>
            </li>
        </ul>
    </nav>
</template>
