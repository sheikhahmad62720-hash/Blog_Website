<script setup>
import { ref } from 'vue'
import api from '../../services/api'

const props = defineProps({
    light: { type: Boolean, default: false },
})

const email = ref('')
const message = ref('')
const error = ref(false)
const loading = ref(false)

async function subscribe() {
    loading.value = true
    error.value = false
    message.value = ''
    try {
        await api.post('/newsletter/subscribe', { email: email.value })
        message.value = 'You are subscribed!'
        email.value = ''
    } catch (e) {
        error.value = true
        message.value = e.response?.data?.errors?.email?.[0] || 'Could not subscribe.'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <form @submit.prevent="subscribe">
        <div class="flex gap-2">
            <input
                v-model="email"
                type="email"
                required
                placeholder="Your email address"
                class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
            />
            <button
                type="submit"
                :disabled="loading"
                :class="[
                    'shrink-0 rounded-lg px-4 py-2 text-sm font-semibold transition active:scale-95 disabled:opacity-50',
                    light
                        ? 'bg-white text-indigo-600 shadow-lg shadow-black/10 hover:bg-indigo-50'
                        : 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/25 hover:shadow-lg hover:brightness-110',
                ]"
            >
                {{ loading ? '...' : 'Subscribe' }}
            </button>
        </div>
        <p v-if="message" class="mt-2 text-sm" :class="error ? 'text-red-500' : 'text-green-600'">
            {{ message }}
        </p>
    </form>
</template>
