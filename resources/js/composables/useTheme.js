import { ref, watch, onMounted } from 'vue'

const prefersDark = window.matchMedia('(prefers-color-scheme: dark)')

export function useTheme() {
    const theme = ref(localStorage.getItem('blog_theme') || (prefersDark.matches ? 'dark' : 'light'))

    function apply() {
        document.documentElement.classList.toggle('dark', theme.value === 'dark')
        localStorage.setItem('blog_theme', theme.value)
    }

    function toggle() {
        theme.value = theme.value === 'dark' ? 'light' : 'dark'
        apply()
    }

    watch(theme, apply)
    onMounted(apply)
    apply()

    return { theme, toggle }
}