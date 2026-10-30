<script setup>
import { computed, ref } from 'vue'
import { resolveDoctorPhoto } from '../../Data/doctorPhotos'
import { useLanguage } from '../../Composables/useLanguage'
import { emptyFilters, matchesDoctor, nextDay, todayInCenter } from '../../Utils/directoryUtils'
import DirectoryFilters from './DirectoryFilters.vue'
import DirectoryToolbar from './DirectoryToolbar.vue'
import DoctorCard from './DoctorCard.vue'
import DirectoryPagination from './DirectoryPagination.vue'
import '../../../css/doctors-directory.css'

const { language } = useLanguage()
const props = defineProps({
  doctors: { type: Array, default: () => [] },
  specialties: { type: Array, default: () => [] },
})

const english = computed(() => language.value === 'en')
const filters = ref({ ...emptyFilters })
const appliedFilters = ref({ ...emptyFilters })
const page = ref(1)
const sort = ref('available')
const view = ref('grid')
const today = todayInCenter()
const perPage = 8
const localizedSpecialties = computed(() => props.specialties.map(
  (specialty) => specialty.names[language.value] || specialty.names.es || specialty.slug,
))
const localizedDoctors = computed(() => props.doctors.map((doctor, index) => ({
  ...doctor,
  photo: doctor.photo || resolveDoctorPhoto(doctor.id, index),
  description: doctor.descriptions?.[language.value] || doctor.descriptions?.es || '',
  specialties: doctor.specialties.map(
    (specialty) => specialty.names[language.value] || specialty.names.es || specialty.slug,
  ),
})))

const results = computed(() => [...localizedDoctors.value]
  .filter((doctor) => matchesDoctor(doctor, appliedFilters.value))
  .sort((doctorA, doctorB) => (sort.value === 'name'
    ? doctorA.name.localeCompare(doctorB.name, 'es')
    : nextDay(doctorA, today) - nextDay(doctorB, today))))

const pages = computed(() => Math.max(1, Math.ceil(results.value.length / perPage)))
const currentPage = computed(() => Math.min(page.value, pages.value))
const shownDoctors = computed(() => results.value.slice((currentPage.value - 1) * perPage, currentPage.value * perPage))

function updateFilter(key, value) {
  filters.value = { ...filters.value, [key]: value }
}

function applyFilters() {
  appliedFilters.value = { ...filters.value }
  page.value = 1
}

function clearFilters() {
  filters.value = { ...emptyFilters }
  appliedFilters.value = { ...emptyFilters }
  page.value = 1
}

function updateSort(value) {
  sort.value = value
  page.value = 1
}

function updatePage(value) {
  page.value = Math.min(Math.max(value, 1), pages.value)
  document.querySelector('.directory-toolbar')?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}
</script>

<template>
  <main class="directory-page">
    <div class="directory-container">
      <h1 class="directory-title">{{ english ? 'Find your specialist' : 'Encuentra tu especialista' }}</h1>

      <DirectoryFilters
        :filters="filters"
        :english="english"
        :specialties="localizedSpecialties"
        @update-filter="updateFilter"
        @search="applyFilters"
        @clear="clearFilters"
      />

      <DirectoryToolbar
        :count="results.length"
        :shown="shownDoctors.length"
        :sort="sort"
        :view="view"
        :english="english"
        @update-sort="updateSort"
        @update-view="view = $event"
      />

      <div v-if="results.length" class="directory-grid" :class="{ 'directory-grid--list': view === 'list' }">
        <DoctorCard
          v-for="doctor in shownDoctors"
          :key="doctor.id"
          :doctor="doctor"
          :today="today"
          :english="english"
        />
      </div>

      <div v-else class="directory-empty">
        <h2>{{ english ? 'No matching specialists' : 'No encontramos especialistas con estos filtros' }}</h2>
        <p>{{ english ? 'Try another specialty, date or search.' : 'Prueba otra especialidad, fecha o búsqueda.' }}</p>
        <button type="button" @click="clearFilters">{{ english ? 'Clear filters' : 'Limpiar filtros' }}</button>
      </div>

      <p class="directory-note">
        {{ english
          ? 'Availability is based on consultation days. Request your appointment via WhatsApp and confirm the time with the center.'
          : 'La disponibilidad corresponde a los días de consulta. Solicita tu cita por WhatsApp y confirma el horario con el centro.' }}
      </p>

      <DirectoryPagination
        :page="currentPage"
        :pages="pages"
        :count="results.length"
        :shown="shownDoctors.length"
        :english="english"
        @change="updatePage"
      />
    </div>
  </main>
</template>
