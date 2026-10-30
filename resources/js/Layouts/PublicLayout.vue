<script setup>
import AppFooter from '../Components/AppFooter.vue'
import AppHeader from '../Components/AppHeader.vue'
import { provideLanguage } from '../Composables/useLanguage'

provideLanguage()
</script>

<template>
  <div class="public-layout">
    <AppHeader />
    <slot />
    <AppFooter />
  </div>
</template>
