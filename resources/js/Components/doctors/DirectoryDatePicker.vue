<script setup>
import { CalendarDays, ChevronLeft, ChevronRight } from '@lucide/vue'
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'

const props = defineProps({
  value: { type: String, default: '' },
  english: { type: Boolean, default: false },
})

const emit = defineEmits(['change'])
const open = ref(false)
const root = ref(null)
const trigger = ref(null)

function parseValue(value) {
  return value ? new Date(`${value}T12:00:00`) : null
}

const initialDate = parseValue(props.value) || new Date()
const displayedMonth = ref(new Date(initialDate.getFullYear(), initialDate.getMonth(), 1))
const selectedDate = computed(() => parseValue(props.value))
const locale = computed(() => (props.english ? 'en-US' : 'es-DO'))
const weekdays = computed(() => (props.english ? ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'] : ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá']))
const monthLabel = computed(() => displayedMonth.value.toLocaleDateString(locale.value, { month: 'long', year: 'numeric' }))
const triggerLabel = computed(() => selectedDate.value?.toLocaleDateString(locale.value) || (props.english ? 'Availability' : 'Disponibilidad'))

const days = computed(() => {
  const first = new Date(displayedMonth.value)
  const calendarStart = new Date(first)
  calendarStart.setDate(first.getDate() - first.getDay())
  return Array.from({ length: 42 }, (_, index) => {
    const date = new Date(calendarStart)
    date.setDate(calendarStart.getDate() + index)
    return date
  })
})

function toIso(date) {
  return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
}

function isSelected(date) {
  return props.value === toIso(date)
}

function isToday(date) {
  const today = new Date()
  return date.toDateString() === today.toDateString()
}

function selectDate(date) {
  emit('change', toIso(date))
  displayedMonth.value = new Date(date.getFullYear(), date.getMonth(), 1)
  open.value = false
  nextTick(() => trigger.value?.focus())
}

function changeMonth(offset) {
  displayedMonth.value = new Date(displayedMonth.value.getFullYear(), displayedMonth.value.getMonth() + offset, 1)
}

function closeAndFocus() {
  open.value = false
  nextTick(() => trigger.value?.focus())
}

function handlePointerDown(event) {
  if (open.value && !root.value?.contains(event.target)) open.value = false
}

function handleKeyDown(event) {
  if (event.key === 'Escape' && open.value) closeAndFocus()
}

watch(() => props.value, (value) => {
  const date = parseValue(value)
  if (date) displayedMonth.value = new Date(date.getFullYear(), date.getMonth(), 1)
})

onMounted(() => {
  document.addEventListener('pointerdown', handlePointerDown)
  document.addEventListener('keydown', handleKeyDown)
})

onBeforeUnmount(() => {
  document.removeEventListener('pointerdown', handlePointerDown)
  document.removeEventListener('keydown', handleKeyDown)
})
</script>

<template>
  <div ref="root" class="date-picker">
    <button
      ref="trigger"
      type="button"
      class="directory-field date-picker__trigger"
      aria-haspopup="dialog"
      :aria-expanded="open"
      @click="open = !open"
    >
      <CalendarDays :size="18" />
      <span>{{ triggerLabel }}</span>
    </button>

    <Transition name="calendar-pop">
      <div v-if="open" class="date-picker__panel" role="dialog" :aria-label="english ? 'Choose consultation date' : 'Elegir fecha de consulta'">
        <div class="calendar">
          <div class="calendar__caption">
            <button type="button" :aria-label="english ? 'Previous month' : 'Mes anterior'" @click="changeMonth(-1)">
              <ChevronLeft :size="16" />
            </button>
            <strong>{{ monthLabel }}</strong>
            <button type="button" :aria-label="english ? 'Next month' : 'Mes siguiente'" @click="changeMonth(1)">
              <ChevronRight :size="16" />
            </button>
          </div>
          <div class="calendar__weekdays" aria-hidden="true">
            <span v-for="weekday in weekdays" :key="weekday">{{ weekday }}</span>
          </div>
          <div class="calendar__days">
            <button
              v-for="day in days"
              :key="toIso(day)"
              type="button"
              :class="{
                'calendar__day--outside': day.getMonth() !== displayedMonth.getMonth(),
                'calendar__day--today': isToday(day),
                'calendar__day--selected': isSelected(day),
              }"
              :aria-label="day.toLocaleDateString(locale, { dateStyle: 'full' })"
              :aria-pressed="isSelected(day)"
              @click="selectDate(day)"
            >
              {{ day.getDate() }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>
