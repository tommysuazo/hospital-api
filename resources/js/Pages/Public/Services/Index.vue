<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { Stethoscope } from '@lucide/vue'
import { computed } from 'vue'
import { useLanguage } from '../../../Composables/useLanguage'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/services-page.css'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  medicalServiceCategories: { type: Array, default: () => [] },
})

const { language, t } = useLanguage()

const categories = computed(() => props.medicalServiceCategories.map((category) => ({
  id: category.id,
  slug: category.slug,
  name: category.names?.[language.value] || category.names?.es || category.slug,
  services: category.medicalServices.map((service) => {
    const translation = service.translations?.[language.value]
      || service.translations?.es
      || {}

    return {
      id: service.id,
      slug: service.slug,
      name: translation.name || service.slug,
      url: service.url,
    }
  }),
})))

function servicesCountLabel(count) {
  return count === 1
    ? t.value.servicesPage.availableService
    : t.value.servicesPage.availableServices
}
</script>

<template>
  <Head :title="t.servicesPage.title" />

  <main class="services-page">
    <section class="services-directory" aria-labelledby="services-page-title">
      <div class="services-directory__container">
        <header class="services-directory__header">
          <h1 id="services-page-title" class="services-directory__title">
            {{ t.servicesPage.title }}
          </h1>
          <p class="services-directory__introduction">
            {{ t.servicesPage.introduction }}
          </p>
        </header>

        <div v-if="categories.length" class="services-directory__grid">
          <article
            v-for="(category, index) in categories"
            :key="category.id"
            class="service-category"
            :aria-labelledby="'service-category-title-' + category.id"
          >
            <header class="service-category__header">
              <span class="service-category__icon" aria-hidden="true">
                <Stethoscope :size="21" />
              </span>

              <span class="service-category__heading">
                <small>{{ String(index + 1).padStart(2, '0') }}</small>
                <h2 :id="'service-category-title-' + category.id">
                  {{ category.name }}
                </h2>
              </span>

              <span class="service-category__count">
                <strong>{{ category.services.length }}</strong>
                {{ servicesCountLabel(category.services.length) }}
              </span>
            </header>

            <nav class="service-category__list" :aria-label="category.name">
              <Link
                v-for="service in category.services"
                :key="service.id"
                class="service-category__link"
                :href="service.url"
              >
                <span class="service-category__marker" aria-hidden="true"></span>
                <span>{{ service.name }}</span>
              </Link>
            </nav>
          </article>
        </div>

        <div v-else class="services-directory__empty">
          <span class="services-directory__empty-icon" aria-hidden="true">
            <Stethoscope :size="26" />
          </span>
          <h2>{{ t.servicesPage.emptyTitle }}</h2>
          <p>{{ t.servicesPage.emptyDescription }}</p>
        </div>
      </div>
    </section>
  </main>
</template>
