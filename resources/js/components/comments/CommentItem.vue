<script setup>
defineProps({
    comment: { type: Object, required: true },
    canDelete: { type: Boolean, default: false },
})

defineEmits(['reply', 'delete'])
</script>

<template>
    <div class="rounded-2xl border border-slate-200/60 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-slate-800/80 dark:bg-slate-900/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img
                    v-if="comment.avatar"
                    :src="comment.avatar"
                    class="h-9 w-9 rounded-full object-cover ring-2 ring-slate-50 dark:ring-slate-800"
                    :alt="comment.author"
                />
                <span v-else class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-sm font-semibold text-indigo-600 ring-2 ring-slate-50 dark:from-indigo-900 dark:to-violet-900 dark:text-indigo-300 dark:ring-slate-800">
                    {{ comment.author?.charAt(0) }}
                </span>
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ comment.author }}
                        <span v-if="comment.is_author" class="ml-1 rounded bg-indigo-50 px-1.5 py-0.5 text-xs font-medium text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                            Author
                        </span>
                    </p>
                    <p class="text-xs text-slate-400">{{ comment.created_at }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button
                    @click="$emit('reply', comment)"
                    class="text-xs font-medium text-slate-500 transition hover:text-indigo-600 dark:text-slate-400"
                >
                    Reply
                </button>
                <button
                    v-if="canDelete"
                    @click="$emit('delete', comment.id)"
                    class="text-xs font-medium text-slate-400 transition hover:text-red-500"
                >
                    Delete
                </button>
            </div>
        </div>

        <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ comment.body }}</p>

        <div v-if="comment.replies?.length" class="mt-4 space-y-3 border-l-2 border-slate-100 pl-4 dark:border-slate-800">
            <div v-for="reply in comment.replies" :key="reply.id" class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/40">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                        {{ reply.author }}
                        <span v-if="reply.is_author" class="ml-1 rounded bg-indigo-50 px-1.5 py-0.5 text-xs font-medium text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                            Author
                        </span>
                    </p>
                    <p class="text-xs text-slate-400">{{ reply.created_at }}</p>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ reply.body }}</p>
            </div>
        </div>
    </div>
</template>
