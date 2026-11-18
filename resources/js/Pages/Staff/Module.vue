<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import {
  Activity, ArrowRight, Building2, CalendarClock, CalendarDays, FileText, FlaskConical,
  KeyRound, ScrollText, Settings, Shapes, ShieldCheck, Stethoscope, UserCog, Users,
} from '@lucide/vue'
import StaffLayout from '../../Layouts/StaffLayout.vue'

defineOptions({ layout: StaffLayout })
const props = defineProps({ module: { type: Object, required: true } })
const icons = {
  appointments: CalendarDays, clients: Users, doctors: Stethoscope, 'medical-results': FlaskConical,
  schedules: CalendarClock, 'medical-areas': Shapes, 'medical-services': Activity, facilities: Building2,
  insurance: ShieldCheck, documents: FileText, users: UserCog, roles: KeyRound, audit: ScrollText, settings: Settings,
}
const icon = computed(() => icons[props.module.key] || Activity)
</script>

<template>
  <Head :title="module.title" />
  <section class="staff-module-page">
    <header class="staff-page-heading"><div><nav><Link href="/staff">Administración</Link><b>/</b><strong>{{ module.title }}</strong></nav><h1>{{ module.title }}</h1><p>{{ module.description }}</p></div></header>
    <article class="staff-module-state">
      <span><component :is="icon" :size="31" /></span>
      <small>Módulo disponible en navegación</small>
      <h2>{{ module.title }}</h2>
      <p>La estructura visual y la ruta ya están preparadas. Las operaciones, formularios y tablas se incorporarán en la siguiente etapa del módulo.</p>
      <div><ShieldCheck :size="18" /><span><strong>Preparado para permisos</strong><small>La navegación se restringirá por rol cuando se habiliten los demás usuarios.</small></span></div>
      <Link href="/staff">Volver al dashboard <ArrowRight :size="16" /></Link>
    </article>
  </section>
</template>
