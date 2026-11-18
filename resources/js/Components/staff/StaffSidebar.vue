<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import {
  Activity,
  Building2,
  CalendarClock,
  CalendarDays,
  FileText,
  FlaskConical,
  KeyRound,
  LayoutDashboard,
  ScrollText,
  Settings,
  Shapes,
  ShieldCheck,
  Stethoscope,
  UserCog,
  Users,
  X,
} from '@lucide/vue'
import navLogo from '../../Assets/images/nav-logo.png'

defineProps({ open: { type: Boolean, default: false } })
const emit = defineEmits(['close'])
const page = usePage()

const groups = [
  {
    label: 'Operación',
    items: [
      { label: 'Dashboard', href: '/staff', icon: LayoutDashboard },
      { label: 'Citas', href: '/staff/appointments', icon: CalendarDays },
      { label: 'Pacientes', href: '/staff/clients', icon: Users },
      { label: 'Médicos', href: '/staff/doctors', icon: Stethoscope },
      { label: 'Resultados médicos', href: '/staff/medical-results', icon: FlaskConical },
      { label: 'Horarios', href: '/staff/schedules', icon: CalendarClock },
    ],
  },
  {
    label: 'Recursos',
    items: [
      { label: 'Áreas médicas', href: '/staff/medical-areas', icon: Shapes },
      { label: 'Servicios', href: '/staff/medical-services', icon: Activity },
      { label: 'Instalaciones', href: '/staff/facilities', icon: Building2 },
      { label: 'Seguros', href: '/staff/insurance', icon: ShieldCheck },
      { label: 'Documentos', href: '/staff/documents', icon: FileText },
    ],
  },
  {
    label: 'Sistema',
    items: [
      { label: 'Personal', href: '/staff/users', icon: UserCog },
      { label: 'Roles y permisos', href: '/staff/roles', icon: KeyRound },
      { label: 'Auditoría', href: '/staff/audit', icon: ScrollText },
      { label: 'Configuración', href: '/staff/settings', icon: Settings },
    ],
  },
]

function isActive(href) {
  return href === '/staff'
    ? page.url === '/staff' || page.url.startsWith('/staff?')
    : page.url === href || page.url.startsWith(`${href}/`) || page.url.startsWith(`${href}?`)
}
</script>

<template>
  <button v-if="open" type="button" class="staff-sidebar__overlay" aria-label="Cerrar menú" @click="emit('close')"></button>
  <aside class="staff-sidebar" :class="{ 'staff-sidebar--open': open }">
    <div class="staff-sidebar__brand">
      <Link href="/staff" class="staff-sidebar__logo" aria-label="Panel administrativo" @click="emit('close')">
        <img :src="navLogo" alt="Centro Médico Oriental" />
      </Link>
      <span>Portal administrativo</span>
      <button type="button" class="staff-sidebar__close" aria-label="Cerrar menú" @click="emit('close')"><X :size="20" /></button>
    </div>

    <nav class="staff-sidebar__nav" aria-label="Navegación administrativa">
      <section v-for="group in groups" :key="group.label" class="staff-sidebar__group">
        <p>{{ group.label }}</p>
        <ul>
          <li v-for="item in group.items" :key="item.href">
            <Link
              :href="item.href"
              class="staff-sidebar__item"
              :class="{ 'staff-sidebar__item--active': isActive(item.href) }"
              :aria-current="isActive(item.href) ? 'page' : undefined"
              @click="emit('close')"
            >
              <component :is="item.icon" :size="18" />
              <span>{{ item.label }}</span>
            </Link>
          </li>
        </ul>
      </section>
    </nav>

    <div class="staff-sidebar__footer"><span>CMO Admin</span><small>Versión inicial</small></div>
  </aside>
</template>
