<script setup>
import { Link } from '@inertiajs/vue3'
import { computed, onBeforeUnmount, ref, watch } from 'vue'
import { Menu, X } from '@lucide/vue'
import rdFlag from '../Assets/images/flags/rd_flag.png'
import usFlag from '../Assets/images/flags/us_flag.png'
import navLogo from '../Assets/images/nav-logo.png'
import { useLanguage } from '../Composables/useLanguage'

const { language, setLanguage, t } = useLanguage()
const menuOpen = ref(false)
const languageOptions = [
  { code: 'es', label: 'Español', flag: rdFlag },
  { code: 'en', label: 'English', flag: usFlag },
]

const links = computed(() => [
  { label: t.value.nav.home, to: '/' },
  { label: t.value.nav.doctors, to: '/doctors' },
  { label: t.value.nav.services, to: '/services' },
  { label: t.value.nav.about, to: '/about' },
  { label: t.value.nav.contact, to: '/contact' },
])

function openMenu() {
  menuOpen.value = true
}

function closeMenu() {
  menuOpen.value = false
}

watch(menuOpen, (isOpen) => document.body.classList.toggle('is-menu-open', isOpen))
onBeforeUnmount(() => document.body.classList.remove('is-menu-open'))
</script>

<template>
  <header class="site-header">
    <nav class="site-header__nav" aria-label="Navegación principal">
      <Link class="site-header__logo" href="/" aria-label="Centro Médico Oriental">
        <img class="site-header__logo-image" :src="navLogo" alt="Centro Médico Oriental" />
      </Link>

      <ul class="site-header__links">
        <li v-for="link in links" :key="link.label">
          <Link class="site-header__link" :href="link.to">{{ link.label }}</Link>
        </li>
      </ul>

      <div class="site-header__actions">
        <div class="language-selector" role="group" aria-label="Seleccionar idioma">
          <button
            v-for="option in languageOptions"
            :key="option.code"
            type="button"
            class="language-selector__option"
            :class="{ 'language-selector__option--active': language === option.code }"
            :aria-label="option.label"
            :aria-pressed="language === option.code"
            @click="setLanguage(option.code)"
          >
            <img class="language-selector__flag" :src="option.flag" alt="" aria-hidden="true" />
            <span>{{ option.code.toUpperCase() }}</span>
          </button>
        </div>
        <Link class="site-header__login" href="/login">{{ t.nav.login }}</Link>
        <Link class="site-header__register" href="/register">{{ t.nav.register }}</Link>
        <button type="button" class="site-header__menu-button" :aria-label="t.nav.openMenu" @click="openMenu">
          <Menu :size="26" />
        </button>
      </div>
    </nav>
  </header>

  <Transition name="menu-fade">
    <div v-if="menuOpen" class="mobile-menu">
      <div class="mobile-menu__top">
        <Link class="site-header__logo" href="/" @click="closeMenu">
          <img class="site-header__logo-image" :src="navLogo" alt="Centro Médico Oriental" />
        </Link>
        <button type="button" class="mobile-menu__close" :aria-label="t.nav.closeMenu" @click="closeMenu">
          <X :size="20" />
        </button>
      </div>
      <nav class="mobile-menu__nav" aria-label="Navegación móvil">
        <Link v-for="link in links" :key="link.label" class="mobile-menu__link" :href="link.to" @click="closeMenu">
          {{ link.label }}
        </Link>
      </nav>
      <div class="mobile-menu__footer">
        <div class="language-selector mobile-menu__language" role="group" aria-label="Seleccionar idioma">
          <button
            v-for="option in languageOptions"
            :key="option.code"
            type="button"
            class="language-selector__option"
            :class="{ 'language-selector__option--active': language === option.code }"
            :aria-label="option.label"
            :aria-pressed="language === option.code"
            @click="setLanguage(option.code)"
          >
            <img class="language-selector__flag" :src="option.flag" alt="" aria-hidden="true" />
            <span>{{ option.code.toUpperCase() }}</span>
          </button>
        </div>
        <Link class="site-header__login" href="/login" @click="closeMenu">{{ t.nav.login }}</Link>
        <Link class="site-header__register" href="/register" @click="closeMenu">{{ t.nav.register }}</Link>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.site-header {
  position: sticky;
  top: 0;
  z-index: 1000;
  width: 100%;
  display: flex;
  align-items: center;
  height: 80px;
  background: var(--color-primary);
  box-shadow: var(--shadow-sm);
  background: linear-gradient(
      90deg,
      #254c8b 0%,
      #294e8b 45%,
      #32528d 100%
  );
}

