<script setup>
import { computed, ref, watch } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import {
  ArrowLeft,
  CalendarDays,
  ChevronLeft,
  ChevronRight,
  DoorClosed,
  Phone,
  Stethoscope,
} from '@lucide/vue'
import { useLanguage } from '../../../Composables/useLanguage'
import { resolveDoctorPhoto } from '../../../Data/doctorPhotos'
import { isAvailableOnDate, todayInCenter } from '../../../Utils/directoryUtils'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/doctor-profile.css'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  doctor: { type: Object, required: true },
})

const { language } = useLanguage()
const today = todayInCenter()
const monthOffset = ref(0)
const selectedDate = ref(null)

const doctor = computed(() => ({
  ...props.doctor,
  photo: props.doctor.photo || resolveDoctorPhoto(props.doctor.id),
  description: props.doctor.descriptions,
  medicalAreas: props.doctor.medicalAreas.map(
    (medicalArea) => medicalArea.names[language.value] || medicalArea.names.es || medicalArea.slug,
  ),
}))
const english = computed(() => language.value === 'en')
const locale = computed(() => english.value ? 'en-US' : 'es-DO')

const copy = computed(() => english.value ? {
  back: 'Back to specialists',
  about: 'About the doctor',
  office: 'Office',
  extension: 'Extension',
  availability: 'Upcoming availability',
  nextConsultation: 'Next consultation',
  availabilityDescription: 'Available consultation dates are highlighted. Explore the upcoming months and choose the day that works best for you.',
  previous: 'Previous months',
  next: 'Next months',
  available: 'Available',
  unavailable: 'Unavailable',
  selected: 'Selected date',
  request: 'Request appointment',
  selectPrompt: 'Select an available date to request your appointment.',
  notFound: 'Specialist not found',
  notFoundDescription: 'The requested profile is not available.',
  weekdays: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
} : {
  back: 'Volver a especialistas',
  about: 'Sobre el doctor',
  office: 'Consultorio',
  extension: 'Extensión',
  availability: 'Próxima disponibilidad',
  nextConsultation: 'Próxima consulta',
  availabilityDescription: 'Las fechas de consulta disponibles están resaltadas. Explora los próximos meses y elige el día que más te convenga.',
  previous: 'Meses anteriores',
  next: 'Próximos meses',
  available: 'Disponible',
  unavailable: 'No disponible',
  selected: 'Fecha seleccionada',
  request: 'Solicitar cita',
  selectPrompt: 'Selecciona una fecha disponible para solicitar tu cita.',
  notFound: 'Especialista no encontrado',
  notFoundDescription: 'El perfil solicitado no está disponible.',
  weekdays: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
})

const months = computed(() => Array.from({ length: 1 }, () => {
  const date = new Date(today.getFullYear(), today.getMonth() + monthOffset.value, 1)
  const totalDays = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate()
  const leadingDays = date.getDay()
  const days = [
    ...Array.from({ length: leadingDays }, () => null),
    ...Array.from({ length: totalDays }, (_, dayIndex) => new Date(date.getFullYear(), date.getMonth(), dayIndex + 1, 12)),
  ]

  return {
    key: `${date.getFullYear()}-${date.getMonth()}`,
    label: date.toLocaleDateString(locale.value, { month: 'long', year: 'numeric' }),
    days,
  }
}))

const nextAvailableDate = computed(() => {
  if (!doctor.value) return null

  for (let offset = 0; offset < 14; offset += 1) {
    const date = new Date(today)
    date.setDate(date.getDate() + offset)
    if (isAvailableOnDate(doctor.value, date)) return date
  }

  return null
})

const nextConsultationLabel = computed(() => nextAvailableDate.value?.toLocaleDateString(locale.value, {
  weekday: 'long',
  day: 'numeric',
  month: 'long',
}))

const selectedLabel = computed(() => selectedDate.value?.toLocaleDateString(locale.value, {
  weekday: 'long',
  day: 'numeric',
  month: 'long',
  year: 'numeric',
}))

const appointmentUrl = computed(() => {
  if (!doctor.value || !selectedDate.value) return '#'
  const message = english.value
    ? `Hello, I would like to request an appointment with ${doctor.value.name} on ${selectedLabel.value}. Please confirm availability.`
    : `Hola, quisiera solicitar una cita con ${doctor.value.name} para el ${selectedLabel.value}. ¿Podrían confirmar la disponibilidad?`
  return `https://wa.me/18095562555?text=${encodeURIComponent(message)}`
})

function toIso(date) {
  return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
}

function isAvailable(date) {
  return date >= today && doctor.value && isAvailableOnDate(doctor.value, date)
}

