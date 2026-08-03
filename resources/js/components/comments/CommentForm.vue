<script setup>
import { ref } from 'vue'
import { useAuth } from '../../composables/useAuth'
import ErrorList from '../ui/ErrorList.vue'

const props = defineProps({
    postSlug: { type: String, required: true },
    submit: { type: Function, required: true },
    submitting: { type: Boolean, default: false },
    errors: { type: Object, default: () => ({}) },
})

const auth = useAuth()
const body = ref('')
const name = ref('')
const email = ref('')
const replyingTo = ref(null)
const replyingId = ref(null)

function submit() {
    const payload = {
        body: body.value,
        parent_id: replyingId.value,
    }
    if (!auth.user) {
        payload.author_name = name.value
        payload.author_email = email.value
    }
    props.submit(payload).then((res) => {
        if (res.ok) {
            body.value = ''
            resetReply()
        }
    })
}

function setReply(name, id) {
    replyingTo.value = name
    replyingId.value = id
}

function resetReply() {
    replyingTo.value = null
    replyingId.value = null
}

defineExpose({ setReply, resetReply })
</script>

<template>
    <template v-if="!replyingTo">
        <form @submit.prevent="submit" class="space-y-3">
            <div v-if="!auth.user" class="grid gap-3 sm:grid-cols-2">
                <input
                    v-model="name"
                    type="text"
                    required
                    placeholder="Your name"
                    class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                />
                <input
                    v-model="email"
                    type="email"
                    required
                    placeholder="Your email"
                    class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                />
            </div>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="flex items-center gap-2 rounded-lg border border-slate-200/80 bg-white px-3 py-2 dark:border-slate-700 dark:bg-slate-900">
                        <img
                            v-if="auth.user?.avatar"
                            :src="auth.user.avatar"
                            class="h-6 w-6 rounded-full object-cover"
                            alt=""
                        />
                        <span v-else class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-violet-100 text-xs font-semibold text-indigo-600">
                            {{ (auth.user?.name || 'G').charAt(0) }}
                        </span>
                        <input
                            v-model="body"
                            type="text"
                            required
                            placeholder="Write a comment..."
                            class="w-full bg-transparent text-sm text-slate-700 outline-none dark:text-slate-200"
                        />
                    </div>
                </div>
                <button
                    type="submit"
                    :disabled="submitting"
                    class="shrink-0 rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95 disabled:opacity-50"
                >
                    {{ submitting ? '...' : 'Post' }}
                </button>
            </div>
            <ErrorList v-if="Object.keys(errors).length" :errors="errors" />
        </form>
    </template>

    <template v-else>
        <form @submit.prevent="submit" class="mt-3 rounded-2xl border border-slate-200/80 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800/40">
            <div class="mb-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                <span>Replying to <strong class="text-slate-700 dark:text-slate-200">{{ replyingTo }}</strong></span>
                <button type="button" @click="resetReply" class="text-indigo-600 hover:underline">Cancel</button>
            </div>
            <textarea
                v-model="body"
                required
                rows="3"
                placeholder="Write a reply..."
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
            ></textarea>
            <div v-if="!auth.user" class="grid gap-3 sm:grid-cols-2">
                <input
                    v-model="name"
                    type="text"
                    required
                    placeholder="Your name"
                    class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                />
                <input
                    v-model="email"
                    type="email"
                    required
                    placeholder="Your email"
                    class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200"
                />
            </div>
            <ErrorList v-if="Object.keys(errors).length" :errors="errors" class="mt-2" />
            <button
                type="submit"
                :disabled="submitting"
                class="mt-2 rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-4 py-1.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95 disabled:opacity-50"
            >
                {{ submitting ? 'Posting...' : 'Post Reply' }}
            </button>
        </form>
    </template>
</template>
