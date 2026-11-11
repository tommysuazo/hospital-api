<script setup>
import { computed, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Bell, ChevronDown, LogOut, Menu, Settings, UserRound } from '@lucide/vue'
import rdFlag from '../../Assets/images/flags/rd_flag.png'
import usFlag from '../../Assets/images/flags/us_flag.png'
import { useLanguage } from '../../Composables/useLanguage'

const emit = defineEmits(['menu', 'provisional'])
const page = usePage()
const accountMenuOpen = ref(false)
const { language, setLanguage } = useLanguage()
const languageOptions = [
  { code: 'es', label: 'Español', flag: rdFlag },
  { code: 'en', label: 'English', flag: usFlag },
]
const account = computed(() => page.props.auth ?? {})
const fullName = computed(() => [account.value.first_name, account.value.last_name].filter(Boolean).join(' ') || 'Paciente')
const initials = computed(() => fullName.value.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase())

function logout() {
  router.post('/auth/logout')
}
</script>

<template>
  <header class="client-topbar">
    <button type="button" class="client-topbar__menu" aria-label="Abrir menú" @click="emit('menu')"><Menu :size="21" /></button>

    <div class="client-topbar__actions">
      <div class="language-selector" role="group" aria-label="Seleccionar idioma">
        <button
          v-for="option in languageOptions"
          :key="option.code"
          type="button"
          class="language-selector__option"
          :class="{ 'language-selector__option--active': language === option.code }"
          :aria-label="option.label"
          :aria-pressed="language === option.code"
          @click="setLanguage(option.code)"
        >
          <img class="language-selector__flag" :src="option.flag" alt="" aria-hidden="true" />
          <span>{{ option.code.toUpperCase() }}</span>
        </button>
      </div>
      <button type="button" class="client-topbar__notification" aria-label="Notificaciones" @click="emit('provisional')">
        <Bell :size="20" /><span></span>
      </button>
      <div class="client-topbar__account">
        <button type="button" class="client-topbar__account-button" :aria-expanded="accountMenuOpen" @click="accountMenuOpen = !accountMenuOpen">
          <span class="client-topbar__avatar">{{ initials }}</span>
          <span class="client-topbar__identity"><strong>{{ fullName }}</strong><small>Paciente</small></span>
          <ChevronDown :size="16" />
        </button>
        <div v-if="accountMenuOpen" class="client-topbar__dropdown">
          <button type="button" @click="emit('provisional'); accountMenuOpen = false"><UserRound :size="17" /> Mi perfil</button>
          <button type="button" @click="emit('provisional'); accountMenuOpen = false"><Settings :size="17" /> Configuración</button>
          <hr />
          <button type="button" class="client-topbar__logout" @click="logout"><LogOut :size="17" /> Cerrar sesión</button>
        </div>
      </div>
    </div>
  </header>
</template>
