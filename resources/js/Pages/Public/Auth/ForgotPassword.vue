<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { KeyRound } from '@lucide/vue'
import { useLanguage } from '../../../Composables/useLanguage'
import PublicLayout from '../../../Layouts/PublicLayout.vue'
import '../../../../css/auth-page.css'
defineOptions({ layout: PublicLayout })
defineProps({ status: String })
const { t } = useLanguage()
const form = useForm({ email: '' })
</script>
<template>
  <Head :title="t.auth.forgotPage.title" />
  <main class="auth-page auth-page--single"><section class="auth-card">
    <span class="auth-card__icon"><KeyRound :size="28" /></span><span class="auth-panel__eyebrow">{{ t.auth.forgotPage.eyebrow }}</span><h1>{{ t.auth.forgotPage.title }}</h1><p>{{ t.auth.forgotPage.description }}</p>
    <form class="auth-form" @submit.prevent="form.post('/password/forgot')"><label class="auth-field"><span>{{ t.auth.email }}</span><input v-model.trim="form.email" name="email" type="email" autocomplete="email" required /><small v-if="form.errors.email" class="auth-field__error">{{ form.errors.email }}</small></label><p v-if="status" class="auth-form__message auth-form__message--success">{{ status }}</p><button class="auth-form__submit" type="submit" :disabled="form.processing">{{ form.processing ? t.auth.processing : t.auth.forgotPage.submit }}</button></form>
    <p class="auth-panel__switch"><Link href="/login">{{ t.auth.forgotPage.back }}</Link></p>
  </section></main>
</template>
