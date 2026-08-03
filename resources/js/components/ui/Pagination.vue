<script setup>
import { computed } from 'vue'

const props = defineProps({
    current: { type: Number, required: true },
    last: { type: Number, required: true },
})

const emit = defineEmits(['change'])

const pages = computed(() => {
    const total = props.last
    const current = props.current
    const start = Math.max(1, current - 2)
    const end = Math.min(total, start + 4)
    const list = []
    for (let i = start; i <= end; i++) list.push(i)
    return list
})

function change(page) {
    if (page !== props.current) emit('change', page)
}
</script>

<template>
    <div class="flex flex-wrap items-center justify-center gap-2">
        <button
            v-for="page in pages"
            :key="page"
            @click="change(page)"
            :disabled="page === current"
            :class="[
                'h-10 min-w-10 rounded-full px-3 text-sm font-semibold transition active:scale-95',
                page === current
                    ? 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/25'
                    : 'text-slate-600 hover:bg-slate-200/70 dark:text-slate-300 dark:hover:bg-slate-800',
            ]"
        >
            {{ page }}
        </button>
    </div>
</template>
