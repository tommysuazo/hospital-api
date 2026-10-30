<script setup>
import { computed, reactive, ref, watch } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { Eye, EyeOff, LockKeyhole, ShieldCheck, Stethoscope, UserRound } from '@lucide/vue'
import { useLanguage } from '../../../Composables/useLanguage'
import heroImage from '../../../Assets/images/hero.jpg'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/auth-page.css'

defineOptions({ layout: PublicLayout })

const props = defineProps({
  mode: { type: String, default: 'login' },
})

const { t } = useLanguage()
const showPassword = ref(false)
const showConfirmation = ref(false)
const submitted = ref(false)
const errorMessage = ref('')
const loginRole = ref('patient')
const form = reactive({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  password: '',
  confirmPassword: '',
  remember: false,
  terms: false,
})

const isRegister = computed(() => props.mode === 'register')
const isStaffLogin = computed(() => !isRegister.value && loginRole.value === 'staff')
const content = computed(() => {
  if (isRegister.value) return t.value.auth.register
  if (!isStaffLogin.value) return t.value.auth.login

  return {
    ...t.value.auth.login,
    eyebrow: t.value.auth.login.staffEyebrow,
    title: t.value.auth.login.staffTitle,
    subtitle: t.value.auth.login.staffSubtitle,
    submit: t.value.auth.login.staffSubmit,
  }
})
const successMessage = computed(() => {
  if (isRegister.value) return t.value.auth.registerSuccess
  return isStaffLogin.value ? t.value.auth.staffLoginSuccess : t.value.auth.loginSuccess
})

watch(() => props.mode, () => {
  submitted.value = false
  errorMessage.value = ''
  showPassword.value = false
  showConfirmation.value = false
  loginRole.value = 'patient'
})

function selectLoginRole(role) {
  loginRole.value = role
  submitted.value = false
  errorMessage.value = ''
}

function submitForm() {
  errorMessage.value = ''
  submitted.value = false

  if (isRegister.value && form.password !== form.confirmPassword) {
    errorMessage.value = t.value.auth.mismatch
    return
  }

  submitted.value = true
}
</script>

