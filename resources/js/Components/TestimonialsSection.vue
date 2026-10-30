<script setup>
import { Quote, ShieldCheck } from '@lucide/vue'
import { useLanguage } from '../Composables/useLanguage'

const { t } = useLanguage()
</script>

<template>
  <section class="testimonials" aria-labelledby="testimonials-title">
    <div class="testimonials__container">
      <div class="testimonials__heading">
        <div>
          <span class="testimonials__eyebrow"><ShieldCheck :size="15" />{{ t.testimonials.eyebrow }}</span>
          <h2 id="testimonials-title">{{ t.testimonials.title }}</h2>
        </div>
        <p>{{ t.testimonials.description }}</p>
      </div>

      <div class="testimonials__grid">
        <article v-for="(item, index) in t.testimonials.items" :key="item.name" class="testimonial-card" :class="{ 'testimonial-card--featured': index === 1 }">
          <Quote class="testimonial-card__quote" :size="27" aria-hidden="true" />
          <blockquote>“{{ item.quote }}”</blockquote>
          <div class="testimonial-card__person">
            <span class="testimonial-card__avatar">{{ item.name.charAt(0) }}</span>
            <div><strong>{{ item.name }}</strong><small>{{ item.context }}</small></div>
          </div>
        </article>
      </div>
    </div>
  </section>
</template>

<style scoped>
.testimonials {
  padding: 6.5rem 2rem;
  background:
    radial-gradient(circle at 100% 0%, rgba(43, 174, 120, 0.13), transparent 25rem),
    var(--color-specialties);
}

.testimonials__container { max-width: var(--container-width); margin-inline: auto; }

.testimonials__heading {
  display: grid;
  grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
  align-items: end;
  gap: 3rem;
  margin-bottom: 3rem;
}

.testimonials__eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  margin-bottom: 0.85rem;
  color: #70d9ae;
  font-size: 0.75rem;
  font-weight: 800;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

.testimonials h2 {
  max-width: 690px;
  margin: 0;
  color: var(--color-white);
  font-size: clamp(2rem, 4vw, 3.35rem);
  line-height: 1.12;
  letter-spacing: -0.045em;
}

.testimonials__heading > p {
  margin: 0;
  color: rgba(255, 255, 255, 0.68);
  font-size: 0.94rem;
  line-height: 1.75;
}

.testimonials__grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }

.testimonial-card {
  position: relative;
  display: flex;
  flex-direction: column;
  min-height: 285px;
  padding: 2rem;
  border: 1px solid rgba(255, 255, 255, 0.13);
  border-radius: var(--radius-card);
  color: var(--color-white);
  background: rgba(255, 255, 255, 0.07);
  backdrop-filter: blur(10px);
}

.testimonial-card--featured { border-color: rgba(112, 217, 174, 0.35); background: rgba(43, 174, 120, 0.13); }
.testimonial-card__quote { margin-bottom: 1.25rem; color: var(--color-accent); fill: rgba(43, 174, 120, 0.18); }
.testimonial-card blockquote { margin: 0 0 1.7rem; color: rgba(255, 255, 255, 0.88); font-size: 0.98rem; line-height: 1.75; }

.testimonial-card__person { display: flex; align-items: center; gap: 0.7rem; margin-top: auto; }
.testimonial-card__avatar { display: grid; place-items: center; width: 40px; height: 40px; border-radius: 50%; color: var(--color-white); background: var(--color-accent); font-size: 0.85rem; font-weight: 800; }
.testimonial-card__person strong,
.testimonial-card__person small { display: block; }
.testimonial-card__person strong { color: var(--color-white); font-size: 0.85rem; }
.testimonial-card__person small { color: rgba(255, 255, 255, 0.56); font-size: 0.72rem; }

@media (max-width: 900px) {
  .testimonials__heading { grid-template-columns: 1fr; gap: 1.2rem; }
  .testimonials__grid { grid-template-columns: 1fr; }
  .testimonial-card { min-height: 0; }
}

@media (max-width: 768px) { .testimonials { padding: 5rem 1.5rem; } }
@media (max-width: 480px) { .testimonials { padding-inline: 1rem; } .testimonial-card { padding: 1.5rem; } }
</style>
