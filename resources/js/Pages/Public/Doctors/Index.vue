<script setup>
import { Head } from '@inertiajs/vue3'
import DoctorsDirectory from '../../../Components/doctors/DoctorsDirectory.vue'
import PublicLayout from '../../../Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

defineProps({
  doctors: { type: Array, default: () => [] },
  specialties: { type: Array, default: () => [] },
})
</script>

<template>
  <Head title="Doctores" />
  <DoctorsDirectory :doctors="doctors" :specialties="specialties" />
</template>
