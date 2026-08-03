import { computed } from 'vue'
import { useAuthStore } from '../store/authStore'

export function useAuth() {
    const auth = useAuthStore()

    return {
        user: computed(() => auth.user),
        isAuthenticated: computed(() => auth.isAuthenticated),
        isAdmin: computed(() => auth.isAdmin),
        login: auth.login,
        register: auth.register,
        logout: auth.logout,
        fetchMe: auth.fetchMe,
    }
}