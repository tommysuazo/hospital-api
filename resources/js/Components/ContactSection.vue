<script setup>
import { Mail, MapPin, MessageCircle, Navigation, Phone } from '@lucide/vue'
import { computed } from 'vue'
import { useLanguage } from '../Composables/useLanguage'

const { t } = useLanguage()
const latitude = 18.435677014927986
const longitude = -68.96872911961046
const bbox = [longitude - 0.006, latitude - 0.006, longitude + 0.006, latitude + 0.006].join(',')
const mapSource = `https://www.openstreetmap.org/export/embed.html?bbox=${bbox}&layer=mapnik&marker=${latitude},${longitude}`
const directionsUrl = `https://www.openstreetmap.org/?mlat=${latitude}&mlon=${longitude}#map=17/${latitude}/${longitude}`
const contactItems = computed(() => [
  { icon: MapPin, label: t.value.contact.addressLabel, value: t.value.contact.address },
  { icon: Phone, label: t.value.contact.phoneLabel, value: t.value.contact.phone },
  { icon: MessageCircle, label: t.value.contact.whatsappLabel, value: t.value.contact.whatsapp },
  { icon: Mail, label: t.value.contact.emailLabel, value: t.value.contact.email },
])
</script>

<template>
  <section id="contact" class="contact">
    <div class="section-heading contact__heading"><h2 class="section-title">{{ t.contact.title }}</h2></div>
    <div class="contact__grid">
      <div class="contact__map">
        <iframe title="Centro Médico Oriental" :src="mapSource" loading="lazy"></iframe>
      </div>
      <aside class="contact-card">
        <h3>{{ t.contact.cardTitle }}</h3>
        <p class="contact-card__subtitle">{{ t.contact.cardSubtitle }}</p>
        <div class="contact-card__list">
          <div v-for="item in contactItems" :key="item.label" class="contact-card__item">
            <span class="contact-card__icon"><component :is="item.icon" :size="16" /></span>
            <div><small>{{ item.label }}</small><strong>{{ item.value }}</strong></div>
          </div>
        </div>
        <a class="contact-card__button" :href="directionsUrl" target="_blank" rel="noreferrer"><Navigation :size="16" />{{ t.contact.cta }}</a>
      </aside>
    </div>
  </section>
</template>

<style scoped>
.contact { padding: 6rem 2rem; background: var(--color-white); }
.contact__heading { margin-bottom: 3rem; }
.contact__grid { display: grid; grid-template-columns: 1.6fr 0.9fr; gap: 1.25rem; max-width: var(--container-width); min-height: 480px; margin-inline: auto; border-radius: var(--radius-lg); }
.contact__map { min-height: 320px; overflow: hidden; border-radius: var(--radius-lg); background: var(--color-background-light); box-shadow: var(--shadow-lg); }
.contact__map iframe { display: block; width: 100%; height: 100%; border: 1px solid var(--color-border); }
.contact-card { display: flex; flex-direction: column; padding: 3rem 2.5rem; border-radius: var(--radius-lg); color: var(--color-white); background: var(--color-primary); box-shadow: var(--shadow-lg); }
.contact-card h3 { margin: 0 0 0.5rem; font-size: 1.4rem; font-weight: 700; }
.contact-card__subtitle { margin: 0 0 2rem; color: rgba(255, 255, 255, 0.75); font-size: 0.95rem; }
.contact-card__list { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2.5rem; }
.contact-card__item { display: flex; align-items: flex-start; gap: 0.9rem; }
.contact-card__item > div { min-width: 0; flex: 1; }
.contact-card__icon { display: grid; place-items: center; width: 38px; height: 38px; flex: 0 0 auto; border-radius: 50%; color: var(--color-accent); background: rgba(255, 255, 255, 0.12); }
.contact-card small { display: block; margin-bottom: 0.15rem; color: rgba(255, 255, 255, 0.65); font-size: 0.72rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; }
.contact-card strong { display: block; font-size: 0.95rem; font-weight: 600; overflow-wrap: anywhere; }
.contact-card__button { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; margin-top: auto; padding: 0.9rem 1.5rem; border-radius: var(--radius-md); color: var(--color-white); background: var(--color-accent); font-size: 0.95rem; font-weight: 600; text-decoration: none; transition: var(--transition); }
.contact-card__button:hover { filter: brightness(1.08); }
@media (max-width: 1024px) { .contact__grid { grid-template-columns: 1fr; } .contact__map { min-height: 320px; } .contact-card { padding: 2rem 1.5rem; } }
@media (max-width: 768px) { .contact { padding: 5rem 1.5rem; } }
@media (max-width: 480px) { .contact { padding-inline: 1rem; } .contact-card { padding-inline: 1.25rem; } }
</style>
