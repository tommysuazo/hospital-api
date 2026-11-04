<script setup>
import {
  Baby,
  HeartPulse,
  Stethoscope,
} from '@lucide/vue'
import { computed } from 'vue'
import maternityImage from '../Assets/images/maternity/mother-and-newborn.png'
import { useLanguage } from '../Composables/useLanguage'
import colonoscopyIcon from '../Assets/images/icons/colonoscopy.svg?raw'
import emergenciesIcon from '../Assets/images/icons/emergencies.svg?raw'
import generalConsultationIcon from '../Assets/images/icons/general-consultation.svg?raw'
import ultrasoundIcon from '../Assets/images/icons/ultrasound.svg?raw'
import xRayIcon from '../Assets/images/icons/x-ray.svg?raw'

const props = defineProps({
  procedures: { type: Array, default: () => [] },
})

const { language, t } = useLanguage()
const fixedServiceSlugs = ['general-consultation', 'emergencies']
const serviceIcons = {
  'general-consultation': generalConsultationIcon,
  emergencies: emergenciesIcon,
  'x-ray': xRayIcon,
  radiography: xRayIcon,
  ultrasound: ultrasoundIcon,
  colonoscopy: colonoscopyIcon,
}

const benefitIcons = [HeartPulse, Baby]

const supportingServices = computed(() => {
  const defaultServices = t.value.services.items
  const defaultServicesBySlug = new Map(defaultServices.map((service) => [service.slug, service]))
  const fixedServices = fixedServiceSlugs
    .map((slug) => defaultServicesBySlug.get(slug))
    .filter(Boolean)

  const procedures = props.procedures.length
    ? props.procedures.map((procedure) => {
        const translation = procedure.translations?.[language.value]
          || procedure.translations?.es
          || {}
        const defaultService = defaultServicesBySlug.get(procedure.slug)

        return {
          slug: procedure.slug,
          name: translation.name || defaultService?.name || procedure.slug,
          desc: translation.short_description || defaultService?.desc || '',
          schedule: defaultService?.schedule || '',
        }
      })
    : defaultServices.filter((service) => !fixedServiceSlugs.includes(service.slug))

  return [...fixedServices, ...procedures]
})
</script>

<template>
  <section id="services" class="maternity-services">
    <div class="maternity-services__container">
      <div class="maternity-spotlight">
        <div class="maternity-spotlight__grid">
          <figure class="maternity-visual__photo">
            <img :src="maternityImage" :alt="t.services.maternity.imageAlt" loading="lazy" />
          </figure>

          <div class="maternity-copy">
            <header class="maternity-spotlight__header">
              <h2>{{ t.services.maternity.title }}</h2>
            </header>

            <p class="maternity-copy__lead">{{ t.services.maternity.lead }}</p>

            <div class="maternity-benefits">
              <article
                v-for="(benefit, index) in t.services.maternity.benefits"
                :key="benefit.title"
                class="maternity-benefit"
              >
                <span class="maternity-benefit__icon">
                  <component :is="benefitIcons[index]" :size="21" aria-hidden="true" />
                </span>
                <span>
                  <strong>{{ benefit.title }}</strong>
                  <small>{{ benefit.description }}</small>
                </span>
              </article>
            </div>
          </div>
        </div>
      </div>

      <div class="supporting-services">
        <header class="supporting-services__header">
          <h3>{{ t.services.otherTitle }}</h3>
        </header>

        <div class="supporting-services__grid">
          <article
            v-for="service in supportingServices"
            :key="service.slug"
            class="supporting-service"
          >
            <span class="supporting-service__icon">
              <span
                v-if="serviceIcons[service.slug]"
                class="supporting-service__custom-icon"
                v-html="serviceIcons[service.slug]"
              ></span>
              <Stethoscope v-else :size="24" aria-hidden="true" />
            </span>
            <span class="supporting-service__copy">
              <strong>{{ service.name }}</strong>
              <small v-if="service.desc">{{ service.desc }}</small>
              <small v-if="service.schedule" class="supporting-service__schedule">{{ service.schedule }}</small>
            </span>
          </article>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.maternity-services {
  position: relative;
  overflow: hidden;
  padding: 2rem 2rem 4rem 2rem;
  background: linear-gradient(180deg, #f7fafc 0%, #ffffff 46%, #f7fafc 100%);
}

.maternity-services__container {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: var(--container-width);
  margin-inline: auto;
}

.maternity-spotlight {
  width: min(100%, 1080px);
  margin-inline: auto;
  padding: clamp(1.25rem, 2.2vw, 1.8rem);
}

.maternity-spotlight__header {
  margin-bottom: 0.85rem;
}

.maternity-spotlight__header h2 {
  margin: 0;
  color: #172d5a;
  font-size: clamp(1.55rem, 2.4vw, 2rem);
  font-weight: 700;
  letter-spacing: -0.035em;
  line-height: 1.14;
  text-wrap: balance;
}

.maternity-spotlight__grid {
  display: grid;
  grid-template-columns: minmax(260px, 0.8fr) minmax(0, 1.2fr);
  gap: clamp(1.5rem, 3vw, 2.5rem);
  align-items: center;
}

.maternity-visual__photo {
  position: relative;
  width: 100%;
  height: clamp(250px, 25vw, 310px);
  margin: 0;
  overflow: hidden;
  border-radius: 18px;
  background: #eaf0f6;
}

.maternity-visual__photo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transition: transform 700ms ease;
}

