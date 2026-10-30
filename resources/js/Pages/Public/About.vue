<script setup>
import { Head } from '@inertiajs/vue3'
import AboutPageIntro from '../../Components/about/AboutPageIntro.vue'
import FounderStory from '../../Components/about/FounderStory.vue'
import StaffSection from '../../Components/about/StaffSection.vue'
import { useLanguage } from '../../Composables/useLanguage'
import PublicLayout from '../../Layouts/PublicLayout.vue'
import '../../../css/about-page.css'

defineOptions({ layout: PublicLayout })

const { t } = useLanguage()
</script>

<template>
  <Head title="Nosotros" />
  <main>
      <section class="about-page-header">
        <div class="about-page-header__inner">
          <div class="about-page-header__copy">
            <span class="about-page-header__eyebrow">{{ t.aboutPage.eyebrow }}</span>
            <h1 class="about-page-header__title">{{ t.aboutPage.title }}</h1>
            <p>{{ t.aboutPage.description }}</p>
          </div>
          <div class="about-page-header__stats">
            <article v-for="stat in t.aboutPage.stats" :key="stat.label">
              <strong>{{ stat.value }}</strong><span>{{ stat.label }}</span>
            </article>
          </div>
        </div>
      </section>
      <AboutPageIntro />
      <FounderStory />
      <StaffSection />
  </main>
</template>
