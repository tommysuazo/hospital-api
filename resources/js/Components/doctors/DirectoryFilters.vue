<script setup>
import { RotateCcw, Search, Stethoscope } from '@lucide/vue'
import DirectoryDatePicker from './DirectoryDatePicker.vue'

defineProps({
  filters: { type: Object, required: true },
  english: { type: Boolean, default: false },
  specialties: { type: Array, default: () => [] },
})

const emit = defineEmits(['update-filter', 'search', 'clear'])
const text = (english, es, en) => (english ? en : es)
</script>

<template>
  <form class="directory-filters" @submit.prevent="emit('search')">
    <div class="directory-filters__row">
      <label class="directory-field directory-field--search">
        <Search :size="18" />
        <input
          :aria-label="text(english, 'Buscar doctor', 'Search doctors')"
          :placeholder="text(english, 'Buscar por médico o subespecialidad…', 'Search by doctor or subspecialty…')"
          :value="filters.search"
          @input="emit('update-filter', 'search', $event.target.value)"
        />
      </label>
      <label class="directory-field">
        <Stethoscope :size="18" />
        <select
          :aria-label="text(english, 'Especialidad', 'Specialty')"
          :value="filters.specialty"
          @change="emit('update-filter', 'specialty', $event.target.value)"
        >
          <option value="">{{ text(english, 'Todas las especialidades', 'All specialties') }}</option>
          <option v-for="specialty in specialties" :key="specialty" :value="specialty">{{ specialty }}</option>
        </select>
      </label>
      <DirectoryDatePicker
        :value="filters.date"
        :english="english"
        @change="emit('update-filter', 'date', $event)"
      />
      <button class="directory-search" type="submit" :aria-label="text(english, 'Buscar', 'Search')">
        <Search :size="20" />
      </button>
    </div>
    <div class="directory-filters__footer">
      <button type="button" class="directory-clear" @click="emit('clear')">
        <RotateCcw :size="14" />{{ text(english, 'Limpiar filtros', 'Clear filters') }}
      </button>
    </div>
  </form>
</template>
