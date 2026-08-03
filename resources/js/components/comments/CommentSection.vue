<script setup>
import { computed, ref } from 'vue'
import { useComments } from '../../composables/useComments'
import { useAuth } from '../../composables/useAuth'
import CommentForm from './CommentForm.vue'
import CommentItem from './CommentItem.vue'

const props = defineProps({
    postSlug: { type: String, required: true },
})

const auth = useAuth()
const formRef = ref(null)
const errors = ref({})

const { comments, loading, submitting, fetchComments, addComment, deleteComment } = useComments(props.postSlug)

fetchComments()

const totalComments = computed(() => {
    let total = comments.value.length
    comments.value.forEach((c) => (total += (c.replies?.length || 0)))
    return total
})

async function handleSubmit(payload) {
    errors.value = {}
    const res = await addComment(payload)
    if (!res.ok) {
        errors.value = res.error || {}
    }
    return res
}

function openReply(comment) {
    formRef.value?.setReply(comment.author, comment.id)
    document.getElementById('comments')?.scrollIntoView({ behavior: 'smooth' })
}

async function removeComment(id) {
    await deleteComment(id)
}

function canDelete(comment) {
    if (!auth.user) return false
    return auth.user.role === 'admin' || comment.author_id === auth.user.id
}
</script>

<template>
    <section class="mt-12">
        <div class="mb-6">
            <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                Discussion
            </span>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Comments <span class="text-base font-normal text-slate-400">({{ totalComments }})</span>
            </h2>
        </div>

        <div class="mt-6">
            <CommentForm
                ref="formRef"
                :post-slug="postSlug"
                :submit="handleSubmit"
                :submitting="submitting"
                :errors="errors"
            />
        </div>

        <div v-if="loading" class="mt-8 space-y-4">
            <div v-for="i in 2" :key="i" class="h-20 animate-pulse rounded-2xl bg-slate-200/70 dark:bg-slate-800/70"></div>
        </div>

        <div v-else-if="comments.length" class="mt-8 space-y-6">
            <CommentItem
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
                :can-delete="canDelete(comment)"
                @reply="openReply"
                @delete="removeComment"
            />
        </div>

        <p v-else class="mt-8 text-sm text-slate-500 dark:text-slate-400">
            No comments yet. Be the first to share your thoughts.
        </p>
    </section>
</template>
