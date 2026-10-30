<script setup>
defineProps({
  member: { type: Object, required: true },
})
</script>

<template>
  <article class="staff-card">
    <div class="staff-card__image">
      <img :src="member.photo" :alt="member.name" class="staff-card__photo" />
    </div>
    <div class="staff-card__content">
      <h3 class="staff-card__name">{{ member.name }}</h3>
      <p class="staff-card__role">{{ member.role }}</p>
      <span class="staff-card__role-accent"></span>
    </div>
  </article>
</template>
