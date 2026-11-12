<script setup>
import { reactive } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { CalendarDays, CalendarPlus, CircleCheck, MapPin, Pencil, RotateCcw, Search, Stethoscope, UserRound } from '@lucide/vue'
import ClientPortalLayout from '../../../Layouts/ClientPortalLayout.vue'
import '../../../../css/appointment-history.css'

defineOptions({ layout: ClientPortalLayout })

const props = defineProps({
  appointments: { type: Object, required: true },
  doctors: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
  statusOptions: { type: Array, default: () => [] },
  status: { type: String, default: '' },
})

const form = reactive({
  date: props.filters.date || '',
  doctor: props.filters.doctor || '',
  status: props.filters.status || '',
})

const statusLabels = Object.fromEntries(props.statusOptions.map((option) => [option.value, option.label]))

function applyFilters(page = 1) {
  router.get('/portal/appointments', { ...form, page }, { preserveState: true, preserveScroll: true, replace: true })
}

function clearFilters() {
  form.date = ''
  form.doctor = ''
  form.status = ''
  applyFilters()
}

function formatDate(date) {
  return new Intl.DateTimeFormat('es-DO', { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'UTC' })
    .format(new Date(`${date}T12:00:00Z`))
}
</script>

<template>
  <Head title="Historial de citas" />

  <section class="appointment-history">
    <header class="appointment-history__heading">
      <div>
        <nav aria-label="Migas de pan"><Link href="/portal">Mi portal</Link><span>/</span><strong>Historial de citas</strong></nav>
        <span class="appointment-history__eyebrow"><CalendarDays :size="16" /> Mis citas</span>
        <h1>Historial de citas</h1>
        <p>Consulta tus citas, encuentra una por fecha, doctor o estado y gestiona las que aún están pendientes.</p>
      </div>
      <Link href="/portal/appointments/create" class="appointment-history__new"><CalendarPlus :size="18" /> Solicitar cita</Link>
    </header>

    <div v-if="status" class="appointment-feedback appointment-feedback--success" role="status">
      <CircleCheck :size="20" /><div><strong>Cita actualizada</strong><p>{{ status }}</p></div>
    </div>

    <form class="appointment-filters" @submit.prevent="applyFilters()">
      <label><span>Fecha</span><input v-model="form.date" type="date" /></label>
      <label><span>Doctor</span><select v-model="form.doctor"><option value="">Todos los doctores</option><option v-for="doctor in doctors" :key="doctor.uuid" :value="doctor.uuid">{{ doctor.name }}</option></select></label>
      <label><span>Estado</span><select v-model="form.status"><option value="">Todos los estados</option><option v-for="option in statusOptions" :key="option.value" :value="option.value">{{ option.label }}</option></select></label>
      <div class="appointment-filters__actions"><button type="submit"><Search :size="16" /> Filtrar</button><button type="button" class="appointment-filters__clear" @click="clearFilters"><RotateCcw :size="15" /> Limpiar</button></div>
    </form>

    <div class="appointment-history__summary"><strong>{{ appointments.total }}</strong> {{ appointments.total === 1 ? 'cita encontrada' : 'citas encontradas' }}</div>

    <div v-if="appointments.data.length" class="appointment-list">
      <article v-for="appointment in appointments.data" :key="appointment.uuid" class="appointment-card">
        <div class="appointment-card__date"><CalendarDays :size="21" /><span>{{ formatDate(appointment.date) }}</span><small>Turno #{{ appointment.turnNumber }}</small></div>
        <div class="appointment-card__doctor">
          <img v-if="appointment.doctor.photo" :src="appointment.doctor.photo" :alt="appointment.doctor.name" />
          <span v-else><UserRound :size="22" /></span>
          <div><strong>{{ appointment.doctor.name }}</strong><small><Stethoscope :size="13" /> {{ appointment.medicalArea.name }}</small><small v-if="appointment.doctor.room"><MapPin :size="13" /> Consultorio {{ appointment.doctor.room }}</small></div>
        </div>
        <div class="appointment-card__details"><span>Para</span><strong>{{ appointment.appointmentFor === 'self' ? 'Mí' : 'Dependiente' }}</strong><p v-if="appointment.reason">{{ appointment.reason }}</p></div>
        <div class="appointment-card__state"><span class="appointment-status" :class="`appointment-status--${appointment.status}`">{{ statusLabels[appointment.status] }}</span><Link v-if="appointment.canEdit" :href="`/portal/appointments/${appointment.uuid}/edit`"><Pencil :size="15" /> Editar cita</Link><small v-else>Esta cita ya no admite cambios.</small></div>
      </article>
    </div>

    <div v-else class="appointment-history__empty"><span><CalendarDays :size="28" /></span><h2>No encontramos citas</h2><p>Ajusta los filtros o solicita una nueva cita con uno de nuestros especialistas.</p><button v-if="form.date || form.doctor || form.status" type="button" @click="clearFilters">Quitar filtros</button><Link v-else href="/portal/appointments/create">Solicitar cita</Link></div>

    <nav v-if="appointments.lastPage > 1" class="appointment-pagination" aria-label="Paginación">
      <button v-for="page in appointments.lastPage" :key="page" type="button" :class="{ active: page === appointments.currentPage }" :aria-current="page === appointments.currentPage ? 'page' : undefined" @click="applyFilters(page)">{{ page }}</button>
    </nav>
  </section>
</template>
