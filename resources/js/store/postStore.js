import { defineStore } from 'pinia'
import api from '../services/api'

export const usePostStore = defineStore('posts', {
    state: () => ({
        posts: [],
        meta: null,
        featured: null,
        related: [],
        categories: [],
        tags: [],
        loading: false,
    }),

    actions: {
        async fetchPosts(params = {}) {
            this.loading = true
            try {
                const { data } = await api.get('/posts', { params })
                this.posts = data.data
                this.meta = {
                    current_page: data.meta.current_page,
                    last_page: data.meta.last_page,
                    total: data.meta.total,
                }
            } finally {
                this.loading = false
            }
        },

        async fetchFeatured() {
            const { data } = await api.get('/posts', { params: { per_page: 1 } })
            this.featured = data.data[0] || null
        },

        async fetchCategories() {
            const { data } = await api.get('/categories')
            this.categories = data
        },

        async fetchTags() {
            const { data } = await api.get('/tags')
            this.tags = data
        },

        async fetchRelated(slug) {
            const { data } = await api.get(`/posts/${slug}/related`)
            this.related = data.data
        },
    },
})