.maternity-copy__lead {
  margin: 0 0 1rem;
  color: #2f3c51;
  font-size: 0.92rem;
  line-height: 1.55;
}

.maternity-benefits {
  display: flex;
  flex-direction: column;
  gap: 0.65rem;
}

.maternity-benefit {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  padding: 0.8rem;
  border: 1px solid transparent;
  border-radius: 16px;
  background: #f5f8fc;
  transition: var(--transition);
}

.maternity-benefit:hover {
  border-color: rgba(43, 174, 120, 0.18);
  background: #f0f8f5;
  transform: translateX(3px);
}

.maternity-benefit__icon {
  display: grid;
  place-items: center;
  width: 42px;
  height: 42px;
  flex: 0 0 auto;
  border-radius: 12px;
  color: var(--color-white);
  background: var(--color-primary);
}

.maternity-benefit strong,
.maternity-benefit small { display: block; }

.maternity-benefit strong {
  margin-bottom: 0.2rem;
  color: #172d5a;
  font-size: 0.88rem;
  line-height: 1.4;
}

.maternity-benefit small {
  color: var(--color-text-muted);
  font-size: 0.75rem;
  line-height: 1.5;
}

.supporting-services { margin-top: 2rem; }

.supporting-services__header {
  margin-bottom: 1.75rem;
}

.supporting-services__header h3 {
  margin: 0;
  color: #172d5a;
  font-size: clamp(1.55rem, 2.8vw, 2.15rem);
  line-height: 1.2;
}

.supporting-services__grid {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 0.8rem;
}

.supporting-service {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  min-width: 0;
  padding: 1rem;
  border: 1px solid var(--color-border);
  border-radius: 15px;
  background: var(--color-white);
  box-shadow: var(--shadow-sm);
  transition: var(--transition);
}

.supporting-service:hover {
  border-color: rgba(43, 174, 120, 0.38);
  box-shadow: 0 12px 30px -23px rgba(17, 43, 99, 0.65);
  transform: translateY(-2px);
}

.supporting-service__icon {
  display: grid;
  place-items: center;
  width: 38px;
  height: 38px;
  flex: 0 0 auto;
  border-radius: 11px;
  color: var(--color-accent-dark);
  background: rgba(43, 174, 120, 0.1);
}

.supporting-service__custom-icon { display: grid; place-items: center; }
.supporting-service__custom-icon :deep(svg) { width: 27px; height: 27px; }

.supporting-service__copy { min-width: 0; }
.supporting-service__copy strong,
.supporting-service__copy small { display: block; }

.supporting-service__copy strong {
  color: #172d5a;
  font-size: 0.86rem;
  line-height: 1.35;
}

.supporting-service__copy small {
  display: -webkit-box;
  margin-top: 0.28rem;
  overflow: hidden;
  color: var(--color-text-muted);
  font-size: 0.7rem;
  line-height: 1.45;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
}

.supporting-service__schedule { color: var(--color-accent-dark) !important; font-weight: 700; }

@media (max-width: 1180px) {
  .supporting-services__grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}

@media (max-width: 900px) {
  .maternity-spotlight__grid {
    grid-template-columns: minmax(230px, 0.85fr) minmax(0, 1.15fr);
    gap: 1.5rem;
  }
}

@media (max-width: 768px) {
  .maternity-services { padding: 3.5rem 1.5rem; }
  .maternity-spotlight { border-radius: 26px; }
  .maternity-spotlight__grid { grid-template-columns: 1fr; }
  .maternity-copy { max-width: 560px; margin-inline: auto; }
  .maternity-visual__photo { width: min(100%, 560px); height: min(66vw, 340px); margin-inline: auto; }
  .supporting-services__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 560px) {
  .maternity-services { padding-inline: 1rem; }
  .maternity-spotlight { padding: 1.25rem; border-radius: 22px; }
  .maternity-spotlight__grid { gap: 1.5rem; }
  .maternity-visual__photo { width: 100%; height: min(75vw, 340px); border-radius: 16px; }
  .supporting-services { margin-top: 3.5rem; }
  .supporting-services__grid { grid-template-columns: 1fr; }
}

@media (prefers-reduced-motion: reduce) {
  .maternity-benefit,
  .supporting-service { transition: none; }
}
</style>
