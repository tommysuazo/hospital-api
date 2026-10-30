<script setup>
import staffManuel from '../../Assets/images/about/staff-manuel.jpg'
import staffCarla from '../../Assets/images/about/staff-carla.jpg'
import staffEduardo from '../../Assets/images/about/staff-eduardo.jpg'
import staffVanessa from '../../Assets/images/about/staff-vanessa.jpg'
import StaffCard from './StaffCard.vue'
import { useLanguage } from '../../Composables/useLanguage'

const { language, t } = useLanguage()

const staff = [
  { name: 'Dr. Manuel Peña', role: { es: 'Director General', en: 'General Director' }, photo: staffManuel },
  { name: 'Lic. Carla Fernández', role: { es: 'Gerente Administrativo', en: 'Administrative Manager' }, photo: staffCarla },
  { name: 'Lic. Eduardo Ramírez', role: { es: 'Contralor', en: 'Controller' }, photo: staffEduardo },
  { name: 'Lic. Vanessa Cruz', role: { es: 'Gerente de Recursos Humanos', en: 'Human Resources Manager' }, photo: staffVanessa },
]
</script>

<template>
  <section class="staff-list">
    <div class="staff-list__header">
      <span>{{ t.aboutPage.staffEyebrow }}</span>
      <h2 class="staff-list__title">{{ t.aboutPage.staffTitle }}</h2>
      <p>{{ t.aboutPage.staffDescription }}</p>
    </div>
    <div class="staff-list__grid">
      <StaffCard v-for="member in staff" :key="member.name" :member="{ ...member, role: member.role[language] }" />
    </div>
  </section>
</template>
