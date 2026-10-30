<script setup>
import { Head } from '@inertiajs/vue3'
import AboutSection from '../../Components/AboutSection.vue'
import ContactSection from '../../Components/ContactSection.vue'
import HeroSection from '../../Components/HeroSection.vue'
import InsuranceSection from '../../Components/InsuranceSection.vue'
import ServicesSection from '../../Components/ServicesSection.vue'
import SpecialtiesSection from '../../Components/SpecialtiesSection.vue'
import TestimonialsSection from '../../Components/TestimonialsSection.vue'
import PublicLayout from '../../Layouts/PublicLayout.vue'

defineOptions({ layout: PublicLayout })

defineProps({
  specialties: { type: Array, default: () => [] },
  procedures: { type: Array, default: () => [] },
})
</script>

<template>
  <Head title="Inicio" />
  <main>
    <HeroSection />
    <ServicesSection :procedures="procedures" />
    <SpecialtiesSection :specialties="specialties" />
    <InsuranceSection />
    <AboutSection />
    <TestimonialsSection />
    <ContactSection />
  </main>
</template>
