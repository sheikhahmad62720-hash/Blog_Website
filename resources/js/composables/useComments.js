import { ref } from 'vue'
import api from '../services/api'

export function useComments(postSlug) {
    const comments = ref([])
    const loading = ref(false)
    const submitting = ref(false)

    async function fetchComments() {
        loading.value = true
        try {
            const { data } = await api.get(`/posts/${postSlug}/comments`)
            comments.value = data.data
        } finally {
            loading.value = false
        }
    }

    async function addComment(payload) {
        submitting.value = true
        try {
            const { data } = await api.post(`/posts/${postSlug}/comments`, payload)
            if (payload.parent_id) {
                const parent = comments.value.find((c) => c.id === payload.parent_id)
                if (parent) {
                    parent.replies.push(data.data)
                }
            } else {
                comments.value.unshift(data)
            }
            return { ok: true }
        } catch (error) {
            return { ok: false, error: error.response?.data?.errors || {} }
        } finally {
            submitting.value = false
        }
    }

    async function deleteComment(id) {
        await api.delete(`/comments/${id}`)
        comments.value = comments.value.filter((c) => c.id !== id)
    }

    return { comments, loading, submitting, fetchComments, addComment, deleteComment }
}