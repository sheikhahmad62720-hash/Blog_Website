import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('blog_user') || 'null'),
        token: localStorage.getItem('blog_token') || null,
    }),

    getters: {
        isAuthenticated: (state) => Boolean(state.token),
        isAdmin: (state) => state.user?.role === 'admin',
    },

    actions: {
        async login(email, password) {
            const { data } = await api.post('/login', { email, password })
            this.setSession(data)
        },

        async register(name, email, password) {
            const { data } = await api.post('/register', { name, email, password })
            this.setSession(data)
        },

        async fetchMe() {
            const { data } = await api.get('/me')
            this.user = data
            localStorage.setItem('blog_user', JSON.stringify(data))
            return data
        },

        async logout() {
            try {
                await api.post('/logout')
            } finally {
                this.user = null
                this.token = null
                localStorage.removeItem('blog_token')
                localStorage.removeItem('blog_user')
            }
        },

        setSession(data) {
            this.token = data.token
            this.user = data.user
            localStorage.setItem('blog_token', data.token)
            localStorage.setItem('blog_user', JSON.stringify(data.user))
        },
    },
})
