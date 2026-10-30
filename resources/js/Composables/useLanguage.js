import { computed, inject, provide, ref } from 'vue'
import { translations } from '../Data/siteContent'

const languageKey = Symbol('language')

export function provideLanguage() {
  const language = ref('es')
  const t = computed(() => translations[language.value])

  function setLanguage(nextLanguage) {
    if (translations[nextLanguage]) {
      language.value = nextLanguage
      document.documentElement.lang = nextLanguage
    }
  }

  const languageState = { language, t, setLanguage }
  provide(languageKey, languageState)
  return languageState
}

export function useLanguage() {
  const languageState = inject(languageKey)
  if (!languageState) {
    throw new Error('useLanguage must be called below provideLanguage')
  }
  return languageState
}
