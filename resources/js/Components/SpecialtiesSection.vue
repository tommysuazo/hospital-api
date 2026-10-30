<script setup>
import { Baby, Heart, Stethoscope } from '@lucide/vue'
import { computed } from 'vue'
import dermatologyIcon from '../Assets/images/icons/dermatologia.svg?raw'
import endocrinologyIcon from '../Assets/images/icons/endocrinologia.svg?raw'
import gastroenterologyIcon from '../Assets/images/icons/gastroenterologia.svg?raw'
import geriatricsIcon from '../Assets/images/icons/geriatria.svg?raw'
import gynecologyIcon from '../Assets/images/icons/ginecologia.svg?raw'
import hepatologyIcon from '../Assets/images/icons/hepatologia.svg?raw'
import obstetricsIcon from '../Assets/images/icons/Obstetricia.svg?raw'
import psychologyIcon from '../Assets/images/icons/psicologia.svg?raw'
import urologyIcon from '../Assets/images/icons/urologia.svg?raw'
import { useLanguage } from '../Composables/useLanguage'

const props = defineProps({
  specialties: { type: Array, default: () => [] },
})

const { language, t } = useLanguage()
const specialtyNames = computed(() => props.specialties.length
  ? props.specialties.map((specialty) => specialty.names[language.value] || specialty.names.es || specialty.slug)
  : t.value.specialties.items)
const icons = [
  Stethoscope,
  Baby,
  gynecologyIcon,
  obstetricsIcon,
  Heart,
  psychologyIcon,
  urologyIcon,
  dermatologyIcon,
  gastroenterologyIcon,
  hepatologyIcon,
  endocrinologyIcon,
  geriatricsIcon,
]
</script>

<template>
  <section id="specialties" class="specialties">
    <div class="section-heading specialties__heading">
      <h2 class="section-title specialties__title">{{ t.specialties.title }}</h2>
    </div>
    <div class="specialties__grid">
      <button v-for="(specialty, index) in specialtyNames" :key="specialty" type="button" class="specialty-button">
        <span
          v-if="typeof icons[index % icons.length] === 'string'"
          class="specialty-button__custom-icon"
          aria-hidden="true"
          v-html="icons[index % icons.length]"
        ></span>
        <component :is="icons[index % icons.length]" v-else :size="22" />
        <span>{{ specialty }}</span>
      </button>
    </div>
  </section>
</template>

<style scoped>
.specialties { padding: 6rem 2rem; background: var(--color-specialties); }
.specialties__heading { margin-bottom: 3.5rem; }
.specialties__title { color: var(--color-white); }
.specialties__grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; max-width: var(--container-width); margin-inline: auto; }
.specialty-button { display: flex; align-items: center; justify-content: center; gap: 0.9rem; width: 15rem; max-width: 100%; padding: 1rem; border: 0; border-radius: var(--radius-md); color: var(--color-white); background: var(--color-primary); box-shadow: var(--shadow-sm); text-align: center; cursor: pointer; transition: var(--transition); }
.specialty-button svg { flex: 0 0 auto; color: var(--color-accent); }
.specialty-button__custom-icon { display: inline-flex; width: 32px; height: 32px; flex: 0 0 auto; color: var(--color-accent); }
.specialty-button__custom-icon :deep(svg) { width: 32px; height: 32px; }
.specialty-button__custom-icon :deep(path) { fill: currentColor; }
.specialty-button span { min-width: 0; font-family: var(--font-heading); font-size: 1rem; font-weight: 600; overflow-wrap: anywhere; }
.specialty-button:hover { transform: translateY(-1px); box-shadow: var(--shadow-md); }
@media (max-width: 768px) { .specialties { padding: 5rem 1.5rem; } }
@media (max-width: 570px) { .specialties { padding-inline: 1rem; } .specialty-button { width: 100%; } }
</style>
