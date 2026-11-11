<script setup>
import { provide, ref } from 'vue'
import ClientSidebar from '../Components/client/ClientSidebar.vue'
import ClientTopbar from '../Components/client/ClientTopbar.vue'
import { provideLanguage } from '../Composables/useLanguage'
import '../../css/client-portal.css'

provideLanguage()

const sidebarOpen = ref(false)
const notice = ref('')
let noticeTimer

function showPortalNotice(message = 'Esta función estará disponible próximamente.') {
  notice.value = message
  window.clearTimeout(noticeTimer)
  noticeTimer = window.setTimeout(() => { notice.value = '' }, 3200)
}

provide('showPortalNotice', showPortalNotice)
</script>

<template>
  <div class="client-portal">
    <ClientSidebar :open="sidebarOpen" @close="sidebarOpen = false" @provisional="showPortalNotice" />
    <div class="client-portal__workspace">
      <ClientTopbar @menu="sidebarOpen = true" @provisional="showPortalNotice" />
      <Transition name="portal-notice">
        <div v-if="notice" class="client-portal__notice" role="status">{{ notice }}</div>
      </Transition>
      <main class="client-portal__main">
        <slot />
      </main>
    </div>
  </div>
</template>
