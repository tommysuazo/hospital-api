<script setup>
import { RotateCcw, Search, Stethoscope } from '@lucide/vue'
import DirectoryDatePicker from './DirectoryDatePicker.vue'

defineProps({
  filters: { type: Object, required: true },
  english: { type: Boolean, default: false },
  medicalAreas: { type: Array, default: () => [] },
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
          :placeholder="text(english, 'Buscar por médico o área médica…', 'Search by doctor or medical area…')"
          :value="filters.search"
          @input="emit('update-filter', 'search', $event.target.value)"
        />
      </label>
      <label class="directory-field">
        <Stethoscope :size="18" />
        <select
          :aria-label="text(english, 'Área médica', 'Medical area')"
          :value="filters.medicalArea"
          @change="emit('update-filter', 'medicalArea', $event.target.value)"
        >
          <option value="">{{ text(english, 'Todas las áreas médicas', 'All medical areas') }}</option>
          <option v-for="medicalArea in medicalAreas" :key="medicalArea" :value="medicalArea">{{ medicalArea }}</option>
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
