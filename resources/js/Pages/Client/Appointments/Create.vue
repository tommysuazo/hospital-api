<script setup>
import { computed, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import {
  CalendarDays,
  CalendarPlus,
  Check,
  CircleAlert,
  CircleCheck,
  Clock3,
  MapPin,
  ShieldCheck,
  Stethoscope,
  UserRound,
  UsersRound,
} from '@lucide/vue'
import ClientPortalLayout from '../../../Layouts/ClientPortalLayout.vue'
import { useLanguage } from '../../../Composables/useLanguage'
import '../../../../css/appointment-create.css'

defineOptions({ layout: ClientPortalLayout })

const props = defineProps({
  doctors: { type: Array, default: () => [] },
  appointmentForOptions: { type: Array, default: () => [] },
  minimumDate: { type: String, required: true },
  selectedDoctorUuid: { type: String, default: '' },
  status: { type: String, default: '' },
})

const { language } = useLanguage()
const initialDoctor = props.doctors.find((doctor) => doctor.uuid === props.selectedDoctorUuid)
const form = useForm({
  medical_area_slug: initialDoctor?.medicalAreas?.[0]?.slug || '',
  doctor_uuid: initialDoctor?.uuid || '',
  appointment_date: '',
  appointment_for: 'self',
  reason: '',
})

const dayLabels = {
  monday: 'lunes',
  tuesday: 'martes',
  wednesday: 'miércoles',
  thursday: 'jueves',
  friday: 'viernes',
  saturday: 'sábado',
  sunday: 'domingo',
}
const jsDays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']

const medicalAreas = computed(() => {
  const areas = new Map()

  props.doctors.forEach((doctor) => doctor.medicalAreas.forEach((area) => {
    if (!areas.has(area.slug)) areas.set(area.slug, area)
  }))

  return [...areas.values()].sort((areaA, areaB) => (
    areaName(areaA).localeCompare(areaName(areaB), language.value === 'en' ? 'en' : 'es')
  ))
})

const availableDoctors = computed(() => props.doctors.filter((doctor) => (
  !form.medical_area_slug
    || doctor.medicalAreas.some((area) => area.slug === form.medical_area_slug)
)))

const selectedDoctor = computed(() => props.doctors.find(
  (doctor) => doctor.uuid === form.doctor_uuid
))

const selectedArea = computed(() => medicalAreas.value.find(
  (area) => area.slug === form.medical_area_slug
))

const appointmentForSummary = computed(() => (
  form.appointment_for === 'self' ? 'Mí' : 'Dependiente'
))

const scheduleDays = computed(() => [...new Set(
  (selectedDoctor.value?.schedules || []).map((schedule) => schedule.dayOfWeek)
)])

const dateAvailability = computed(() => {
  if (!form.appointment_date || !selectedDoctor.value) return null

  const [year, month, day] = form.appointment_date.split('-').map(Number)
  const selectedDate = new Date(year, month - 1, day, 12)
  const selectedDay = jsDays[selectedDate.getDay()]
  const hasSchedule = selectedDoctor.value.schedules.some(
    (schedule) => schedule.dayOfWeek === selectedDay
  )

  if (!hasSchedule) {
    return { valid: false, message: 'El doctor no consulta ese día de la semana.' }
  }

  const isUnavailable = selectedDoctor.value.unavailabilities.some((period) => (
    form.appointment_date >= period.startsOn && form.appointment_date <= period.endsOn
  ))

  if (isUnavailable) {
    return { valid: false, message: 'El doctor no está disponible en esa fecha.' }
  }

  return { valid: true, message: 'La fecha coincide con el horario habitual del doctor.' }
})

const canSubmit = computed(() => (
  form.medical_area_slug
  && form.doctor_uuid
  && form.appointment_date
  && form.appointment_for
  && dateAvailability.value?.valid
  && !form.processing
))

watch(() => form.medical_area_slug, () => {
  if (!availableDoctors.value.some((doctor) => doctor.uuid === form.doctor_uuid)) {
    form.doctor_uuid = ''
    form.appointment_date = ''
  }
})

watch(() => form.doctor_uuid, () => {
  form.appointment_date = ''
})

function areaName(area) {
  return area.names?.[language.value] || area.names?.es || area.slug
}

function selectDoctor(doctor) {
  form.doctor_uuid = doctor.uuid

  if (!doctor.medicalAreas.some((area) => area.slug === form.medical_area_slug)) {
    form.medical_area_slug = doctor.medicalAreas[0]?.slug || ''
  }

  form.clearErrors('doctor_uuid', 'appointment_date', 'appointment')
}

function formatTime(time) {
  const [hour, minute] = time.split(':').map(Number)
  return new Intl.DateTimeFormat('es-DO', {
    hour: 'numeric',
    minute: '2-digit',
  }).format(new Date(2026, 0, 1, hour, minute))
}

function submit() {
  form.post('/portal/appointments', {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}
</script>

<template>
  <Head title="Solicitar cita" />

  <section class="appointment-create">
    <header class="appointment-create__heading">
      <div>
        <nav aria-label="Migas de pan">
          <Link href="/portal">Mi portal</Link><span>/</span><strong>Solicitar cita</strong>
        </nav>
        <span class="appointment-create__eyebrow"><CalendarPlus :size="16" /> Nueva cita</span>
        <h1>Programa tu próxima consulta</h1>
        <p>Selecciona el área, el especialista y una fecha dentro de su horario habitual.</p>
      </div>
      <div class="appointment-create__security"><ShieldCheck :size="20" /><span><strong>Solicitud segura</strong><small>Tus datos se mantienen protegidos</small></span></div>
    </header>

    <div v-if="status" class="appointment-feedback appointment-feedback--success" role="status">
      <CircleCheck :size="20" /><div><strong>Cita creada correctamente</strong><p>{{ status }}</p></div>
    </div>

    <div v-if="form.errors.appointment" class="appointment-feedback appointment-feedback--error" role="alert">
      <CircleAlert :size="20" /><div><strong>No pudimos confirmar la cita</strong><p>{{ form.errors.appointment }}</p></div>
    </div>

    <form v-if="doctors.length" class="appointment-create__layout" @submit.prevent="submit">
      <div class="appointment-form">
        <section class="appointment-step">
          <header class="appointment-step__header"><span>1</span><div><h2>Área médica</h2><p>¿Qué tipo de atención necesitas?</p></div></header>
          <label class="appointment-field">
            <span>Selecciona un área</span>
            <select v-model="form.medical_area_slug" name="medical_area_slug" required @change="form.clearErrors('medical_area_slug', 'appointment')">
              <option disabled value="">Selecciona una opción</option>
              <option v-for="area in medicalAreas" :key="area.slug" :value="area.slug">{{ areaName(area) }}</option>
            </select>
            <small v-if="form.errors.medical_area_slug" class="appointment-field__error">{{ form.errors.medical_area_slug }}</small>
          </label>
        </section>

        <section class="appointment-step">
          <header class="appointment-step__header"><span>2</span><div><h2>Especialista</h2><p>Elige el doctor con quien deseas consultar.</p></div></header>
          <div v-if="availableDoctors.length" class="appointment-doctors">
            <button
              v-for="doctor in availableDoctors"
              :key="doctor.uuid"
              type="button"
              class="appointment-doctor"
              :class="{ 'appointment-doctor--selected': form.doctor_uuid === doctor.uuid }"
              :aria-pressed="form.doctor_uuid === doctor.uuid"
              @click="selectDoctor(doctor)"
            >
              <img v-if="doctor.photo" :src="doctor.photo" :alt="doctor.name" />
              <span v-else class="appointment-doctor__placeholder"><UserRound :size="24" /></span>
              <span class="appointment-doctor__identity"><strong>{{ doctor.name }}</strong><small><MapPin :size="13" /> Consultorio {{ doctor.room }}</small></span>
              <span class="appointment-doctor__check"><Check :size="15" /></span>
            </button>
          </div>
          <p v-else class="appointment-step__empty">No hay doctores disponibles para el área seleccionada.</p>
          <small v-if="form.errors.doctor_uuid" class="appointment-field__error">{{ form.errors.doctor_uuid }}</small>
        </section>

        <section class="appointment-step">
          <header class="appointment-step__header"><span>3</span><div><h2>Fecha de consulta</h2><p>Solo podrás confirmar fechas incluidas en el horario del doctor.</p></div></header>
          <div class="appointment-date-grid">
            <label class="appointment-field">
              <span>Fecha</span>
              <span class="appointment-field__control"><CalendarDays :size="18" /><input v-model="form.appointment_date" name="appointment_date" type="date" :min="minimumDate" :disabled="!selectedDoctor" required @change="form.clearErrors('appointment_date', 'appointment')" /></span>
              <small v-if="form.errors.appointment_date" class="appointment-field__error">{{ form.errors.appointment_date }}</small>
            </label>
            <div class="appointment-schedule" :class="{ 'appointment-schedule--empty': !selectedDoctor }">
              <span><Clock3 :size="17" /> Horario habitual</span>
              <template v-if="selectedDoctor">
                <p>{{ scheduleDays.map((day) => dayLabels[day]).join(', ') }}</p>
                <small v-for="schedule in selectedDoctor.schedules" :key="`${schedule.dayOfWeek}-${schedule.startTime}`">
                  {{ dayLabels[schedule.dayOfWeek] }} · {{ formatTime(schedule.startTime) }}–{{ formatTime(schedule.endTime) }}
                </small>
              </template>
              <p v-else>Selecciona un doctor para consultar sus días disponibles.</p>
            </div>
          </div>
          <p v-if="dateAvailability" class="appointment-date-status" :class="{ 'appointment-date-status--valid': dateAvailability.valid }">
            <CircleCheck v-if="dateAvailability.valid" :size="17" /><CircleAlert v-else :size="17" />{{ dateAvailability.message }}
          </p>
        </section>

        <section class="appointment-step">
          <header class="appointment-step__header"><span>4</span><div><h2>Detalles de la cita</h2><p>Indica para quién es y comparte información útil para la consulta.</p></div></header>
          <div class="appointment-for" role="radiogroup" aria-label="Para quién es la cita">
            <label v-for="option in appointmentForOptions" :key="option.value" :class="{ 'appointment-for__option--selected': form.appointment_for === option.value }">
              <input v-model="form.appointment_for" type="radio" name="appointment_for" :value="option.value" />
              <UserRound v-if="option.value === 'self'" :size="20" /><UsersRound v-else :size="20" />
              <span><strong>{{ option.label }}</strong><small>{{ option.value === 'self' ? 'La consulta será para ti.' : 'El centro confirmará los datos del dependiente.' }}</small></span>
            </label>
          </div>
          <small v-if="form.errors.appointment_for" class="appointment-field__error">{{ form.errors.appointment_for }}</small>

          <label class="appointment-field appointment-field--reason">
            <span>Motivo de la consulta <small>Opcional</small></span>
            <textarea v-model.trim="form.reason" name="reason" rows="4" maxlength="1000" placeholder="Describe brevemente el motivo o los síntomas principales…"></textarea>
            <span class="appointment-field__counter">{{ form.reason.length }}/1000</span>
            <small v-if="form.errors.reason" class="appointment-field__error">{{ form.errors.reason }}</small>
          </label>
        </section>
      </div>

      <aside class="appointment-summary">
        <span class="appointment-summary__icon"><Stethoscope :size="22" /></span>
        <h2>Resumen de tu cita</h2>
        <dl>
          <div><dt>Área médica</dt><dd>{{ selectedArea ? areaName(selectedArea) : 'Por seleccionar' }}</dd></div>
          <div><dt>Doctor</dt><dd>{{ selectedDoctor?.name || 'Por seleccionar' }}</dd></div>
          <div><dt>Fecha</dt><dd>{{ form.appointment_date || 'Por seleccionar' }}</dd></div>
          <div><dt>Para</dt><dd>{{ appointmentForSummary }}</dd></div>
        </dl>
        <p><ShieldCheck :size="16" /> El turno se asignará automáticamente al confirmar.</p>
        <button type="submit" :disabled="!canSubmit">
          <CalendarPlus :size="18" />{{ form.processing ? 'Confirmando…' : 'Confirmar cita' }}
        </button>
      </aside>
    </form>

    <div v-else class="appointment-empty">
      <span><Stethoscope :size="28" /></span><h2>No hay especialistas disponibles</h2><p>En este momento no encontramos doctores con horarios activos para solicitar una cita.</p><Link href="/portal/doctors">Consultar directorio</Link>
    </div>
  </section>
</template>
