<script setup>
import { Quote } from '@lucide/vue'
import founderPhoto from '../../Assets/images/about/founder.jpg'
import { useLanguage } from '../../Composables/useLanguage'

const { t } = useLanguage()
</script>

<template>
  <section class="founder-story">
    <div class="founder-story__grid">
      <div class="founder-story__image">
        <img :src="founderPhoto" alt="Dr. Rafael Polanco" class="founder-story__photo" />
        <span class="founder-story__since">{{ t.aboutPage.founderSince }}</span>
      </div>
      <div class="founder-story__content">
        <span class="founder-story__eyebrow">{{ t.aboutPage.founderEyebrow }}</span>
        <h2 class="founder-story__title">{{ t.aboutPage.founderName }}</h2>
        <p class="founder-story__highlight">
          <strong>{{ t.aboutPage.founderHighlight }}</strong>
        </p>
        <p v-for="paragraph in t.aboutPage.founderParagraphs" :key="paragraph" class="founder-story__text">{{ paragraph }}</p>
        <blockquote class="founder-story__quote">
          <Quote :size="25" class="founder-story__quote-icon" />
          <p class="founder-story__quote-text">{{ t.aboutPage.founderQuote }}</p>
        </blockquote>
      </div>
    </div>
  </section>
</template>
