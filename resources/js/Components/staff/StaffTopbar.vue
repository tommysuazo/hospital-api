<script setup>
import { computed, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Bell, ChevronDown, LogOut, Menu, Settings, ShieldCheck, UserRound } from '@lucide/vue'

const emit = defineEmits(['menu'])
const page = usePage()
const accountMenuOpen = ref(false)
const account = computed(() => page.props.auth ?? {})
const fullName = computed(() => [account.value.first_name, account.value.last_name].filter(Boolean).join(' ') || 'Administrador')
const initials = computed(() => fullName.value.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase())
const primaryRole = computed(() => account.value.roles?.[0]?.replaceAll('-', ' ') || 'Personal')

function logout() {
  router.post('/auth/logout')
}
</script>

<template>
  <header class="staff-topbar">
    <button type="button" class="staff-topbar__menu" aria-label="Abrir menú" @click="emit('menu')"><Menu :size="21" /></button>
    <div class="staff-topbar__context"><span>Portal institucional</span><strong>Administración</strong></div>

    <div class="staff-topbar__actions">
      <button type="button" class="staff-topbar__notification" aria-label="Notificaciones"><Bell :size="20" /><span></span></button>
      <div class="staff-topbar__account">
        <button type="button" class="staff-topbar__account-button" :aria-expanded="accountMenuOpen" @click="accountMenuOpen = !accountMenuOpen">
          <span class="staff-topbar__avatar">{{ initials }}</span>
          <span class="staff-topbar__identity"><strong>{{ fullName }}</strong><small>{{ primaryRole }}</small></span>
          <ChevronDown :size="16" />
        </button>
        <div v-if="accountMenuOpen" class="staff-topbar__dropdown">
          <div class="staff-topbar__role"><ShieldCheck :size="17" /><span><small>Sesión activa como</small><strong>{{ primaryRole }}</strong></span></div>
          <hr />
          <button type="button"><UserRound :size="17" /> Mi perfil</button>
          <button type="button" @click="router.visit('/staff/settings')"><Settings :size="17" /> Configuración</button>
          <hr />
          <button type="button" class="staff-topbar__logout" @click="logout"><LogOut :size="17" /> Cerrar sesión</button>
        </div>
      </div>
    </div>
  </header>
</template>
