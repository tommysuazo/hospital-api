<script setup>
import { computed, ref, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Eye, EyeOff, LockKeyhole, ShieldCheck, Stethoscope, UserRound } from '@lucide/vue'
import { useLanguage } from '../../../Composables/useLanguage'
import heroImage from '../../../Assets/images/hero.jpg'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/auth-page.css'

defineOptions({ layout: PublicLayout })
const props = defineProps({ mode: { type: String, default: 'login' }, status: { type: String, default: '' } })
const { t } = useLanguage()
const showPassword = ref(false)
const showConfirmation = ref(false)
const loginRole = ref('client')
const form = useForm({
  access_type: 'client', first_name: '', last_name: '', document_type: 'id_card', document_number: '',
  birth_date: '', gender: '', email: '', phone: '', password: '', password_confirmation: '', remember: false, terms: false,
})
const isRegister = computed(() => props.mode === 'register')
const isStaffLogin = computed(() => !isRegister.value && loginRole.value === 'staff')
const content = computed(() => {
  if (isRegister.value) return t.value.auth.register
  if (!isStaffLogin.value) return t.value.auth.login
  return { ...t.value.auth.login, eyebrow: t.value.auth.login.staffEyebrow, title: t.value.auth.login.staffTitle, subtitle: t.value.auth.login.staffSubtitle, submit: t.value.auth.login.staffSubmit }
})
watch(() => props.mode, () => { form.clearErrors(); showPassword.value = false; showConfirmation.value = false; selectLoginRole('client') })
function selectLoginRole(role) { loginRole.value = role; form.access_type = role; form.clearErrors() }
function submitForm() {
  form.post(isRegister.value ? '/register' : '/login', {
    preserveScroll: true,
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head :title="isRegister ? t.auth.register.title : t.auth.login.title" />
  <main class="auth-page">
    <div class="auth-page__glow auth-page__glow--top" aria-hidden="true"></div>
    <div class="auth-page__glow auth-page__glow--bottom" aria-hidden="true"></div>
    <section class="auth-shell" :class="{ 'auth-shell--register': isRegister }">
      <aside class="auth-visual" :style="{ backgroundImage: `url(${heroImage})` }">
        <div class="auth-visual__shade"></div>
        <div class="auth-visual__content">
          <span class="auth-visual__badge"><ShieldCheck :size="16" /> {{ t.auth.secure }}</span>
          <h2>{{ t.auth.secureHeadline }}</h2><p>{{ t.auth.secureDescription }}</p>
        </div>
        <div class="auth-visual__support"><span>{{ t.auth.help }}</span><a href="tel:+18095562555">809-556-2555</a></div>
      </aside>
      <div class="auth-panel"><div class="auth-panel__inner">
        <div v-if="!isRegister" class="auth-tabs" role="tablist" :aria-label="t.auth.login.tabsLabel">
          <button type="button" role="tab" :aria-selected="loginRole === 'client'" :class="{ 'auth-tabs__button--active': loginRole === 'client' }" @click="selectLoginRole('client')"><UserRound :size="17" /> {{ t.auth.login.patientTab }}</button>
          <button type="button" role="tab" :aria-selected="loginRole === 'staff'" :class="{ 'auth-tabs__button--active': loginRole === 'staff' }" @click="selectLoginRole('staff')"><Stethoscope :size="17" /> {{ t.auth.login.staffTab }}</button>
        </div>
        <div class="auth-panel__heading"><span class="auth-panel__eyebrow">{{ content.eyebrow }}</span><h1>{{ content.title }}</h1><p>{{ content.subtitle }}</p></div>
        <form class="auth-form" @submit.prevent="submitForm">
          <template v-if="isRegister">
            <div class="auth-form__row">
              <label class="auth-field"><span>{{ t.auth.firstName }}</span><input v-model.trim="form.first_name" name="first_name" type="text" autocomplete="given-name" required /><small v-if="form.errors.first_name" class="auth-field__error">{{ form.errors.first_name }}</small></label>
              <label class="auth-field"><span>{{ t.auth.lastName }}</span><input v-model.trim="form.last_name" name="last_name" type="text" autocomplete="family-name" required /><small v-if="form.errors.last_name" class="auth-field__error">{{ form.errors.last_name }}</small></label>
            </div>
            <div class="auth-form__row">
              <label class="auth-field"><span>{{ t.auth.documentType }}</span><select v-model="form.document_type" name="document_type" required><option value="id_card">{{ t.auth.idCard }}</option><option value="passport">{{ t.auth.passport }}</option></select></label>
              <label class="auth-field"><span>{{ t.auth.documentNumber }}</span><input v-model.trim="form.document_number" name="document_number" type="text" required /><small v-if="form.errors.document_number" class="auth-field__error">{{ form.errors.document_number }}</small></label>
            </div>
            <div class="auth-form__row">
              <label class="auth-field"><span>{{ t.auth.birthDate }}</span><input v-model="form.birth_date" name="birth_date" type="date" required /><small v-if="form.errors.birth_date" class="auth-field__error">{{ form.errors.birth_date }}</small></label>
              <label class="auth-field"><span>{{ t.auth.gender }}</span><select v-model="form.gender" name="gender" required><option disabled value="">{{ t.auth.selectOption }}</option><option value="female">{{ t.auth.female }}</option><option value="male">{{ t.auth.male }}</option><option value="other">{{ t.auth.other }}</option></select><small v-if="form.errors.gender" class="auth-field__error">{{ form.errors.gender }}</small></label>
            </div>
          </template>
          <label class="auth-field"><span>{{ isStaffLogin ? t.auth.staffEmail : t.auth.email }}</span><input v-model.trim="form.email" name="email" type="email" autocomplete="email" :placeholder="isStaffLogin ? 'usuario@centromedicooriental.com' : 'nombre@correo.com'" required /><small v-if="form.errors.email" class="auth-field__error">{{ form.errors.email }}</small></label>
          <label v-if="isRegister" class="auth-field"><span>{{ t.auth.phone }}</span><input v-model.trim="form.phone" name="phone" type="tel" autocomplete="tel" placeholder="809-000-0000" required /><small v-if="form.errors.phone" class="auth-field__error">{{ form.errors.phone }}</small></label>
          <label class="auth-field">
            <span>{{ t.auth.password }}</span><span class="auth-field__password"><LockKeyhole :size="18" aria-hidden="true" /><input v-model="form.password" name="password" :type="showPassword ? 'text' : 'password'" :autocomplete="isRegister ? 'new-password' : 'current-password'" placeholder="••••••••" minlength="8" required /><button type="button" :aria-label="showPassword ? t.auth.hidePassword : t.auth.showPassword" @click="showPassword = !showPassword"><EyeOff v-if="showPassword" :size="19" /><Eye v-else :size="19" /></button></span>
            <small v-if="form.errors.password" class="auth-field__error">{{ form.errors.password }}</small><small v-else-if="isRegister">{{ t.auth.passwordHint }}</small>
          </label>
          <label v-if="isRegister" class="auth-field"><span>{{ t.auth.confirmPassword }}</span><span class="auth-field__password"><LockKeyhole :size="18" aria-hidden="true" /><input v-model="form.password_confirmation" name="password_confirmation" :type="showConfirmation ? 'text' : 'password'" autocomplete="new-password" placeholder="••••••••" minlength="8" required /><button type="button" :aria-label="showConfirmation ? t.auth.hidePassword : t.auth.showPassword" @click="showConfirmation = !showConfirmation"><EyeOff v-if="showConfirmation" :size="19" /><Eye v-else :size="19" /></button></span></label>
          <div v-if="!isRegister" class="auth-form__options">
            <label class="auth-check"><input v-model="form.remember" type="checkbox" /><span>{{ t.auth.remember }}</span></label>
            <Link v-if="!isStaffLogin" href="/password/forgot">{{ t.auth.forgot }}</Link><span v-else class="auth-form__staff-help">{{ t.auth.staffPasswordHelp }}</span>
          </div>
          <label v-else class="auth-check auth-check--terms"><input v-model="form.terms" type="checkbox" required /><span>{{ t.auth.termsPrefix }} <a href="#">{{ t.auth.terms }}</a> {{ t.auth.and }} <a href="#">{{ t.auth.privacy }}</a>.</span></label>
          <p v-if="props.status" class="auth-form__message auth-form__message--success" role="status">{{ props.status }}</p>
          <p v-if="form.errors.auth" class="auth-form__message auth-form__message--error" role="alert">{{ form.errors.auth }}</p>
          <button class="auth-form__submit" type="submit" :disabled="form.processing">{{ form.processing ? t.auth.processing : content.submit }}</button>
        </form>
        <p class="auth-panel__switch">{{ content.switchText }} <Link :href="isRegister ? '/login' : '/register'">{{ content.switchAction }}</Link></p>
      </div></div>
    </section>
  </main>
</template>
