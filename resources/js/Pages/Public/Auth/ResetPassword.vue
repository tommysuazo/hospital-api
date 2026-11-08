<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import { Eye, EyeOff, LockKeyhole } from '@lucide/vue'
import { useLanguage } from '../../../Composables/useLanguage'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/auth-page.css'
defineOptions({ layout: PublicLayout })
const { t } = useLanguage()
const showPassword = ref(false)
const form = useForm({ password: '', password_confirmation: '' })
</script>
<template>
  <Head :title="t.auth.resetPage.title" />
  <main class="auth-page auth-page--single"><section class="auth-card">
    <span class="auth-card__icon"><LockKeyhole :size="28" /></span><span class="auth-panel__eyebrow">{{ t.auth.resetPage.eyebrow }}</span><h1>{{ t.auth.resetPage.title }}</h1><p>{{ t.auth.resetPage.description }}</p>
    <form class="auth-form" @submit.prevent="form.post('/password/reset', { onFinish: () => form.reset() })">
      <label class="auth-field"><span>{{ t.auth.password }}</span><span class="auth-field__password"><LockKeyhole :size="18" /><input v-model="form.password" name="password" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" minlength="8" required /><button type="button" :aria-label="showPassword ? t.auth.hidePassword : t.auth.showPassword" @click="showPassword = !showPassword"><EyeOff v-if="showPassword" :size="19" /><Eye v-else :size="19" /></button></span><small v-if="form.errors.password" class="auth-field__error">{{ form.errors.password }}</small></label>
      <label class="auth-field"><span>{{ t.auth.confirmPassword }}</span><input v-model="form.password_confirmation" name="password_confirmation" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" minlength="8" required /></label>
      <button class="auth-form__submit" type="submit" :disabled="form.processing">{{ form.processing ? t.auth.processing : t.auth.resetPage.submit }}</button>
    </form>
  </section></main>
</template>
