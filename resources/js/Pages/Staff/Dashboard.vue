<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import {
  AlertTriangle,
  ArrowUpRight,
  CalendarCheck,
  CalendarClock,
  CalendarDays,
  ChevronRight,
  CircleUserRound,
  Clock3,
  FlaskConical,
  Stethoscope,
  Users,
} from '@lucide/vue'
import StaffStatCard from '../../Components/staff/StaffStatCard.vue'
import StaffLayout from '../../Layouts/StaffLayout.vue'

defineOptions({ layout: StaffLayout })
const props = defineProps({
  generatedAt: { type: String, required: true },
  todayLabel: { type: String, required: true },
  stats: { type: Object, required: true },
  weeklyAppointments: { type: Array, default: () => [] },
  statusDistribution: { type: Array, default: () => [] },
  upcomingAppointments: { type: Array, default: () => [] },
  recentResults: { type: Array, default: () => [] },
})

const page = usePage()
const firstName = computed(() => page.props.auth?.first_name || 'Administrador')
const totalStatuses = computed(() => props.statusDistribution.reduce((total, item) => total + item.count, 0))
const statusChart = computed(() => {
  if (!totalStatuses.value) return { background: '#eef2f6' }
  let accumulated = 0
  const segments = props.statusDistribution.map((item) => {
    const start = accumulated
    accumulated += (item.count / totalStatuses.value) * 100
    return `${item.color} ${start}% ${accumulated}%`
  })
  return { background: `conic-gradient(${segments.join(', ')})` }
})

const cards = computed(() => [
  { label: 'Citas de hoy', value: props.stats.appointmentsToday, icon: CalendarDays, tone: 'primary', detail: 'Hoy' },
  { label: 'Próximas citas', value: props.stats.upcomingAppointments, icon: CalendarClock, tone: 'sky', detail: 'Confirmadas' },
  { label: 'Pacientes activos', value: props.stats.activeClients, icon: Users, tone: 'green' },
  { label: 'Médicos activos', value: props.stats.activeDoctors, icon: Stethoscope, tone: 'violet' },
  { label: 'Resultados borrador', value: props.stats.draftResults, icon: FlaskConical, tone: 'amber', detail: 'Pendientes' },
  { label: 'Ausencias vigentes', value: props.stats.currentAlerts, icon: AlertTriangle, tone: 'rose', detail: 'Alertas' },
])

const statusLabels = { confirmed: 'Confirmada', completed: 'Completada', cancelled: 'Cancelada', no_show: 'No asistió' }
const resultLabels = { draft: 'Borrador', published: 'Publicado' }
</script>

<template>
  <Head title="Dashboard administrativo" />
  <section class="staff-dashboard">
    <header class="staff-page-heading">
      <div><nav><span>Administración</span><b>/</b><strong>Dashboard</strong></nav><h1>Hola, {{ firstName }}</h1><p>Este es el resumen operativo del Centro Médico Oriental.</p></div>
      <div class="staff-page-heading__date"><CalendarCheck :size="20" /><span><small>Hoy</small>{{ todayLabel }}</span></div>
    </header>

    <div class="staff-stat-grid">
      <StaffStatCard v-for="card in cards" :key="card.label" v-bind="card" />
    </div>

    <div class="staff-dashboard__grid staff-dashboard__grid--charts">
      <article class="staff-panel staff-panel--chart">
        <header class="staff-panel__header"><div><h2>Citas de la semana</h2><p>Actividad programada durante los próximos siete días.</p></div><span>Semana actual</span></header>
        <div class="staff-bar-chart" aria-label="Citas de la semana">
          <div v-for="day in weeklyAppointments" :key="day.date" class="staff-bar-chart__column">
            <strong>{{ day.count }}</strong><div><span :style="{ height: `${Math.max(day.percentage, day.count ? 8 : 2)}%` }"></span></div><small>{{ day.label }}</small>
          </div>
        </div>
      </article>

      <article class="staff-panel">
        <header class="staff-panel__header"><div><h2>Estado de citas</h2><p>Distribución histórica registrada.</p></div></header>
        <div class="staff-donut">
          <div class="staff-donut__chart" :style="statusChart"><span><strong>{{ totalStatuses }}</strong><small>Total</small></span></div>
          <ul><li v-for="item in statusDistribution" :key="item.status"><span><i :style="{ background: item.color }"></i>{{ item.label }}</span><strong>{{ item.count }}</strong></li></ul>
        </div>
      </article>
    </div>

    <div class="staff-dashboard__grid staff-dashboard__grid--content">
      <article class="staff-panel staff-panel--table">
        <header class="staff-panel__header"><div><h2>Próximas citas</h2><p>Consultas ordenadas por fecha y turno.</p></div><Link href="/staff/appointments">Ver todas <ArrowUpRight :size="15" /></Link></header>
        <div v-if="upcomingAppointments.length" class="staff-table-wrap">
          <table class="staff-table"><thead><tr><th>Paciente</th><th>Médico</th><th>Fecha</th><th>Turno</th><th>Estado</th></tr></thead><tbody>
            <tr v-for="appointment in upcomingAppointments" :key="appointment.uuid">
              <td><strong>{{ appointment.client }}</strong><small>{{ appointment.medicalArea }}</small></td>
              <td>{{ appointment.doctor || 'Por asignar' }}<small v-if="appointment.consultingRoom">Consultorio {{ appointment.consultingRoom }}</small></td>
              <td>{{ appointment.dateLabel }}</td><td>#{{ appointment.turnNumber }}</td>
              <td><span class="staff-status" :class="`staff-status--${appointment.status}`">{{ statusLabels[appointment.status] }}</span></td>
            </tr>
          </tbody></table>
        </div>
        <div v-else class="staff-empty"><CalendarDays :size="26" /><strong>No hay citas próximas</strong><p>Las citas programadas aparecerán aquí.</p></div>
      </article>

      <article class="staff-panel">
        <header class="staff-panel__header"><div><h2>Resultados recientes</h2><p>Últimos registros clínicos creados.</p></div><Link href="/staff/medical-results">Ver módulo <ChevronRight :size="15" /></Link></header>
        <ul v-if="recentResults.length" class="staff-result-list">
          <li v-for="result in recentResults" :key="result.uuid"><span class="staff-result-list__icon"><FlaskConical :size="18" /></span><div><strong>{{ result.title }}</strong><small>{{ result.client }} · {{ result.createdAtLabel }}</small></div><span class="staff-status" :class="`staff-status--${result.status}`">{{ resultLabels[result.status] }}</span></li>
        </ul>
        <div v-else class="staff-empty staff-empty--compact"><FlaskConical :size="25" /><strong>No hay resultados registrados</strong><p>Los resultados recientes aparecerán aquí.</p></div>
      </article>
    </div>

    <div class="staff-quick-access">
      <Link href="/staff/clients"><CircleUserRound :size="20" /><span><strong>Pacientes</strong><small>Consultar expedientes</small></span><ChevronRight :size="17" /></Link>
      <Link href="/staff/schedules"><Clock3 :size="20" /><span><strong>Horarios</strong><small>Revisar disponibilidad</small></span><ChevronRight :size="17" /></Link>
      <Link href="/staff/medical-results"><FlaskConical :size="20" /><span><strong>Resultados</strong><small>Gestionar publicaciones</small></span><ChevronRight :size="17" /></Link>
    </div>
  </section>
</template>
