<template>
  <button
    :class="buttonClasses"
    @click="$emit('toggle')"
    :aria-label="isOpen ? 'Fermer le menu' : 'Ouvrir le menu'"
    :aria-expanded="isOpen"
  >
    <div class="hamburger-lines">
      <span class="line line-1" :class="{ 'open': isOpen }"></span>
      <span class="line line-2" :class="{ 'open': isOpen }"></span>
      <span class="line line-3" :class="{ 'open': isOpen }"></span>
    </div>
  </button>
</template>

<script setup>
const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'light', 'dark'].includes(value)
  }
})

defineEmits(['toggle'])

const buttonClasses = computed(() => [
  'hamburger-button',
  'relative w-10 h-10 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-bordeaux-500',
  {
    'bg-transparent hover:bg-bordeaux-50': props.variant === 'default',
    'bg-white/10 hover:bg-white/20': props.variant === 'light',
    'bg-charcoal-800 hover:bg-charcoal-700': props.variant === 'dark'
  }
])
</script>

<style scoped>
.hamburger-lines {
  @apply flex flex-col justify-center items-center w-6 h-6 mx-auto;
}

.line {
  @apply block w-5 h-0.5 bg-bordeaux-800 transition-all duration-300 ease-in-out;
  transform-origin: center;
}

.line:not(:last-child) {
  @apply mb-1;
}

/* Animation hamburger → X */
.line-1.open {
  transform: translateY(6px) rotate(45deg);
}

.line-2.open {
  opacity: 0;
  transform: scaleX(0);
}

.line-3.open {
  transform: translateY(-6px) rotate(-45deg);
}

/* Variants */
.hamburger-button .line {
  @apply bg-bordeaux-800;
}

.hamburger-button[data-variant="light"] .line {
  @apply bg-white;
}

.hamburger-button[data-variant="dark"] .line {
  @apply bg-white;
}
</style>