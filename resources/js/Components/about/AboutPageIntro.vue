<script setup>
import { computed } from 'vue'
import { Eye, HeartHandshake, Target } from '@lucide/vue'
import { useLanguage } from '../../Composables/useLanguage'

const { t } = useLanguage()
const icons = [Eye, Target, HeartHandshake]
const items = computed(() => t.value.aboutPage.principles.map((item, index) => ({ ...item, icon: icons[index] })))
</script>

<template>
  <section class="about-page-intro">
    <div class="about-page-intro__container">
      <div class="about-page-intro__heading">
        <span>{{ t.aboutPage.principlesEyebrow }}</span>
        <h2>{{ t.aboutPage.principlesTitle }}</h2>
        <p>{{ t.aboutPage.principlesDescription }}</p>
      </div>
      <div class="about-page-intro__grid">
      <article v-for="(item, index) in items" :key="item.title" class="about-page-intro__card">
        <span class="about-page-intro__number">0{{ index + 1 }}</span>
        <div class="about-page-intro__header">
          <span class="about-page-intro__icon"><component :is="item.icon" :size="22" /></span>
          <h2 class="about-page-intro__title">{{ item.title }}</h2>
        </div>
        <p class="about-page-intro__text">{{ item.text }}</p>
      </article>
      </div>
    </div>
  </section>
</template>
