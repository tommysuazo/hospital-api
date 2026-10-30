<script setup>
import { Link } from '@inertiajs/vue3'
import { ArrowRight, DoorClosed, Phone, Star, User, Video } from '@lucide/vue'
import { computed } from 'vue'
import { availabilityLabel, nextDay } from '../../Utils/directoryUtils'

const props = defineProps({
  doctor: { type: Object, required: true },
  today: { type: Date, required: true },
  english: { type: Boolean, default: false },
})

const appointmentMessage = computed(() => props.english
  ? `Hello, I would like to request an appointment with ${props.doctor.name}. Please confirm the available times.`
  : `Hola, quisiera solicitar una cita con ${props.doctor.name}. ¿Podrían confirmarme los horarios disponibles?`)

const whatsappUrl = computed(() => `https://wa.me/18095562555?text=${encodeURIComponent(appointmentMessage.value)}`)
const availability = computed(() => availabilityLabel(props.doctor, props.today, props.english))
const availableToday = computed(() => nextDay(props.doctor, props.today) === 0)
</script>

<template>
  <article class="specialist-card">
    <div class="specialist-card__image">
      <img class="specialist-card__photo" :src="doctor.photo" :alt="doctor.name" />
      <span v-if="doctor.languages?.length" class="specialist-card__language">{{ doctor.languages.join(' / ') }}</span>
      <span v-if="doctor.modalities?.includes('telemedicine')" class="specialist-card__video"><Video :size="12" />Telemedicina</span>
      <span class="specialist-card__availability" :class="{ 'specialist-card__availability--today': availableToday }">
        <span></span>{{ availability }}
      </span>
    </div>
    <div class="specialist-card__content">
      <div class="specialist-card__meta">
        <p>{{ doctor.medicalAreas.join(' · ') }}</p>
        <span v-if="typeof doctor.rating === 'number'"><Star :size="14" />{{ doctor.rating.toFixed(1) }}<small v-if="doctor.reviewCount != null"> ({{ doctor.reviewCount }})</small></span>
      </div>
      <h2>{{ doctor.name }}</h2>
      <div class="specialist-card__details">
        <span><DoorClosed :size="14" />Hab. {{ doctor.room }}</span>
        <span><Phone :size="14" />Ext. {{ doctor.ext }}</span>
      </div>
      <div class="specialist-card__actions">
        <Link class="specialist-card__profile" :href="doctor.url">
          {{ english ? 'Profile' : 'Perfil' }}<User :size="13" />
        </Link>
        <a
          class="specialist-card__book"
          :href="whatsappUrl"
          target="_blank"
          rel="noopener noreferrer"
          :aria-label="`${english ? 'Request an appointment with' : 'Solicitar cita con'} ${doctor.name} ${english ? 'via WhatsApp' : 'por WhatsApp'}`"
        >
          {{ english ? 'Book' : 'Agendar' }}<ArrowRight :size="14" />
        </a>
      </div>
    </div>
  </article>
</template>
