<script setup>
import { computed, reactive, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Clock3, Mail, MapPin, MessageCircle, Phone, Send, ShieldCheck } from '@lucide/vue'
import { useLanguage } from '../../Composables/useLanguage'
import heroImage from '../../Assets/images/hero.jpg'
import PublicLayout from '../../Layouts/PublicLayout.vue'
import '../../../css/contact-page.css'

defineOptions({ layout: PublicLayout })

const { t } = useLanguage()
const submitted = ref(false)
const form = reactive({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
})

const contactItems = computed(() => [
  { icon: Phone, label: t.value.contactPage.phoneLabel, value: '809-556-2555', href: 'tel:+18095562555' },
  { icon: MessageCircle, label: 'WhatsApp', value: '809-556-2555', href: 'https://wa.me/18095562555' },
  { icon: Mail, label: t.value.contactPage.emailLabel, value: 'centromedicooriental@gmail.com', href: 'mailto:centromedicooriental@gmail.com' },
  { icon: MapPin, label: t.value.contactPage.addressLabel, value: t.value.contact.address },
])

function submitForm() {
  submitted.value = true
}
</script>

<template>
  <Head title="Contacto" />
  <main class="contact-page">
      <div class="contact-page__glow contact-page__glow--top" aria-hidden="true"></div>
      <div class="contact-page__glow contact-page__glow--bottom" aria-hidden="true"></div>

      <section class="contact-shell">
        <aside class="contact-visual" :style="{ backgroundImage: `url(${heroImage})` }">
          <div class="contact-visual__shade"></div>

          <div class="contact-visual__content">
            <span class="contact-visual__badge"><ShieldCheck :size="16" /> {{ t.contactPage.badge }}</span>
            <h1>{{ t.contactPage.visualTitle }}</h1>
            <p>{{ t.contactPage.visualDescription }}</p>

            <div class="contact-visual__list">
              <div v-for="item in contactItems" :key="item.label" class="contact-visual__item">
                <span class="contact-visual__icon"><component :is="item.icon" :size="18" /></span>
                <div>
                  <small>{{ item.label }}</small>
                  <a v-if="item.href" :href="item.href" :target="item.href.startsWith('http') ? '_blank' : undefined" rel="noreferrer">
                    {{ item.value }}
                  </a>
                  <strong v-else>{{ item.value }}</strong>
                </div>
              </div>
            </div>
          </div>

          <div class="contact-visual__hours">
            <Clock3 :size="18" />
            <div>
              <span>{{ t.contactPage.hoursLabel }}</span>
              <strong>{{ t.contactPage.hours }}</strong>
            </div>
          </div>
        </aside>

        <div class="contact-panel">
          <div class="contact-panel__inner">
            <div class="contact-panel__heading">
              <span class="contact-panel__eyebrow">{{ t.contactPage.eyebrow }}</span>
              <h2>{{ t.contactPage.title }}</h2>
              <p>{{ t.contactPage.subtitle }}</p>
            </div>

            <form class="contact-form" @submit.prevent="submitForm">
              <div class="contact-form__row">
                <label class="contact-field">
                  <span>{{ t.contactPage.name }}</span>
                  <input v-model.trim="form.name" name="name" type="text" autocomplete="name" :placeholder="t.contactPage.namePlaceholder" required />
                </label>
                <label class="contact-field">
                  <span>{{ t.contactPage.phone }}</span>
                  <input v-model.trim="form.phone" name="phone" type="tel" autocomplete="tel" placeholder="809-000-0000" required />
                </label>
              </div>

              <label class="contact-field">
                <span>{{ t.contactPage.email }}</span>
                <input v-model.trim="form.email" name="email" type="email" autocomplete="email" placeholder="nombre@correo.com" required />
              </label>

              <label class="contact-field">
                <span>{{ t.contactPage.subject }}</span>
                <select v-model="form.subject" name="subject" required>
                  <option value="" disabled>{{ t.contactPage.subjectPlaceholder }}</option>
                  <option v-for="option in t.contactPage.subjects" :key="option" :value="option">{{ option }}</option>
                </select>
              </label>

              <label class="contact-field">
                <span>{{ t.contactPage.message }}</span>
                <textarea v-model.trim="form.message" name="message" rows="4" :placeholder="t.contactPage.messagePlaceholder" required></textarea>
              </label>

              <p v-if="submitted" class="contact-form__message" role="status">
                {{ t.contactPage.success }}
              </p>

              <button class="contact-form__submit" type="submit">
                {{ t.contactPage.submit }}
                <Send :size="17" aria-hidden="true" />
              </button>
            </form>

            <p class="contact-panel__note">{{ t.contactPage.note }}</p>
          </div>
        </div>
      </section>
  </main>
</template>
