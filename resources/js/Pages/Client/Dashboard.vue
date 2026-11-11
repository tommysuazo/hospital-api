<script setup>
import { computed, inject } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { ArrowRight, CalendarCheck, CalendarPlus, CircleCheck, FlaskConical, HeartPulse } from '@lucide/vue'
import ClientPortalLayout from '../../Layouts/ClientPortalLayout.vue'

defineOptions({ layout: ClientPortalLayout })
const page = usePage()
const showPortalNotice = inject('showPortalNotice', () => {})
const firstName = computed(() => page.props.auth?.first_name || 'Paciente')
const today = new Intl.DateTimeFormat('es-DO', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(new Date())
</script>

<template>
  <Head title="Mi portal" />
  <section class="client-dashboard">
    <header class="client-dashboard__heading">
      <div>
        <nav aria-label="Migas de pan"><span>Mi portal</span><b>/</b><strong>Inicio</strong></nav>
        <h1>Hola, {{ firstName }}</h1>
      </div>
      <div class="client-dashboard__date"><HeartPulse :size="19" /><span><small>Hoy</small>{{ today }}</span></div>
    </header>

    <div class="client-dashboard__preview"><CircleCheck :size="18" /><p><strong>Vista preliminar del portal.</strong> Los módulos y acciones se conectarán progresivamente con tu información médica.</p></div>

    <div class="client-dashboard__grid client-dashboard__grid--primary">
      <article class="portal-panel portal-panel--appointments">
        <header class="portal-panel__header"><div><h2>Próximas citas</h2><p>Tus consultas programadas aparecerán aquí.</p></div><button type="button" @click="showPortalNotice()">Ver todas <ArrowRight :size="15" /></button></header>
        <div class="portal-empty-state"><span><CalendarCheck :size="27" /></span><h3>No tienes citas programadas</h3><p>Solicita una consulta con el especialista y la fecha que mejor se ajusten a tus necesidades.</p><Link href="/portal/appointments/create" class="portal-button portal-button--primary"><CalendarPlus :size="17" /> Solicitar cita</Link></div>
      </article>

      <aside class="portal-panel">
        <header class="portal-panel__header"><div><h2>Resultados recientes</h2><p>Estudios y análisis publicados para ti.</p></div><button type="button" @click="showPortalNotice()">Consultar <ArrowRight :size="15" /></button></header>
        <div class="portal-compact-empty"><span><FlaskConical :size="22" /></span><div><strong>No hay resultados nuevos</strong><p>Te notificaremos cuando un resultado esté disponible.</p></div></div>
      </aside>
    </div>

    <div class="client-dashboard__grid client-dashboard__grid--secondary">
      <article class="portal-panel">
        <header class="portal-panel__header"><div><h2>Actividad de la cuenta</h2><p>Movimientos recientes de tu portal.</p></div></header>
        <ul class="portal-activity">
          <li><span></span><div><strong>Sesión iniciada de forma segura</strong><small>Hoy · Verificación por correo completada</small></div></li>
          <li class="portal-activity__future"><span></span><div><strong>Tu historial comenzará aquí</strong><small>Las citas, documentos y resultados se mostrarán al habilitarse.</small></div></li>
        </ul>
      </article>
    </div>
  </section>
</template>