<template>
  <Head :title="isRegister ? 'Registro' : 'Ingresar'" />
  <main class="auth-page">
      <div class="auth-page__glow auth-page__glow--top" aria-hidden="true"></div>
      <div class="auth-page__glow auth-page__glow--bottom" aria-hidden="true"></div>

      <section class="auth-shell" :class="{ 'auth-shell--register': isRegister }">
        <aside class="auth-visual" :style="{ backgroundImage: `url(${heroImage})` }">
          <div class="auth-visual__shade"></div>

          <div class="auth-visual__content">
            <span class="auth-visual__badge"><ShieldCheck :size="16" /> {{ t.auth.secure }}</span>
            <h2>{{ t.auth.secureHeadline }}</h2>
            <p>{{ t.auth.secureDescription }}</p>
          </div>

          <div class="auth-visual__support">
            <span>{{ t.auth.help }}</span>
            <a href="tel:+18095562555">809-556-2555</a>
          </div>
        </aside>

        <div class="auth-panel">
          <div class="auth-panel__inner">
            <div v-if="!isRegister" class="auth-tabs" role="tablist" :aria-label="t.auth.login.tabsLabel">
              <button
                type="button"
                role="tab"
                :aria-selected="loginRole === 'patient'"
                :class="{ 'auth-tabs__button--active': loginRole === 'patient' }"
                @click="selectLoginRole('patient')"
              >
                <UserRound :size="17" />
                {{ t.auth.login.patientTab }}
              </button>
              <button
                type="button"
                role="tab"
                :aria-selected="loginRole === 'staff'"
                :class="{ 'auth-tabs__button--active': loginRole === 'staff' }"
                @click="selectLoginRole('staff')"
              >
                <Stethoscope :size="17" />
                {{ t.auth.login.staffTab }}
              </button>
            </div>

            <div class="auth-panel__heading">
              <span class="auth-panel__eyebrow">{{ content.eyebrow }}</span>
              <h1>{{ content.title }}</h1>
              <p>{{ content.subtitle }}</p>
            </div>

            <form class="auth-form" @submit.prevent="submitForm">
              <div v-if="isRegister" class="auth-form__row">
                <label class="auth-field">
                  <span>{{ t.auth.firstName }}</span>
                  <input v-model.trim="form.firstName" name="firstName" type="text" autocomplete="given-name" placeholder="Ana" required />
                </label>
                <label class="auth-field">
                  <span>{{ t.auth.lastName }}</span>
                  <input v-model.trim="form.lastName" name="lastName" type="text" autocomplete="family-name" placeholder="Rodríguez" required />
                </label>
              </div>

              <label class="auth-field">
                <span>{{ isStaffLogin ? t.auth.staffEmail : t.auth.email }}</span>
                <input
                  v-model.trim="form.email"
                  name="email"
                  type="email"
                  autocomplete="email"
                  :placeholder="isStaffLogin ? 'usuario@centromedicooriental.com' : 'nombre@correo.com'"
                  required
                />
              </label>

              <label v-if="isRegister" class="auth-field">
                <span>{{ t.auth.phone }}</span>
                <input v-model.trim="form.phone" name="phone" type="tel" autocomplete="tel" placeholder="809-000-0000" required />
              </label>

              <label class="auth-field">
                <span>{{ t.auth.password }}</span>
                <span class="auth-field__password">
                  <LockKeyhole :size="18" aria-hidden="true" />
                  <input
                    v-model="form.password"
                    name="password"
                    :type="showPassword ? 'text' : 'password'"
                    :autocomplete="isRegister ? 'new-password' : 'current-password'"
                    placeholder="••••••••"
                    minlength="8"
                    required
                  />
                  <button type="button" :aria-label="showPassword ? t.auth.hidePassword : t.auth.showPassword" @click="showPassword = !showPassword">
                    <EyeOff v-if="showPassword" :size="19" />
                    <Eye v-else :size="19" />
                  </button>
                </span>
                <small v-if="isRegister">{{ t.auth.passwordHint }}</small>
              </label>

              <label v-if="isRegister" class="auth-field">
                <span>{{ t.auth.confirmPassword }}</span>
                <span class="auth-field__password">
                  <LockKeyhole :size="18" aria-hidden="true" />
                  <input
                    v-model="form.confirmPassword"
                    name="confirmPassword"
                    :type="showConfirmation ? 'text' : 'password'"
                    autocomplete="new-password"
                    placeholder="••••••••"
                    minlength="8"
                    required
                  />
                  <button type="button" :aria-label="showConfirmation ? t.auth.hidePassword : t.auth.showPassword" @click="showConfirmation = !showConfirmation">
                    <EyeOff v-if="showConfirmation" :size="19" />
                    <Eye v-else :size="19" />
                  </button>
                </span>
              </label>

              <div v-if="!isRegister" class="auth-form__options">
                <label class="auth-check">
                  <input v-model="form.remember" type="checkbox" />
                  <span>{{ t.auth.remember }}</span>
                </label>
                <a href="#">{{ t.auth.forgot }}</a>
              </div>

              <label v-else class="auth-check auth-check--terms">
                <input v-model="form.terms" type="checkbox" required />
                <span>
                  {{ t.auth.termsPrefix }} <a href="#">{{ t.auth.terms }}</a> {{ t.auth.and }}
                  <a href="#">{{ t.auth.privacy }}</a>.
                </span>
              </label>

              <p v-if="errorMessage" class="auth-form__message auth-form__message--error" role="alert">{{ errorMessage }}</p>
              <p v-if="submitted" class="auth-form__message auth-form__message--success" role="status">
                {{ successMessage }}
              </p>

              <button class="auth-form__submit" type="submit">{{ content.submit }}</button>
            </form>

            <p class="auth-panel__switch">
              {{ content.switchText }}
              <Link :href="isRegister ? '/login' : '/register'">{{ content.switchAction }}</Link>
            </p>
          </div>
        </div>
      </section>
  </main>
</template>
