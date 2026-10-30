<script setup>
import { Link } from '@inertiajs/vue3'
import { MessageCircle, Stethoscope } from '@lucide/vue'
import { computed } from 'vue'
import { useLanguage } from '../Composables/useLanguage'

const { t } = useLanguage()
const links = computed(() => [
  { label: t.value.nav.doctors, to: '/doctors' },
  { label: t.value.nav.medicalAreas, to: '/#medical-areas' },
  { label: t.value.nav.services, to: '/#services' },
  { label: t.value.nav.about, to: '/about' },
  { label: t.value.nav.contact, to: '/#contact' },
])
</script>

<template>
  <footer class="footer">
    <div class="footer__grid">
      <div>
        <div class="footer__brand">
          <span class="footer__brand-icon"><Stethoscope :size="20" /></span>
          <div><strong>Centro Médico Oriental</strong><span>{{ t.footer.tagline }}</span></div>
        </div>
        <p class="footer__description">{{ t.footer.description }}</p>
      </div>
      <div>
        <h4>{{ t.footer.linksTitle }}</h4>
        <ul><li v-for="link in links" :key="link.label"><Link :href="link.to">{{ link.label }}</Link></li></ul>
      </div>
      <div>
        <h4>{{ t.footer.legalTitle }}</h4>
        <ul><li v-for="item in t.footer.legal" :key="item"><a href="#">{{ item }}</a></li></ul>
      </div>
      <div>
        <h4>{{ t.footer.socialTitle }}</h4>
        <div class="footer__social">
          <a href="#" aria-label="Facebook">
            <svg viewBox="0 0 24 24" width="17" height="17" aria-hidden="true"><path fill="currentColor" d="M14 8.5V7c0-.8.5-1 1-1h2V2.1A27 27 0 0 0 14.1 2C11.2 2 9 3.8 9 7.2v1.3H6V13h3v9h4.5v-9h3l.5-4.5h-3Z" /></svg>
          </a>
          <a href="#" aria-label="Instagram">
            <svg viewBox="0 0 24 24" width="17" height="17" aria-hidden="true"><path fill="currentColor" d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7Zm10.5 1.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z" /></svg>
          </a>
          <a href="#" aria-label="WhatsApp"><MessageCircle :size="17" /></a>
        </div>
      </div>
    </div>
    <div class="footer__bottom"><span>{{ t.footer.copyright }}</span></div>
  </footer>
</template>

<style scoped>
.footer { padding: 4.5rem 2rem 2rem; color: rgba(255, 255, 255, 0.85); background: var(--color-footer); }
.footer__grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 2.5rem; max-width: var(--container-width); margin-inline: auto; padding-bottom: 3rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
.footer__brand { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1rem; }
.footer__brand-icon { display: grid; place-items: center; width: 38px; height: 38px; flex: 0 0 auto; border-radius: var(--radius-md); color: var(--color-white); background: linear-gradient(135deg, var(--color-primary), var(--color-accent)); }
.footer__brand strong { display: block; color: var(--color-white); font-size: 1rem; font-weight: 700; }
.footer__brand div span { display: block; color: var(--color-accent); font-size: 0.78rem; font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; }
.footer__description { max-width: 320px; margin: 0; color: rgba(255, 255, 255, 0.6); font-size: 0.9rem; }
.footer h4 { margin: 0 0 1.25rem; color: var(--color-white); font-size: 0.85rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; }
.footer ul { display: flex; flex-direction: column; gap: 0.85rem; margin: 0; padding: 0; list-style: none; }
.footer a { color: rgba(255, 255, 255, 0.65); font-size: 0.9rem; text-decoration: none; transition: var(--transition); }
.footer a:hover { color: var(--color-accent); }
.footer__social { display: flex; gap: 0.75rem; }
.footer__social a { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 50%; color: var(--color-white); background: rgba(255, 255, 255, 0.08); }
.footer__social a:hover { background: var(--color-accent); }
.footer__bottom { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; max-width: var(--container-width); margin-inline: auto; padding-top: 1.75rem; color: rgba(255, 255, 255, 0.45); font-size: 0.82rem; }
@media (max-width: 1024px) { .footer__grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 768px) { .footer { padding: 4rem 1.5rem 2rem; } .footer__grid { grid-template-columns: 1fr; } }
@media (max-width: 480px) { .footer { padding-inline: 1rem; } }
</style>
