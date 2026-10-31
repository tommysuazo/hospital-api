<script setup>
import { Baby, Heart, Stethoscope } from '@lucide/vue'
import { computed } from 'vue'
import dermatologyIcon from '../Assets/images/icons/dermatology.svg?raw'
import endocrinologyIcon from '../Assets/images/icons/endocrinology.svg?raw'
import gastroenterologyIcon from '../Assets/images/icons/gastroenterology.svg?raw'
import geriatricsIcon from '../Assets/images/icons/geriatrics.svg?raw'
import gynecologyIcon from '../Assets/images/icons/gynecology.svg?raw'
import hepatologyIcon from '../Assets/images/icons/hepatology.svg?raw'
import obstetricsIcon from '../Assets/images/icons/obstetrics.svg?raw'
import psychologyIcon from '../Assets/images/icons/psychology.svg?raw'
import urologyIcon from '../Assets/images/icons/urology.svg?raw'
import { useLanguage } from '../Composables/useLanguage'

const props = defineProps({
  medicalAreas: { type: Array, default: () => [] },
})

const { language, t } = useLanguage()
const defaultMedicalAreaSlugs = [
  'general-medicine',
  'pediatrics',
  'gynecology',
  'obstetrics',
  'cardiology',
  'psychology',
  'urology',
  'dermatology',
  'gastroenterology',
  'hepatology',
  'endocrinology',
  'geriatrics',
]
const medicalAreaItems = computed(() => props.medicalAreas.length
  ? props.medicalAreas.map((medicalArea) => ({
      slug: medicalArea.slug,
      name: medicalArea.names[language.value] || medicalArea.names.es || medicalArea.slug,
    }))
  : t.value.medicalAreas.items.map((name, index) => ({
      slug: defaultMedicalAreaSlugs[index],
      name,
    })))
const iconsBySlug = {
  'general-medicine': Stethoscope,
  pediatrics: Baby,
  gynecology: gynecologyIcon,
  obstetrics: obstetricsIcon,
  cardiology: Heart,
  psychology: psychologyIcon,
  urology: urologyIcon,
  dermatology: dermatologyIcon,
  gastroenterology: gastroenterologyIcon,
  hepatology: hepatologyIcon,
  endocrinology: endocrinologyIcon,
  geriatrics: geriatricsIcon,
}

const iconFor = (slug) => iconsBySlug[slug] || Stethoscope
</script>

<template>
  <section id="medical-areas" class="medical-areas">
    <div class="section-heading medical-areas__heading">
      <h2 class="section-title medical-areas__title">{{ t.medicalAreas.title }}</h2>
    </div>
    <div class="medical-areas__grid">
      <button v-for="medicalArea in medicalAreaItems" :key="medicalArea.slug" type="button" class="medical-area-button">
        <span
          v-if="typeof iconFor(medicalArea.slug) === 'string'"
          class="medical-area-button__custom-icon"
          aria-hidden="true"
          v-html="iconFor(medicalArea.slug)"
        ></span>
        <component :is="iconFor(medicalArea.slug)" v-else :size="22" />
        <span>{{ medicalArea.name }}</span>
      </button>
    </div>
  </section>
</template>

<style scoped>
.medical-areas { padding: 6rem 2rem; background: var(--color-medical-areas); }
.medical-areas__heading { margin-bottom: 3.5rem; }
.medical-areas__title { color: var(--color-white); }
.medical-areas__grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; max-width: var(--container-width); margin-inline: auto; }
.medical-area-button { display: flex; align-items: center; justify-content: center; gap: 0.9rem; width: 15rem; max-width: 100%; padding: 1rem; border: 0; border-radius: var(--radius-md); color: var(--color-white); background: var(--color-primary); box-shadow: var(--shadow-sm); text-align: center; cursor: pointer; transition: var(--transition); }
.medical-area-button svg { flex: 0 0 auto; color: var(--color-accent); }
.medical-area-button__custom-icon { display: inline-flex; width: 32px; height: 32px; flex: 0 0 auto; color: var(--color-accent); }
.medical-area-button__custom-icon :deep(svg) { width: 32px; height: 32px; }
.medical-area-button__custom-icon :deep(path) { fill: currentColor; }
.medical-area-button span { min-width: 0; font-family: var(--font-heading); font-size: 1rem; font-weight: 600; overflow-wrap: anywhere; }
.medical-area-button:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
@media (max-width: 768px) { .medical-areas { padding: 5rem 1.5rem; } }
@media (max-width: 570px) { .medical-areas { padding-inline: 1rem; } .medical-area-button { width: 100%; } }
</style>
