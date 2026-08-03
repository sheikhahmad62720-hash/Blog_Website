<script setup>
import { onBeforeUnmount, onMounted, shallowRef, watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import Image from '@tiptap/extension-image'
import api from '../../services/api'

const emit = defineEmits(['update:modelValue'])
const fileInput = shallowRef(null)

const props = defineProps({
    modelValue: { type: String, default: '' },
})

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit.configure({ heading: { levels: [2, 3] } }),
        Link.configure({ openOnClick: false }),
        Image,
    ],
    editorProps: {
        attributes: {
            class: 'outline-none prose prose-slate dark:prose-invert max-w-none focus:outline-none',
        },
    },
    onUpdate: () => emit('update:modelValue', editor.value?.getHTML()),
})

onMounted(() => {
    if (props.modelValue) editor.value?.commands.setContent(props.modelValue)
})

watch(
    () => props.modelValue,
    (value) => {
        if (editor.value && value !== editor.value.getHTML()) {
            editor.value.commands.setContent(value || '')
        }
    }
)

onBeforeUnmount(() => editor.value?.destroy())

const buttons = [
    { label: 'Bold', icon: 'B', active: () => editor.value?.isActive('bold'), action: () => editor.value?.chain().focus().toggleBold().run() },
    { label: 'Italic', icon: 'I', active: () => editor.value?.isActive('italic'), action: () => editor.value?.chain().focus().toggleItalic().run() },
    { label: 'Heading 2', icon: 'H2', active: () => editor.value?.isActive('heading', { level: 2 }), action: () => editor.value?.chain().focus().toggleHeading({ level: 2 }).run() },
    { label: 'Heading 3', icon: 'H3', active: () => editor.value?.isActive('heading', { level: 3 }), action: () => editor.value?.chain().focus().toggleHeading({ level: 3 }).run() },
    { label: 'Link', icon: 'Link', active: () => editor.value?.isActive('link'), action: () => setLink() },
    { label: 'Image', icon: 'Img', active: () => false, action: () => fileInput.value?.click() },
    { label: 'Code block', icon: 'Code', active: () => editor.value?.isActive('codeBlock'), action: () => editor.value?.chain().focus().toggleCodeBlock().run() },
    { label: 'Blockquote', icon: 'Quote', active: () => editor.value?.isActive('blockquote'), action: () => editor.value?.chain().focus().toggleBlockquote().run() },
    { label: 'Bullet list', icon: '• List', active: () => editor.value?.isActive('bulletList'), action: () => editor.value?.chain().focus().toggleBulletList().run() },
    { label: 'Ordered list', icon: '1. List', active: () => editor.value?.isActive('orderedList'), action: () => editor.value?.chain().focus().toggleOrderedList().run() },
]

function setLink() {
    const url = window.prompt('Enter URL:')
    if (url === null) return
    if (url === '') {
        editor.value?.chain().focus().extendMarkRange('link').unsetLink().run()
        return
    }
    editor.value?.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
}

async function handleImageUpload(event) {
    const file = event.target.files[0]
    if (!file) return
    const formData = new FormData()
    formData.append('image', file)
    const { data } = await api.post('/uploads/images', formData)
    editor.value?.chain().focus().setImage({ src: data.url }).run()
    event.target.value = ''
}
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-50/80 p-2 dark:border-slate-700 dark:bg-slate-800/50">
            <button v-for="btn in buttons" :key="btn.label" type="button" :title="btn.label" @click="btn.action"
                :class="[
                    'flex h-8 min-w-8 items-center justify-center rounded-lg px-2 text-xs font-semibold transition active:scale-95',
                    btn.active() ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/25' : 'text-slate-600 hover:bg-slate-200 dark:text-slate-300 dark:hover:bg-slate-700',
                ]">
                {{ btn.icon }}
            </button>
        </div>
        <editor-content :editor="editor" class="editor-content min-h-[300px] px-4 py-3 text-slate-800 dark:text-slate-200" />
        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="handleImageUpload" />
    </div>
</template>
