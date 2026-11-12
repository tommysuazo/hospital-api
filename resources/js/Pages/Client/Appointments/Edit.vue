<script setup>
import { computed, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { CalendarDays, Check, CircleAlert, Clock3, MapPin, Pencil, ShieldCheck, Stethoscope, UserRound } from '@lucide/vue'
import ClientPortalLayout from '../../../Layouts/ClientPortalLayout.vue'
import { useLanguage } from '../../../Composables/useLanguage'
import '../../../../css/appointment-create.css'

defineOptions({ layout: ClientPortalLayout })

const props = defineProps({
  appointment: { type: Object, required: true },
  doctors: { type: Array, default: () => [] },
  minimumDate: { type: String, required: true },
})

const { language } = useLanguage()
const form = useForm({
  medical_area_slug: props.appointment.medicalArea.slug,
  doctor_uuid: props.appointment.doctor.uuid,
  appointment_date: props.appointment.date,
  reason: props.appointment.reason || '',
})
const jsDays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']
const dayLabels = { monday: 'lunes', tuesday: 'martes', wednesday: 'miércoles', thursday: 'jueves', friday: 'viernes', saturday: 'sábado', sunday: 'domingo' }

const medicalAreas = computed(() => {
  const areas = new Map()
  props.doctors.forEach((doctor) => doctor.medicalAreas.forEach((area) => areas.set(area.slug, area)))
  return [...areas.values()]
})
const availableDoctors = computed(() => props.doctors.filter((doctor) => !form.medical_area_slug || doctor.medicalAreas.some((area) => area.slug === form.medical_area_slug)))
const selectedDoctor = computed(() => props.doctors.find((doctor) => doctor.uuid === form.doctor_uuid))
const selectedArea = computed(() => medicalAreas.value.find((area) => area.slug === form.medical_area_slug))
const dateAvailability = computed(() => {
  if (!form.appointment_date || !selectedDoctor.value) return null
  const [year, month, day] = form.appointment_date.split('-').map(Number)
  const dayName = jsDays[new Date(year, month - 1, day, 12).getDay()]
  if (!selectedDoctor.value.schedules.some((schedule) => schedule.dayOfWeek === dayName)) return { valid: false, message: 'El doctor no consulta ese día de la semana.' }
  if (selectedDoctor.value.unavailabilities.some((period) => form.appointment_date >= period.startsOn && form.appointment_date <= period.endsOn)) return { valid: false, message: 'El doctor no está disponible en esa fecha.' }
  return { valid: true, message: 'La fecha coincide con el horario habitual del doctor.' }
})
const canSubmit = computed(() => form.medical_area_slug && form.doctor_uuid && form.appointment_date && dateAvailability.value?.valid && !form.processing)

watch(() => form.medical_area_slug, () => {
  if (!availableDoctors.value.some((doctor) => doctor.uuid === form.doctor_uuid)) form.doctor_uuid = ''
})

function areaName(area) { return area.names?.[language.value] || area.names?.es || area.slug }
function selectDoctor(doctor) { form.doctor_uuid = doctor.uuid; form.clearErrors('doctor_uuid', 'appointment') }
function formatTime(time) { const [hour, minute] = time.split(':').map(Number); return new Intl.DateTimeFormat('es-DO', { hour: 'numeric', minute: '2-digit' }).format(new Date(2026, 0, 1, hour, minute)) }
function submit() { form.put(`/portal/appointments/${props.appointment.uuid}`, { preserveScroll: true }) }
</script>

<template>
  <Head title="Editar cita" />
  <section class="appointment-create">
    <header class="appointment-create__heading">
      <div><nav aria-label="Migas de pan"><Link href="/portal">Mi portal</Link><span>/</span><Link href="/portal/appointments">Historial</Link><span>/</span><strong>Editar cita</strong></nav><span class="appointment-create__eyebrow"><Pencil :size="16" /> Cita pendiente</span><h1>Edita tu cita</h1><p>Puedes cambiar el área, especialista, fecha o motivo mientras la cita se mantenga pendiente.</p></div>
      <div class="appointment-create__security"><ShieldCheck :size="20" /><span><strong>Cambio protegido</strong><small>Validaremos nuevamente la disponibilidad</small></span></div>
    </header>

    <div v-if="form.errors.appointment" class="appointment-feedback appointment-feedback--error" role="alert"><CircleAlert :size="20" /><div><strong>No pudimos actualizar la cita</strong><p>{{ form.errors.appointment }}</p></div></div>

    <form class="appointment-create__layout" @submit.prevent="submit">
      <div class="appointment-form">
        <section class="appointment-step">
          <header class="appointment-step__header"><span>1</span><div><h2>Área médica</h2><p>Selecciona el tipo de atención.</p></div></header>
          <label class="appointment-field"><span>Área</span><select v-model="form.medical_area_slug" required><option v-for="area in medicalAreas" :key="area.slug" :value="area.slug">{{ areaName(area) }}</option></select><small v-if="form.errors.medical_area_slug" class="appointment-field__error">{{ form.errors.medical_area_slug }}</small></label>
        </section>

        <section class="appointment-step">
          <header class="appointment-step__header"><span>2</span><div><h2>Especialista</h2><p>Conserva o cambia el doctor de la cita.</p></div></header>
          <div class="appointment-doctors"><button v-for="doctor in availableDoctors" :key="doctor.uuid" type="button" class="appointment-doctor" :class="{ 'appointment-doctor--selected': form.doctor_uuid === doctor.uuid }" @click="selectDoctor(doctor)"><img v-if="doctor.photo" :src="doctor.photo" :alt="doctor.name" /><span v-else class="appointment-doctor__placeholder"><UserRound :size="24" /></span><span class="appointment-doctor__identity"><strong>{{ doctor.name }}</strong><small><MapPin :size="13" /> Consultorio {{ doctor.room }}</small></span><span class="appointment-doctor__check"><Check :size="15" /></span></button></div>
          <small v-if="form.errors.doctor_uuid" class="appointment-field__error">{{ form.errors.doctor_uuid }}</small>
        </section>

        <section class="appointment-step">
          <header class="appointment-step__header"><span>3</span><div><h2>Fecha de consulta</h2><p>Elige una fecha compatible con el horario del doctor.</p></div></header>
          <div class="appointment-date-grid"><label class="appointment-field"><span>Fecha</span><span class="appointment-field__control"><CalendarDays :size="18" /><input v-model="form.appointment_date" type="date" :min="minimumDate" required /></span><small v-if="form.errors.appointment_date" class="appointment-field__error">{{ form.errors.appointment_date }}</small></label><div class="appointment-schedule"><span><Clock3 :size="17" /> Horario habitual</span><template v-if="selectedDoctor"><small v-for="schedule in selectedDoctor.schedules" :key="`${schedule.dayOfWeek}-${schedule.startTime}`">{{ dayLabels[schedule.dayOfWeek] }} · {{ formatTime(schedule.startTime) }}–{{ formatTime(schedule.endTime) }}</small></template><p v-else>Selecciona un doctor.</p></div></div>
          <p v-if="dateAvailability" class="appointment-date-status" :class="{ 'appointment-date-status--valid': dateAvailability.valid }"><CircleAlert :size="17" />{{ dateAvailability.message }}</p>
        </section>

        <section class="appointment-step"><header class="appointment-step__header"><span>4</span><div><h2>Motivo de la consulta</h2><p>Actualiza la información que recibirá el especialista.</p></div></header><label class="appointment-field appointment-field--reason"><span>Motivo <small>Opcional</small></span><textarea v-model.trim="form.reason" rows="4" maxlength="1000"></textarea><span class="appointment-field__counter">{{ form.reason.length }}/1000</span><small v-if="form.errors.reason" class="appointment-field__error">{{ form.errors.reason }}</small></label></section>
      </div>

      <aside class="appointment-summary"><span class="appointment-summary__icon"><Stethoscope :size="22" /></span><h2>Resumen actualizado</h2><dl><div><dt>Área médica</dt><dd>{{ selectedArea ? areaName(selectedArea) : 'Por seleccionar' }}</dd></div><div><dt>Doctor</dt><dd>{{ selectedDoctor?.name || 'Por seleccionar' }}</dd></div><div><dt>Fecha</dt><dd>{{ form.appointment_date }}</dd></div><div><dt>Turno actual</dt><dd>#{{ appointment.turnNumber }}</dd></div></dl><p><ShieldCheck :size="16" /> Si cambias doctor o fecha, el sistema asignará un nuevo número de turno.</p><button type="submit" :disabled="!canSubmit"><Pencil :size="17" />{{ form.processing ? 'Guardando…' : 'Guardar cambios' }}</button><Link href="/portal/appointments" style="display:block;margin-top:.8rem;color:#657186;font-size:.7rem;text-align:center;text-decoration:none">Cancelar</Link></aside>
    </form>
  </section>
</template>
