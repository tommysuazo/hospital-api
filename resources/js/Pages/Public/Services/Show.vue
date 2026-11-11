<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { ArrowLeft, CheckCircle2, Stethoscope } from '@lucide/vue'
import { computed } from 'vue'
import { useLanguage } from '../../../Composables/useLanguage'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/service-detail.css'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  medicalService: { type: Object, required: true },
  backHref: { type: String, default: '/services' },
  embedded: { type: Boolean, default: false },
})

const { language, t } = useLanguage()

const service = computed(() => {
  const translation = props.medicalService.translations?.[language.value]
    || props.medicalService.translations?.es
    || {}

  return {
    name: translation.name || props.medicalService.slug,
    introduction: translation.short_description || t.value.servicesPage.detail.genericIntroduction,
    description: translation.description || t.value.servicesPage.detail.genericDescription,
    category: props.medicalService.category?.names?.[language.value]
      || props.medicalService.category?.names?.es
      || props.medicalService.category?.slug,
  }
})
</script>

<template>
  <Head :title="service.name" />

  <component :is="embedded ? 'div' : 'main'" class="service-detail">
    <div class="service-detail__container">
      <Link class="service-detail__back" :href="backHref">
        <ArrowLeft :size="17" aria-hidden="true" />
        {{ t.servicesPage.detail.back }}
      </Link>

      <section class="service-detail__hero" aria-labelledby="service-detail-title">
        <div class="service-detail__hero-icon" aria-hidden="true">
          <Stethoscope :size="30" />
        </div>
        <div>
          <span class="service-detail__eyebrow">
            {{ service.category || t.servicesPage.detail.eyebrow }}
          </span>
          <h1 id="service-detail-title">{{ service.name }}</h1>
          <p>{{ service.introduction }}</p>
        </div>
      </section>

      <div class="service-detail__grid">
        <section class="service-detail__panel">
          <span class="service-detail__panel-label">{{ t.servicesPage.detail.eyebrow }}</span>
          <h2>{{ t.servicesPage.detail.aboutTitle }}</h2>
          <p>{{ service.description }}</p>
        </section>

        <aside class="service-detail__panel service-detail__panel--accent">
          <h2>{{ t.servicesPage.detail.beforeTitle }}</h2>
          <p>{{ t.servicesPage.detail.beforeDescription }}</p>
          <ul>
            <li v-for="step in t.servicesPage.detail.steps" :key="step">
              <CheckCircle2 :size="17" aria-hidden="true" />
              <span>{{ step }}</span>
            </li>
          </ul>
        </aside>
      </div>

      <section class="service-detail__help">
        <div>
          <h2>{{ t.servicesPage.detail.helpTitle }}</h2>
          <p>{{ t.servicesPage.detail.helpDescription }}</p>
        </div>
        <Link href="/contact">{{ t.servicesPage.detail.contact }}</Link>
      </section>
    </div>
  </component>
</template>