.site-header__nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  max-width: var(--container-width);
  margin-inline: auto;
  padding-inline: 2rem;
}

.site-header__logo {
  display: flex;
  align-items: center;
  width: 210px;
  height: 64px;
  flex-shrink: 0;
  text-decoration: none;
}

.site-header__logo-image {
  width: 100%;
  height: 60%;
  object-fit: contain;
  object-position: left center;
}

.site-header__links {
  display: flex;
  align-items: center;
  gap: 2rem;
  margin: 0;
  padding: 0;
  list-style: none;
}

.site-header__link {
  position: relative;
  color: var(--color-white);
  font-size: 0.95rem;
  font-weight: 500;
  text-decoration: none;
  transition: var(--transition);
}

.site-header__link:hover { color: var(--color-accent); }

.site-header__actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.language-selector {
  display: inline-flex;
  align-items: center;
  gap: 0.2rem;
  padding: 0.2rem;
  border-radius: var(--radius-pill);
  background: #17366e;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
}

.language-selector__option {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.35rem;
  min-width: 60px;
  height: 34px;
  padding: 0 0.55rem;
  border: 0;
  border-radius: var(--radius-pill);
  color: rgba(255, 255, 255, 0.72);
  background: transparent;
  font-size: 0.72rem;
  font-weight: 750;
  cursor: pointer;
  transition: color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}

.language-selector__option--active {
  color: #17366e;
  background: var(--color-white);
  box-shadow: 0 3px 10px rgba(9, 29, 67, 0.2);
}

.language-selector__flag {
  width: 22px;
  height: auto;
  border: 1px solid rgba(255, 255, 255, 0.5);
  border-radius: 2px;
  box-shadow: 0 1px 3px rgba(9, 29, 67, 0.25);
}

.site-header__login {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.65rem 1.4rem;
  border: 0;
  border-radius: var(--radius-md);
  color: #112b63;
  background: var(--color-white);
  font-size: 0.9rem;
  font-weight: 600;
  line-height: 1.2;
  text-decoration: none;
  cursor: pointer;
  transition: var(--transition);
}

.site-header__login:hover { color: var(--color-white); background: var(--color-accent); }

.site-header__register {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.65rem 1.4rem;
  border: 1px solid rgba(255, 255, 255, 0.45);
  border-radius: var(--radius-md);
  color: var(--color-white);
  background: #294e8b;
  font-size: 0.9rem;
  font-weight: 600;
  line-height: 1.2;
  text-decoration: none;
  cursor: pointer;
  transition: var(--transition);
}

.site-header__register:hover {
  color: var(--color-white);
  border-color: var(--color-accent);
  background: var(--color-accent);
}

.site-header__menu-button {
  display: none;
  padding: 0.4rem;
  border: 0;
  color: var(--color-white);
  background: transparent;
  cursor: pointer;
}

.mobile-menu {
  position: fixed;
  inset: 0;
  z-index: 2000;
  display: flex;
  flex-direction: column;
  color: var(--color-white);
  background: #112b63;
}

.mobile-menu__top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 80px;
  padding: 0.75rem 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.mobile-menu__close {
  display: grid;
  place-items: center;
  width: 40px;
  height: 40px;
  border: 0;
  border-radius: 50%;
  color: #112b63;
  background: var(--color-white);
  cursor: pointer;
}

.mobile-menu__nav {
  display: flex;
  flex-direction: column;
  gap: 1.75rem;
  padding: 2rem 1.5rem;
  overflow-y: auto;
}

.mobile-menu__link {
  color: inherit;
  font-size: 1.35rem;
  font-weight: 600;
  text-decoration: none;
}

.mobile-menu__footer {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: auto;
  padding: 1.5rem;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.mobile-menu__language {
  align-self: flex-start;
}

.menu-fade-enter-active,
.menu-fade-leave-active { transition: opacity 0.2s ease; }
.menu-fade-enter-from,
.menu-fade-leave-to { opacity: 0; }

@media (max-width: 1200px) {
  .site-header__nav { padding-inline: 1.5rem; }
  .site-header__links { gap: 1.25rem; }
  .site-header__logo { width: 190px; }
}

@media (max-width: 900px) {
  .site-header__links,
  .site-header__actions > .language-selector,
  .site-header__actions > .site-header__login,
  .site-header__actions > .site-header__register { display: none; }
  .site-header__menu-button { display: flex; }
}

@media (max-width: 480px) {
  .site-header__nav { padding-inline: 1rem; }
  .site-header__logo { width: 170px; height: 58px; }
}
</style>
