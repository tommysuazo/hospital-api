<script setup>
defineProps({
  icon: { type: Object, required: true },
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  detail: { type: String, default: '' },
  tone: { type: String, default: 'primary' },
})
</script>

<template>
  <article class="staff-stat-card">
    <div class="staff-stat-card__top">
      <span class="staff-stat-card__icon" :class="`staff-stat-card__icon--${tone}`"><component :is="icon" :size="21" /></span>
      <small v-if="detail">{{ detail }}</small>
    </div>
    <strong>{{ value }}</strong>
    <span>{{ label }}</span>
  </article>
</template>
