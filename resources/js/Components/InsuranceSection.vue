<script setup>
import { insurers } from '../Data/siteContent'
import { useLanguage } from '../Composables/useLanguage'

const { t } = useLanguage()
const imageModules = import.meta.glob('../Assets/images/insurance/*', { eager: true, import: 'default' })
const imageByName = Object.fromEntries(Object.entries(imageModules).map(([path, url]) => [path.split('/').pop(), url]))
</script>

<template>
  <section id="insurance" class="insurance">
    <div class="section-heading insurance__heading">
      <h2 class="section-title insurance__title">{{ t.insurance.title }}</h2>
    </div>
    <div class="insurance__grid">
      <div v-for="insurer in insurers" :key="insurer.name" class="insurance__item">
        <img :src="imageByName[insurer.logo]" :alt="insurer.name" />
      </div>
    </div>
  </section>
</template>

<style scoped>
.insurance { padding: 5rem 2rem 6rem; background: var(--color-background-light); }
.insurance__heading { margin-bottom: 3rem; }
.insurance__title { font-size: clamp(1.5rem, 2.5vw, 2rem); }
.insurance__grid { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 1.25rem; max-width: var(--container-width); margin-inline: auto; }
.insurance__item { grid-column: span 2; display: flex; align-items: center; justify-content: center; min-height: 80px; padding: 1rem; border: 1px solid var(--color-border); border-radius: var(--radius-md); background: var(--color-white); box-shadow: var(--shadow-sm); }
.insurance__item:nth-child(7) { grid-column: 2 / span 2; }
.insurance__item img { width: 100%; height: 90px; object-fit: contain; }
@media (max-width: 1024px) { .insurance__grid { grid-template-columns: repeat(4, 1fr); } .insurance__item, .insurance__item:nth-child(7) { grid-column: span 1; } }
@media (max-width: 768px) { .insurance { padding: 5rem 1.5rem; } .insurance__grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .insurance { padding-inline: 1rem; } .insurance__grid { gap: 0.8rem; } .insurance__item { padding: 0.65rem; } .insurance__item img { height: 72px; } }
</style>
