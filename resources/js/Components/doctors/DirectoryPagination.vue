<script setup>
import { ChevronLeft, ChevronRight } from '@lucide/vue'

defineProps({
  page: { type: Number, required: true },
  pages: { type: Number, required: true },
  count: { type: Number, required: true },
  shown: { type: Number, required: true },
  english: { type: Boolean, default: false },
})

const emit = defineEmits(['change'])
</script>

<template>
  <div class="directory-bottom">
    <p>{{ english ? `Showing ${shown} of ${count} registered specialists.` : `Mostrando ${shown} de ${count} médicos especialistas registrados.` }}</p>
    <nav v-if="pages > 1" class="directory-pagination" :aria-label="english ? 'Results pages' : 'Páginas de resultados'">
      <button type="button" :disabled="page === 1" :aria-label="english ? 'Previous page' : 'Página anterior'" @click="emit('change', page - 1)"><ChevronLeft :size="16" /></button>
      <button v-for="number in pages" :key="number" type="button" :aria-current="page === number ? 'page' : undefined" @click="emit('change', number)">{{ number }}</button>
      <button type="button" :disabled="page === pages" :aria-label="english ? 'Next page' : 'Página siguiente'" @click="emit('change', page + 1)"><ChevronRight :size="16" /></button>
    </nav>
  </div>
</template>
