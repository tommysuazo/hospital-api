<script setup>
import { Head } from '@inertiajs/vue3'
import ServicesDirectory from '../../../Components/services/ServicesDirectory.vue'
import { useLanguage } from '../../../Composables/useLanguage'
import ClientPortalLayout from '../../../Layouts/ClientPortalLayout.vue'

defineOptions({ layout: ClientPortalLayout })

defineProps({
  medicalServiceCategories: { type: Array, default: () => [] },
})

const { t } = useLanguage()
</script>

<template>
  <Head :title="t.servicesPage.title" />
  <ServicesDirectory :medical-service-categories="medicalServiceCategories" />
</template>
