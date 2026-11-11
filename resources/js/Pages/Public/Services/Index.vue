<script setup>
import { Head } from '@inertiajs/vue3'
import ServicesDirectory from '../../../Components/services/ServicesDirectory.vue'
import { useLanguage } from '../../../Composables/useLanguage'
import PublicLayout from '../../../Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

defineProps({
  medicalServiceCategories: { type: Array, default: () => [] },
})

const { t } = useLanguage()
</script>

<template>
  <Head :title="t.servicesPage.title" />
  <main>
    <ServicesDirectory :medical-service-categories="medicalServiceCategories" />
  </main>
</template>
