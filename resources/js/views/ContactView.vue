<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../services/api'
import ErrorList from '../components/ui/ErrorList.vue'

onMounted(() => (document.title = 'Contact · Blog'))

const form = reactive({ name: '', email: '', subject: '', message: '' })
const errors = ref({})
const loading = ref(false)
const success = ref(false)

async function submit() {
    loading.value = true
    success.value = false
    errors.value = {}
    try {
        await api.post('/contact', form)
        success.value = true
        form.name = form.email = form.subject = form.message = ''
    } catch (e) {
        errors.value = e.response?.data?.errors || {}
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="mx-auto max-w-3xl px-4 py-5 sm:px-6">
        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400">
            Get in touch
        </span>
        <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl">Contact Us</h1>
        <p class="mt-2 text-slate-500 dark:text-slate-400">Questions, feedback, or story ideas — we'd love to hear from you.</p>

        <form @submit.prevent="submit" class="mt-10 space-y-5 rounded-3xl border border-slate-200/60 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-8 dark:border-slate-800/80 dark:bg-slate-900/50">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Name</label>
                    <input v-model="form.name" type="text" required class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Email</label>
                    <input v-model="form.email" type="email" required class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Subject</label>
                <input v-model="form.subject" type="text" required class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Message</label>
                <textarea v-model="form.message" rows="6" required class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"></textarea>
            </div>

            <ErrorList :errors="errors" />

            <button type="submit" :disabled="loading" class="rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95 disabled:opacity-50">
                {{ loading ? 'Sending...' : 'Send Message' }}
            </button>

            <p v-if="success" class="text-sm text-green-600">Message sent successfully. We'll get back to you soon.</p>
        </form>
    </div>
</template>
