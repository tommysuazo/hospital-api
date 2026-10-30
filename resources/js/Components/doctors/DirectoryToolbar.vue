<script setup>
import { LayoutGrid, List } from '@lucide/vue'

defineProps({
  count: { type: Number, required: true },
  shown: { type: Number, required: true },
  sort: { type: String, required: true },
  view: { type: String, required: true },
  english: { type: Boolean, default: false },
})

const emit = defineEmits(['update-sort', 'update-view'])
</script>

<template>
  <div class="directory-toolbar">
    <div class="directory-toolbar__count">
      <h2 aria-live="polite">{{ count }} {{ english ? 'specialists found' : 'especialistas encontrados' }}</h2>
      <span>{{ english ? `Showing ${shown}` : `Mostrando ${shown}` }}</span>
    </div>
    <div class="directory-toolbar__controls">
      <label>
        {{ english ? 'Sort by:' : 'Ordenar por:' }}
        <select :value="sort" @change="emit('update-sort', $event.target.value)">
          <option value="available">{{ english ? 'Next consultation day' : 'Próximo día de consulta' }}</option>
          <option value="name">{{ english ? 'Name A–Z' : 'Nombre A–Z' }}</option>
        </select>
      </label>
      <div class="directory-toolbar__views">
        <button type="button" :aria-label="english ? 'Grid view' : 'Vista de tarjetas'" :aria-pressed="view === 'grid'" @click="emit('update-view', 'grid')"><LayoutGrid :size="18" /></button>
        <button type="button" :aria-label="english ? 'List view' : 'Vista de lista'" :aria-pressed="view === 'list'" @click="emit('update-view', 'list')"><List :size="18" /></button>
      </div>
    </div>
  </div>
</template>
