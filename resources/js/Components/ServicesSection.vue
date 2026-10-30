<script setup>
import { computed } from 'vue'
import { ArrowRight, Clock } from '@lucide/vue'
import { useLanguage } from '../Composables/useLanguage'
import doctorIcon from '../Assets/images/icons/doctor.svg?raw'
import radiographyIcon from '../Assets/images/icons/radiography.svg?raw'
import sonographyIcon from '../Assets/images/icons/sonography.svg?raw'
import colonoscopyIcon from '../Assets/images/icons/colonoscopy.svg?raw'
import emergencyIcon from '../Assets/images/icons/emergency.svg?raw'

const props = defineProps({
  procedures: { type: Array, default: () => [] },
})

const { language, t } = useLanguage()
const serviceIcons = [doctorIcon, radiographyIcon, sonographyIcon, colonoscopyIcon, emergencyIcon]
const services = computed(() => {
  if (!props.procedures.length) return t.value.services.items

  return props.procedures.map((procedure) => {
    const translation = procedure.translations[language.value] || procedure.translations.es || {}

    return {
      name: translation.name,
      desc: translation.short_description,
      schedule: '',
    }
  })
})
</script>

<template>
  <section id="services" class="services">
    <div class="section-heading"><h2 class="section-title">{{ t.services.title }}</h2></div>
    <div class="services__grid">
      <article
        v-for="(service, index) in services"
        :key="service.name"
        class="service-card"
        :class="{ 'service-card--emergency': service.available }"
      >
        <div class="service-card__heading">
          <span class="service-card__icon">
            <span class="service-card__custom-icon" v-html="serviceIcons[index % serviceIcons.length]"></span>
          </span>
          <h3>{{ service.name }}</h3>
        </div>
        <p>{{ service.desc }}</p>
        <span v-if="service.schedule" class="service-card__schedule"><Clock :size="14" />{{ service.schedule }}</span>
        <button v-if="index !== 0 && !service.available" type="button" class="service-card__action">
          {{ t.services.viewMore }}<ArrowRight :size="15" />
        </button>
      </article>
    </div>
  </section>
</template>

<style scoped>
.services { padding: 6rem 2rem; background: var(--color-white); }
.services__grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1.75rem; max-width: var(--container-width); margin-inline: auto; }
.service-card { grid-column: span 2; display: flex; flex-direction: column; padding: 2rem; border: 2px solid var(--color-border-strong); border-radius: var(--radius-card); background: var(--color-white); box-shadow: var(--shadow-sm); transition: var(--transition); }
.service-card:nth-child(4) { grid-column: 2 / 4; }
.service-card:nth-child(5) { grid-column: 4 / 6; }
.service-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.service-card--emergency { background: rgba(43, 174, 120, 0.04); box-shadow: 0 0 0 1px rgba(43, 174, 120, 0.3), var(--shadow-sm); }
.service-card__heading { display: flex; align-items: center; gap: 0.9rem; margin-bottom: 0.5rem; }
.service-card__icon { display: grid; place-items: center; width: 50px; height: 50px; flex: 0 0 auto; border-radius: 50%; color: var(--color-accent); background: rgba(43, 174, 120, 0.1); }
.service-card__custom-icon { display: grid; place-items: center; }
.service-card__custom-icon :deep(svg) { width: 2.5rem; height: 2.5rem; }
.service-card h3 { margin: 0; color: var(--color-primary); font-size: 1.1rem; font-weight: 600; }
.service-card p { flex-grow: 1; margin: 0 0 0.5rem; color: var(--color-text-muted); font-size: 0.92rem; }
.service-card__schedule { display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 1rem; color: var(--color-text-muted); font-size: 0.85rem; font-weight: 600; }
.service-card__action { display: inline-flex; align-items: center; align-self: flex-start; gap: 0.4rem; padding: 0; border: 0; color: var(--color-primary); background: transparent; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: var(--transition); }
.service-card__action:hover { gap: 0.6rem; color: var(--color-accent); }

@media (max-width: 1024px) {
  .services__grid { grid-template-columns: repeat(2, 1fr); }
  .service-card, .service-card:nth-child(4), .service-card:nth-child(5) { grid-column: span 1; }
}
@media (max-width: 768px) { .services { padding: 5rem 1.5rem; } .services__grid { grid-template-columns: 1fr; } }
@media (max-width: 480px) { .services { padding-inline: 1rem; } .service-card { padding: 1.5rem; } }
</style>
