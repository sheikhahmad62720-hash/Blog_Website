<script setup>
import { computed } from 'vue'

const props = defineProps({
    errors: { type: Object, default: () => ({}) },
})

const hasErrors = computed(() => Object.keys(props.errors).length > 0)
</script>

<template>
    <ul v-if="hasErrors" class="space-y-1 rounded-xl border border-red-200 bg-red-50 p-3 dark:border-red-500/30 dark:bg-red-500/10">
        <li v-for="(messages, field) in errors" :key="field" class="text-xs font-medium text-red-600 dark:text-red-400">
            <span v-for="message in messages" :key="message" class="block">{{ message }}</span>
        </li>
    </ul>
</template>
