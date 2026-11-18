<script setup>
import { ref } from 'vue'
import StaffSidebar from '../Components/staff/StaffSidebar.vue'
import StaffTopbar from '../Components/staff/StaffTopbar.vue'
import '../../css/staff-portal.css'

const sidebarOpen = ref(false)
</script>

<template>
  <div class="staff-portal">
    <StaffSidebar :open="sidebarOpen" @close="sidebarOpen = false" />
    <div class="staff-portal__workspace">
      <StaffTopbar @menu="sidebarOpen = true" />
      <main class="staff-portal__main">
        <slot />
      </main>
    </div>
  </div>
</template>
