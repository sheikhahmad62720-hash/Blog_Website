import { ref } from 'vue'
import api from '../services/api'

export function usePosts() {
    const posts = ref([])
    const meta = ref({ current_page: 1, last_page: 1, total: 0 })
    const loading = ref(false)
    const error = ref(null)

    async function fetchPosts(params = {}) {
        loading.value = true
        error.value = null
        try {
            const { data } = await api.get('/posts', { params })
            posts.value = data.data
            meta.value = data.meta
        } catch (e) {
            error.value = e.response?.data?.message || 'Could not load posts.'
        } finally {
            loading.value = false
        }
    }

    return { posts, meta, loading, error, fetchPosts }
}