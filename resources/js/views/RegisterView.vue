<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import ErrorList from '../components/ui/ErrorList.vue'

onMounted(() => (document.title = 'Register · Blog'))

const router = useRouter()
const { register } = useAuth()

const name = ref('')
const email = ref('')
const password = ref('')
const errors = ref({})
const loading = ref(false)

async function submit() {
    loading.value = true
    errors.value = {}
    try {
        await register(name.value, email.value, password.value)
        router.push('/')
    } catch (e) {
        errors.value = e.response?.data?.errors || {}
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="mx-auto flex min-h-[70vh] max-w-md flex-col justify-center px-4 py-16 sm:px-6">
        <div class="rounded-2xl border border-slate-200/60 bg-white p-8 shadow-xl shadow-slate-900/5 dark:border-slate-800/80 dark:bg-slate-900/50">
            <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
                New here?
            </span>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white">Create an account</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Join the conversation today.</p>

            <form @submit.prevent="submit" class="mt-8 space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Name</label>
                    <input v-model="name" type="text" required class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
                    <input v-model="email" type="email" required class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Password</label>
                    <input v-model="password" type="password" required minlength="8" class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                </div>

                <ErrorList :errors="errors" />

                <button type="submit" :disabled="loading" class="w-full rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-[0.98] disabled:opacity-50">
                    {{ loading ? 'Creating...' : 'Create account' }}
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                Already have an account?
                <RouterLink to="/login" class="font-semibold text-indigo-600 hover:underline">Log in</RouterLink>
            </p>
        </div>
    </div>
</template>
