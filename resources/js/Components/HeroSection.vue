<script setup>
import { Link } from '@inertiajs/vue3'
import { CalendarDays, FileText, Mail, MapPin, Phone, ShieldCheck } from '@lucide/vue'
import heroImage from '../Assets/images/hero.jpg'
import whiteLogo from '../Assets/images/white-logo.png'
import { useLanguage } from '../Composables/useLanguage'

const { t } = useLanguage()
</script>

<template>
  <section class="hero" :style="{ backgroundImage: `url(${heroImage})` }">
    <div class="hero__overlay" aria-hidden="true"></div>
    <div class="hero__content">
      <span class="hero__badge"><ShieldCheck :size="14" />{{ t.hero.badge }}</span>
      <img
        class="hero__logo"
        :src="whiteLogo"
        alt="Centro Médico Oriental, Dr. Rafael Polanco M."
      />
      <p class="hero__subtitle">{{ t.hero.subtitle }}</p>

      <div class="hero__contacts">
        <div class="hero__contact">
          <span class="hero__contact-icon"><Phone :size="16" /></span>
          <span><small>{{ t.hero.phoneWhatsappLabel }}</small><strong>{{ t.hero.phone }}</strong></span>
        </div>
        <div class="hero__contact">
          <span class="hero__contact-icon"><MapPin :size="16" /></span>
          <span><small>{{ t.hero.addressLabel }}</small><strong>{{ t.hero.address }}</strong></span>
        </div>
        <div class="hero__contact hero__contact--wide">
          <span class="hero__contact-icon"><Mail :size="16" /></span>
          <span><small>{{ t.hero.emailLabel }}</small><strong>{{ t.hero.email }}</strong></span>
        </div>
      </div>

      <div class="hero__actions">
        <Link class="button-primary" href="/doctors">
          <CalendarDays :size="18" />{{ t.hero.appointmentCta }}
        </Link>
        <button type="button" class="hero__results-button">
          <FileText :size="18" />{{ t.hero.resultsCta }}
        </button>
      </div>
    </div>
  </section>
</template>

<style scoped>
.hero {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 88vh;
  overflow: hidden;
  color: var(--color-white);
  background-position: center;
  background-size: cover;
  text-align: center;
}

.hero__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(39, 68, 129, 0.88), rgba(39, 68, 129, 0.82));
}

.hero__content {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  max-width: 780px;
  padding: 3rem 2rem;
}

.hero__badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1.75rem;
  padding: 0.5rem 1.1rem;
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: var(--radius-pill);
  background: rgba(255, 255, 255, 0.12);
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

.hero__logo {
  width: 32rem;
  height: auto;
  margin: 0 0 1.5rem;
  flex-shrink: 0;
}

.hero__subtitle {
  max-width: 560px;
  margin: 0 0 2.25rem;
  color: rgba(255, 255, 255, 0.85);
  font-size: 1.2rem;
  font-style: italic;
}

.hero__contacts {
  display: grid;
  grid-template-columns: auto auto;
  gap: 0.75rem;
  margin-bottom: 2rem;
}

.hero__contact {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.7rem 1.1rem;
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: var(--radius-md);
  background: rgba(255, 255, 255, 0.1);
  text-align: left;
}

.hero__contact--wide { grid-column: 1 / -1; justify-self: center; }

.hero__contact-icon {
  display: grid;
  place-items: center;
  width: 34px;
  height: 34px;
  flex: 0 0 auto;
  border-radius: 50%;
  background: var(--color-accent);
}

.hero__contact small,
.hero__contact strong { display: block; white-space: nowrap; }
.hero__contact small { color: rgba(255, 255, 255, 0.65); font-size: 0.65rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; }
.hero__contact strong { color: var(--color-white); font-size: 0.85rem; font-weight: 600; }

.hero__actions {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 0.85rem;
}

.hero__results-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 1rem 2.25rem;
  border: 1px solid rgba(255, 255, 255, 0.7);
  border-radius: var(--radius-md);
  color: var(--color-white);
  background: rgba(255, 255, 255, 0.1);
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
}

.hero__results-button:hover {
  color: var(--color-primary);
  background: var(--color-white);
}

@media (max-width: 1024px) {
  .hero__badge { display: none; }
}

@media (max-width: 768px) {
  .hero { min-height: calc(100vh - 160px); }
  .hero__content { padding-inline: 1.25rem; }
  .hero__contacts { grid-template-columns: 1fr; width: min(100%, 370px); }
  .hero__contact--wide { grid-column: auto; justify-self: stretch; }
}

@media (max-width: 480px) {
  .hero__actions,
  .hero__actions > * { width: 100%; }
  .hero__contact { padding-inline: 0.8rem; }
  .hero__contact strong { white-space: normal; overflow-wrap: anywhere; }
}
</style>
