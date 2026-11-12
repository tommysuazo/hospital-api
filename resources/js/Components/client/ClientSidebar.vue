<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { Activity, CalendarDays, FlaskConical, Headphones, HeartPulse, Home, Stethoscope, X } from '@lucide/vue'
import navLogo from '../../Assets/images/nav-logo.png'

defineProps({ open: { type: Boolean, default: false } })
const emit = defineEmits(['close', 'provisional'])
const page = usePage()

const mainNavigation = [
  { label: 'Inicio', icon: Home, href: '/portal' },
  { label: 'Doctores', icon: Stethoscope, href: '/portal/doctors' },
  { label: 'Servicios', icon: HeartPulse, href: '/portal/services' },
]

const navigation = [
  { label: 'Citas', icon: CalendarDays, href: '/portal/appointments' },
  { label: 'Resultados médicos', icon: FlaskConical },
  { label: 'Historial de actividades', icon: Activity },
]

function isActive(href) {
  return href === '/portal'
    ? page.url === '/portal' || page.url.startsWith('/portal?')
    : page.url === href || page.url.startsWith(`${href}/`) || page.url.startsWith(`${href}?`)
}
</script>

<template>
  <Transition name="portal-overlay">
    <button v-if="open" type="button" class="client-sidebar__overlay" aria-label="Cerrar menú" @click="emit('close')"></button>
  </Transition>

  <aside class="client-sidebar" :class="{ 'client-sidebar--open': open }">
    <div class="client-sidebar__brand">
      <Link href="/portal" class="client-sidebar__logo" aria-label="Portal Centro Médico Oriental" @click="emit('close')">
        <img :src="navLogo" alt="Centro Médico Oriental" />
      </Link>
      <button type="button" class="client-sidebar__close" aria-label="Cerrar menú" @click="emit('close')"><X :size="20" /></button>
    </div>

    <nav class="client-sidebar__nav" aria-label="Navegación del portal">
      <p class="client-sidebar__group-label">Menú</p>
      <ul>
        <li v-for="item in mainNavigation" :key="item.label">
          <Link
            :href="item.href"
            class="client-sidebar__item"
            :class="{ 'client-sidebar__item--active': isActive(item.href) }"
            :aria-current="isActive(item.href) ? 'page' : undefined"
            @click="emit('close')"
          >
            <component :is="item.icon" :size="19" /> <span>{{ item.label }}</span>
          </Link>
        </li>
      </ul>

      <p class="client-sidebar__group-label client-sidebar__group-label--secondary">Mi salud</p>
      <ul>
        <li v-for="item in navigation" :key="item.label">
          <Link
            v-if="item.href"
            :href="item.href"
            class="client-sidebar__item"
            :class="{ 'client-sidebar__item--active': isActive(item.href) }"
            :aria-current="isActive(item.href) ? 'page' : undefined"
            @click="emit('close')"
          >
            <component :is="item.icon" :size="19" /> <span>{{ item.label }}</span>
          </Link>
          <button v-else type="button" class="client-sidebar__item" @click="emit('provisional'); emit('close')">
            <component :is="item.icon" :size="19" /> <span>{{ item.label }}</span>
          </button>
        </li>
      </ul>
    </nav>

    <div class="client-sidebar__support">
      <span class="client-sidebar__support-icon"><Headphones :size="20" /></span>
      <div><strong>¿Necesitas ayuda?</strong><a href="tel:+18095562555">809-556-2555</a></div>
    </div>
  </aside>
</template>
