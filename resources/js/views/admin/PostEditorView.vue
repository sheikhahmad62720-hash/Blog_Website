<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import RichTextEditor from '../../components/editor/RichTextEditor.vue'
import DatePicker from '../../components/ui/DatePicker.vue'
import ErrorList from '../../components/ui/ErrorList.vue'
import { formatDate } from '../../utils'

const route = useRoute()
const router = useRouter()
const isEdit = computed(() => route.name === 'admin.posts.edit')

const categories = ref([])
const tags = ref([])
const selectedTags = ref([])
const errors = ref({})
const saving = ref(false)
const saved = ref(false)
const featuredFile = ref(null)
const editingId = ref(null)

const form = reactive({
    title: '',
    excerpt: '',
    content: '',
    category_id: null,
    status: 'published',
    meta_title: '',
    meta_description: '',
    published_at: '',
})

function resetForm() {
    form.title = ''
    form.excerpt = ''
    form.content = ''
    form.category_id = null
    form.status = 'published'
    form.meta_title = ''
    form.meta_description = ''
    form.published_at = ''
    selectedTags.value = []
    featuredUrl.value = null
    featuredFile.value = null
    editingId.value = null
    errors.value = {}
    saved.value = false
}

async function load() {
    resetForm()
    document.title = isEdit.value ? 'Edit Post · Blog' : 'New Post · Blog'
    const [cats, tgs] = await Promise.all([api.get('/categories'), api.get('/tags')])
    categories.value = cats.data
    tags.value = tgs.data
    if (isEdit.value && route.params.slug) await loadPost(route.params.slug)
}

onMounted(load)

watch(() => route.params.slug, () => load())

function toLocalInput(value) {
    const d = value ? new Date(value) : null
    if (!d || Number.isNaN(d.getTime())) return ''
    const pad = (n) => String(n).padStart(2, '0')
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`
}

async function loadPost(slug) {
    const { data } = await api.get(`/posts/${slug}`)
    const post = data.data
    editingId.value = post.id
    form.title = post.title
    form.excerpt = post.excerpt || ''
    form.content = post.content
    form.category_id = post.category?.id || null
    form.status = post.status
    form.meta_title = post.meta_title || ''
    form.meta_description = post.meta_description || ''
    form.published_at = toLocalInput(post.published_at)
    featuredUrl.value = post.featured_image
    selectedTags.value = (post.tags || []).map((t) => t.id)
}

const featuredUrl = ref(null)

function toggleTag(tag) {
    selectedTags.value = selectedTags.value.includes(tag.id)
        ? selectedTags.value.filter((id) => id !== tag.id)
        : [...selectedTags.value, tag.id]
}

function toUtcInput(value) {
    const d = value ? new Date(value) : new Date()
    if (Number.isNaN(d.getTime())) return new Date().toISOString().slice(0, 16)
    return d.toISOString().slice(0, 16)
}

function onImageChange(event) {
    featuredFile.value = event.target.files[0]
    featuredUrl.value = featuredFile.value ? URL.createObjectURL(featuredFile.value) : null
}

async function save() {
    saving.value = true
    saved.value = false
    errors.value = {}
    const payload = new FormData()
    payload.append('title', form.title)
    payload.append('content', form.content)
    payload.append('status', form.status)
    payload.append('excerpt', form.excerpt || '')
    payload.append('meta_title', form.meta_title || '')
    payload.append('meta_description', form.meta_description || '')
    payload.append('category_id', form.category_id || '')
    payload.append('published_at', toUtcInput(form.published_at))
    selectedTags.value.forEach((id) => payload.append('tags[]', id))
    if (featuredFile.value) payload.append('featured_image', featuredFile.value)

    try {
        if (isEdit.value) {
            await api.put(`/posts/${editingId.value}`, payload)
        } else {
            await api.post('/posts', payload)
        }
        saved.value = true
        setTimeout(() => router.push('/admin/posts'), 800)
    } catch (e) {
        errors.value = e.response?.data?.errors || {}
    } finally {
        saving.value = false
    }
}
</script>

<template>
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                    {{ isEdit ? 'Edit Post' : 'New Post' }}
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ isEdit ? 'Update your article content and settings.' : 'Write and publish a brand new article.' }}
                </p>
            </div>
            <RouterLink to="/admin/posts" class="text-sm font-semibold text-slate-500 transition hover:text-indigo-600">← Back to posts</RouterLink>
        </div>

        <form @submit.prevent="save" class="space-y-6">
            <div class="rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900/50">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Title</label>
                <input v-model="form.title" required class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-lg font-semibold text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Category</label>
                        <select v-model="form.category_id" class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            <option :value="null">None</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Status</label>
                        <select v-model="form.status" class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="tag in tags"
                            :key="tag.id"
                            type="button"
                            @click="toggleTag(tag)"
                            :class="[
                                'rounded-full px-3 py-1 text-xs font-semibold transition active:scale-95',
                                selectedTags.includes(tag.id)
                                    ? 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/25'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300',
                            ]"
                        >
                            {{ tag.name }}
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Featured Image</label>
                    <input type="file" accept="image/*" @change="onImageChange" class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-600 hover:file:bg-indigo-100 dark:text-slate-400" />
                    <img v-if="featuredUrl" :src="featuredUrl" class="mt-3 max-h-48 rounded-xl object-cover shadow-md" loading="lazy" alt="Featured" />
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900/50">
                <label class="mb-3 block text-sm font-semibold text-slate-700 dark:text-slate-300">Content</label>
                <RichTextEditor v-model="form.content" />
            </div>

            <div class="rounded-2xl border border-slate-200/60 bg-white p-6 shadow-sm dark:border-slate-800/80 dark:bg-slate-900/50">
                <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Excerpt</label>
                <textarea v-model="form.excerpt" rows="3" class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"></textarea>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Meta Title (SEO)</label>
                        <input v-model="form.meta_title" class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Meta Description (SEO)</label>
                        <input v-model="form.meta_description" class="w-full rounded-lg border border-slate-200/80 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-300">Publish Date &amp; Time</label>
                    <DatePicker v-model="form.published_at" />
                    <p class="mt-1.5 text-xs text-slate-400">Stored in UTC. Pick the local time you want the post to go live.</p>
                </div>
            </div>

            <ErrorList :errors="errors" />

            <div class="flex items-center gap-3">
                <button type="submit" :disabled="saving" class="rounded-lg bg-gradient-to-r from-indigo-500 to-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:shadow-lg hover:brightness-110 active:scale-95 disabled:opacity-50">
                    {{ saving ? 'Saving...' : isEdit ? 'Update Post' : 'Create Post' }}
                </button>
                <p v-if="saved" class="text-sm text-green-600">Saved!</p>
            </div>
        </form>
    </div>
</template>
