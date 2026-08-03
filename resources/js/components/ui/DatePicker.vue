<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'

const props = defineProps({
    modelValue: { type: String, default: '' },
})

const emit = defineEmits(['update:modelValue'])

const wrap = ref(null)
const open = ref(false)
const viewYear = ref(new Date().getFullYear())
const viewMonth = ref(new Date().getMonth())
const day = ref(null)
const hour = ref(new Date().getHours())
const minute = ref(0)

const weekdays = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']
const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
const minutes = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55]

const minuteOptions = computed(() => {
    const opts = [...minutes]
    const current = minute.value
    if (!opts.includes(current)) opts.push(current)
    return opts.sort((a, b) => a - b)
})

function parseValue(value) {
    if (!value) return { day: null, hour: 0, minute: 0 }
    const d = new Date(value)
    if (Number.isNaN(d.getTime())) return { day: null, hour: 0, minute: 0 }
    return { day: d, hour: d.getHours(), minute: d.getMinutes() }
}

function initFromValue() {
    const { day: d, hour: h, minute: m } = parseValue(props.modelValue)
    if (d) {
        viewYear.value = d.getFullYear()
        viewMonth.value = d.getMonth()
    }
    hour.value = h
    minute.value = m
    day.value = d
}

const selected = computed(() => {
    if (!day.value) return null
    return new Date(viewYear.value, viewMonth.value, day.value.getDate(), hour.value, minute.value)
})

const displayText = computed(() => {
    if (!selected.value) return 'Select date & time'
    const d = selected.value
    const pad = (n) => String(n).padStart(2, '0')
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(hour.value)}:${pad(minute.value)}`
})

const monthName = computed(() => months[viewMonth.value])

const firstDayOfMonth = computed(() => new Date(viewYear.value, viewMonth.value, 1).getDay())
const daysInMonth = computed(() => new Date(viewYear.value, viewMonth.value + 1, 0).getDate())
const daysInPrevMonth = computed(() => new Date(viewYear.value, viewMonth.value, 0).getDate())

const today = new Date()

const calendarCells = computed(() => {
    const cells = []
    const startOffset = firstDayOfMonth.value
    const total = startOffset + daysInMonth.value
    const gridSize = Math.ceil(total / 7) * 7
    for (let i = 0; i < gridSize; i++) {
        const dayNum = i - startOffset + 1
        const inMonth = dayNum >= 1 && dayNum <= daysInMonth.value
        const date = inMonth
            ? new Date(viewYear.value, viewMonth.value, dayNum)
            : dayNum < 1
                ? new Date(viewYear.value, viewMonth.value - 1, daysInPrevMonth.value + dayNum)
                : new Date(viewYear.value, viewMonth.value + 1, dayNum - daysInMonth.value)
        cells.push({
            key: `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`,
            day: date.getDate(),
            inMonth,
            date,
            isToday: inMonth && date.getDate() === today.getDate() && date.getMonth() === today.getMonth() && date.getFullYear() === today.getFullYear(),
            isSelected: day.value && date.getFullYear() === day.value.getFullYear() && date.getMonth() === day.value.getMonth() && date.getDate() === day.value.getDate(),
        })
    }
    return cells
})

function shiftMonth(offset) {
    const d = new Date(viewYear.value, viewMonth.value + offset, 1)
    viewYear.value = d.getFullYear()
    viewMonth.value = d.getMonth()
}

function selectDay(cell) {
    viewYear.value = cell.date.getFullYear()
    viewMonth.value = cell.date.getMonth()
    day.value = cell.date
    emitChange()
    open.value = false
}

function emitChange() {
    if (!day.value) return
    const pad = (n) => String(n).padStart(2, '0')
    const value = `${day.value.getFullYear()}-${pad(day.value.getMonth() + 1)}-${pad(day.value.getDate())}T${pad(hour.value)}:${pad(minute.value)}`
    emit('update:modelValue', value)
}

function setToday() {
    const now = new Date()
    viewYear.value = now.getFullYear()
    viewMonth.value = now.getMonth()
    day.value = now
    hour.value = now.getHours()
    minute.value = now.getMinutes()
    emitChange()
    open.value = false
}

function clearValue() {
    day.value = null
    emit('update:modelValue', '')
}

watch([hour, minute], () => emitChange())
watch(() => props.modelValue, initFromValue)

function onClickOutside(e) {
    if (open.value && wrap.value && !wrap.value.contains(e.target)) {
        open.value = false
    }
}

onMounted(() => {
    initFromValue()
    document.addEventListener('mousedown', onClickOutside)
})

onBeforeUnmount(() => document.removeEventListener('mousedown', onClickOutside))
</script>

<template>
    <div ref="wrap" class="relative">
        <button
            type="button"
            @click="open = !open"
            class="flex w-full items-center justify-between gap-2 rounded-lg border border-slate-200/80 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200"
        >
            <span :class="selected ? '' : 'text-slate-400'">{{ displayText }}</span>
            <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </button>

        <transition
            enter-active-class="transition-all duration-200 ease-out"
            leave-active-class="transition-all duration-150 ease-in"
            enter-from-class="opacity-0 -translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="open"
                class="absolute left-0 right-0 z-40 mt-2 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-2xl shadow-slate-900/10 dark:border-slate-700 dark:bg-slate-900"
            >
                <div class="flex items-center justify-between border-b border-slate-100 px-3 py-2.5 dark:border-slate-800">
                    <button type="button" @click="shiftMonth(-1)" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ monthName }} {{ viewYear }}</span>
                    <button type="button" @click="shiftMonth(1)" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>

                <div class="grid grid-cols-7 gap-1 px-3 pt-2 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    <span v-for="d in weekdays" :key="d">{{ d }}</span>
                </div>

                <div class="grid grid-cols-7 gap-1 p-3">
                    <button
                        v-for="cell in calendarCells"
                        :key="cell.key"
                        type="button"
                        @click="selectDay(cell)"
                        :class="[
                            'flex h-9 w-full items-center justify-center rounded-lg text-sm font-semibold transition active:scale-90',
                            cell.inMonth ? '' : 'text-slate-300 dark:text-slate-600',
                            cell.isToday ? 'ring-1 ring-indigo-400' : '',
                            cell.isSelected
                                ? 'bg-gradient-to-r from-indigo-500 to-violet-600 text-white shadow-md shadow-indigo-500/25'
                                : cell.inMonth ? 'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' : '',
                        ]"
                    >
                        {{ cell.day }}
                    </button>
                </div>

                <div class="flex items-center gap-2 border-t border-slate-100 px-3 py-2.5 dark:border-slate-800">
                    <select v-model.number="hour" class="w-full rounded-lg border border-slate-200/80 bg-white px-2 py-1.5 text-sm text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        <option v-for="h in 24" :key="h - 1" :value="h - 1">{{ String(h - 1).padStart(2, '0') }}</option>
                    </select>
                    <span class="text-slate-400">:</span>
                    <select v-model.number="minute" class="w-full rounded-lg border border-slate-200/80 bg-white px-2 py-1.5 text-sm text-slate-700 outline-none focus:border-indigo-400 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200">
                        <option v-for="m in minuteOptions" :key="m" :value="m">{{ String(m).padStart(2, '0') }}</option>
                    </select>
                    <div class="ml-auto flex gap-1.5">
                        <button type="button" @click="clearValue" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-500 transition hover:bg-slate-100 dark:hover:bg-slate-800">Clear</button>
                        <button type="button" @click="setToday" class="rounded-lg bg-indigo-50 px-2.5 py-1.5 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400">Today</button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
