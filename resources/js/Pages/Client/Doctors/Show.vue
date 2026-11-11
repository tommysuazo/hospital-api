<script setup>
import ClientPortalLayout from '../../../Layouts/ClientPortalLayout.vue'
import DoctorProfile from '../../Public/Doctors/Show.vue'

defineOptions({ layout: ClientPortalLayout })

defineProps({
  doctor: { type: Object, required: true },
})
</script>

<template>
  <DoctorProfile :doctor="doctor" back-href="/portal/doctors" embedded />
</template>
