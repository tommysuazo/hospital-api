<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { MailCheck, ShieldCheck } from '@lucide/vue'
import { useLanguage } from '../../../Composables/useLanguage'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/auth-page.css'
defineOptions({ layout: PublicLayout })
defineProps({ maskedEmail: String, purpose: String, status: String })
const { t } = useLanguage()
const form = useForm({ code: '' })
const resendForm = useForm({})
</script>
<template>
  <Head :title="t.auth.verify.title" />
  <main class="auth-page auth-page--single"><section class="auth-card">
    <span class="auth-card__icon"><MailCheck :size="28" /></span><span class="auth-panel__eyebrow auth-card__eyebrow"><ShieldCheck :size="14" /> {{ t.auth.verify.eyebrow }}</span>
    <h1>{{ t.auth.verify.title }}</h1><p>{{ t.auth.verify.description }} <strong>{{ maskedEmail }}</strong></p>
    <form class="auth-form" @submit.prevent="form.post('/auth/verify-code')">
      <label class="auth-field"><span>{{ t.auth.verify.code }}</span><input v-model="form.code" class="auth-code-input" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}" placeholder="000000" autofocus required /><small v-if="form.errors.code" class="auth-field__error">{{ form.errors.code }}</small></label>
      <p v-if="status" class="auth-form__message auth-form__message--success" role="status">{{ status }}</p>
      <button class="auth-form__submit" type="submit" :disabled="form.processing">{{ form.processing ? t.auth.processing : t.auth.verify.submit }}</button>
    </form>
    <div class="auth-card__actions"><button type="button" :disabled="resendForm.processing" @click="resendForm.post('/auth/resend-code')">{{ t.auth.verify.resend }}</button><Link href="/login">{{ t.auth.verify.back }}</Link></div>
  </section></main>
</template>
