<script setup>
import { Head } from '@inertiajs/vue3'
import DoctorsDirectory from '../../../Components/doctors/DoctorsDirectory.vue'
import ClientPortalLayout from '../../../Layouts/ClientPortalLayout.vue'

defineOptions({ layout: ClientPortalLayout })

defineProps({
  doctors: { type: Array, default: () => [] },
  medicalAreas: { type: Array, default: () => [] },
})
</script>

<template>
  <Head title="Doctores" />
  <DoctorsDirectory :doctors="doctors" :medical-areas="medicalAreas" />
</template>