function isToday(date) {
  return date.toDateString() === today.toDateString()
}

function isSelected(date) {
  return selectedDate.value?.toDateString() === date.toDateString()
}

function selectDate(date) {
  if (isAvailable(date)) selectedDate.value = date
}

function changeMonths(direction) {
  monthOffset.value = Math.min(9, Math.max(0, monthOffset.value + direction))
  selectedDate.value = null
}

watch(() => props.doctor.uuid, () => {
  monthOffset.value = 0
  selectedDate.value = null
})
</script>

<template>
  <Head :title="doctor.name" />
  <main class="doctor-profile">
      <div class="doctor-profile__container">
        <Link class="doctor-profile__back" href="/doctors"><ArrowLeft :size="17" />{{ copy.back }}</Link>

        <section class="doctor-hero">
          <div class="doctor-hero__image">
            <img :src="doctor.photo" :alt="doctor.name" />
          </div>
          <div class="doctor-hero__content">
            <span class="doctor-hero__medical-area"><Stethoscope :size="15" />{{ doctor.medicalAreas.join(' · ') }}</span>
            <h1>{{ doctor.name }}</h1>

            <div class="doctor-hero__details">
              <div class="doctor-hero__facts">
                <div><DoorClosed :size="18" /><span><small>{{ copy.office }}</small><strong>{{ doctor.room }}</strong></span></div>
                <div><Phone :size="18" /><span><small>{{ copy.extension }}</small><strong>{{ doctor.ext }}</strong></span></div>
              </div>

              <div class="doctor-hero__about">
                <h2>{{ copy.about }}</h2>
                <div class="doctor-hero__about-scroll" tabindex="0" :aria-label="copy.about">
                  <p>{{ doctor.description?.[language] }}</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="availability-section">
            <div class="availability-section__header">
              <div class="availability-section__heading">
                <span class="availability-section__eyebrow"><CalendarDays :size="15" />{{ copy.available }}</span>
                <div class="availability-section__title-row">
                  <h2>{{ copy.availability }}</h2>
                  <span v-if="nextConsultationLabel" class="availability-section__next-date">
                    <small>{{ copy.nextConsultation }}</small>
                    <strong>{{ nextConsultationLabel }}</strong>
                  </span>
                </div>
                <p>{{ copy.availabilityDescription }}</p>
              </div>
              <div class="availability-section__navigation">
                <button type="button" :disabled="monthOffset === 0" :aria-label="copy.previous" @click="changeMonths(-1)"><ChevronLeft :size="19" /></button>
                <button type="button" :disabled="monthOffset === 9" :aria-label="copy.next" @click="changeMonths(1)"><ChevronRight :size="19" /></button>
              </div>
            </div>

            <div class="availability-legend">
              <span><i class="availability-legend__dot availability-legend__dot--available"></i>{{ copy.available }}</span>
              <span><i class="availability-legend__dot"></i>{{ copy.unavailable }}</span>
            </div>

            <div class="profile-calendars">
              <article v-for="month in months" :key="month.key" class="profile-calendar">
                <h3>{{ month.label }}</h3>
                <div class="profile-calendar__weekdays" aria-hidden="true">
                  <span v-for="weekday in copy.weekdays" :key="weekday">{{ weekday }}</span>
                </div>
                <div class="profile-calendar__days">
                  <span v-for="(day, index) in month.days" :key="day ? toIso(day) : `blank-${index}`" class="profile-calendar__cell">
                    <button
                      v-if="day"
                      type="button"
                      :disabled="!isAvailable(day)"
                      :class="{
                        'profile-calendar__day--available': isAvailable(day),
                        'profile-calendar__day--today': isToday(day),
                        'profile-calendar__day--selected': isSelected(day),
                      }"
                      :aria-label="day.toLocaleDateString(locale, { dateStyle: 'full' })"
                      :aria-pressed="isSelected(day)"
                      @click="selectDate(day)"
                    >
                      {{ day.getDate() }}
                    </button>
                  </span>
                </div>
              </article>
            </div>

            <div class="availability-selection" :class="{ 'availability-selection--active': selectedDate }">
              <div>
                <small>{{ selectedDate ? copy.selected : copy.availability }}</small>
                <strong>{{ selectedDate ? selectedLabel : copy.selectPrompt }}</strong>
              </div>
              <a
                v-if="selectedDate"
                :href="appointmentUrl"
                target="_blank"
                rel="noopener noreferrer"
              >
                {{ copy.request }}<ChevronRight :size="17" />
              </a>
            </div>
        </section>
      </div>
  </main>
</template>
