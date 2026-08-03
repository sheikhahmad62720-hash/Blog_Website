<script setup>
import { nextTick, onMounted, ref, watch } from 'vue'

const props = defineProps({
    content: { type: String, required: true },
})

const contentEl = ref(null)

function assignHeadingIds() {
    if (!contentEl.value) return
    contentEl.value.querySelectorAll('h2, h3').forEach((el, i) => {
        el.id = `heading-${i}`
        el.classList.add('scroll-mt-28', 'scroll-mb-6')
    })
}

onMounted(assignHeadingIds)

watch(
    () => props.content,
    async () => {
        await nextTick()
        assignHeadingIds()
    }
)
</script>

<template>
    <div ref="contentEl" class="prose prose-lg prose-slate max-w-none dark:prose-invert" v-html="content"></div>
</template>
