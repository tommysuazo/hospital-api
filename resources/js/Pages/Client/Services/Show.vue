<script setup>
import ClientPortalLayout from '../../../Layouts/ClientPortalLayout.vue'
import ServiceDetail from '../../Public/Services/Show.vue'

defineOptions({ layout: ClientPortalLayout })

defineProps({
  medicalService: { type: Object, required: true },
})
</script>

<template>
  <ServiceDetail
    :medical-service="medicalService"
    back-href="/portal/services"
    embedded
  />
</template>
