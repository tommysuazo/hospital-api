<script setup>
import { Award, BadgeCheck, Stethoscope, UsersRound } from '@lucide/vue'
import { useLanguage } from '../Composables/useLanguage'

const { t } = useLanguage()
const icons = [Award, Stethoscope, UsersRound]
</script>

<template>
  <section id="about" class="about">
    <div class="about__grid">
      <div class="about__content">
        <h2>{{ t.about.title }}</h2>
        <p>{{ t.about.paragraph1 }}</p>
        <p>{{ t.about.paragraph2 }}</p>
        <div class="about__tags">
          <span v-for="tag in t.about.tags" :key="tag"><BadgeCheck :size="15" />{{ tag }}</span>
        </div>
      </div>
      <div class="about__stats">
        <article v-for="(stat, index) in t.about.stats.slice(0, 3)" :key="stat.label" class="stat-card">
          <span class="stat-card__icon"><component :is="icons[index]" :size="20" /></span>
          <p><strong>{{ stat.value }}</strong><span>{{ stat.label }}</span></p>
        </article>
      </div>
    </div>
  </section>
</template>

<style scoped>
.about {
  padding: 6rem 2rem;
  color: var(--color-white);
  background: linear-gradient(
    135deg,
    var(--color-primary),
    var(--color-primary-dark)
  );
}

.about__grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  align-items: center;
  gap: 3.5rem;
  max-width: var(--container-width);
  margin-inline: auto;
}

.about__content h2 {
  margin: 0 0 1.25rem;
  font-size: clamp(1.75rem, 3vw, 2.5rem);
  font-weight: 700;
  line-height: 1.25;
}

.about__content p {
  margin: 0 0 1.1rem;
  color: rgba(255, 255, 255, 0.82);
  font-size: 1rem;
}

.about__tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin-top: 1.75rem;
}

.about__tags span {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  padding: 0.5rem 1rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: var(--radius-pill);
  background: rgba(255, 255, 255, 0.1);
  font-size: 0.82rem;
  font-weight: 600;
}

.about__stats {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  justify-self: center;
  width: min(100%, 460px);
  padding: 1.25rem;
  border: 1px solid rgba(255, 255, 255, 0.14);
  border-radius: var(--radius-card);
  background: rgba(17, 43, 99, 0.2);
  box-shadow: 0 22px 45px rgba(12, 29, 66, 0.18);
  backdrop-filter: blur(8px);
}

.stat-card {
  display: grid;
  grid-template-columns: 42px minmax(0, 1fr);
  align-items: center;
  gap: 0.9rem;
  width: 100%;
  min-height: 84px;
  padding: 1.2rem 1.35rem;
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: var(--radius-lg);
  background: rgba(255, 255, 255, 0.08);
  transition: var(--transition);
}

.stat-card:hover {
  background: rgba(255, 255, 255, 0.13);
  transform: translateX(4px);
}

.stat-card__icon {
  display: grid;
  place-items: center;
  width: 42px;
  height: 42px;
  flex: 0 0 auto;
  border-radius: 50%;
  color: var(--color-accent);
  background: rgba(43, 174, 120, 0.2);
}

.stat-card p {
  display: flex;
  align-items: baseline;
  gap: 0.3rem;
  margin: 0;
  color: rgba(255, 255, 255, 0.8);
  font-family: var(--font-heading);
}

.stat-card strong {
  display: block;
  font-size: 1.65rem;
  font-weight: 800;
  line-height: 1;
}

.stat-card p span {
  font-size: 1rem;
  font-weight: 650;
  line-height: 1.35;
}

@media (max-width: 1024px) {
  .about__grid {
    grid-template-columns: 1fr;
    gap: 2.5rem;
  }
}

@media (max-width: 768px) {
  .about {
    padding: 5rem 1.5rem;
  }
}

@media (max-width: 480px) {
  .about {
    padding-inline: 1rem;
  }
}
</style>
