<template>
  <div :class="sectionClasses">
    <!-- Header de section -->
    <div v-if="title || $slots.header" class="menu-section-header">
      <slot name="header">
        <h3 v-if="title" class="menu-section-title">{{ title }}</h3>
        <p v-if="description" class="menu-section-description">{{ description }}</p>
      </slot>
    </div>
    
    <!-- Contenu de la section -->
    <div :class="contentClasses">
      <slot />
    </div>
    
    <!-- Footer de section -->
    <div v-if="$slots.footer" class="menu-section-footer">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: {
    type: String,
    default: ''
  },
  description: {
    type: String,
    default: ''
  },
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'highlighted', 'compact'].includes(value)
  },
  spacing: {
    type: String,
    default: 'normal',
    validator: (value) => ['tight', 'normal', 'loose'].includes(value)
  },
  divider: {
    type: Boolean,
    default: false
  }
})

const sectionClasses = computed(() => [
  'menu-section',
  {
    // Variants
    'bg-transparent': props.variant === 'default',
    'bg-gradient-to-r from-bordeaux-50 to-gold-50 rounded-xl p-4': props.variant === 'highlighted',
    'space-y-1': props.variant === 'compact',
    
    // Spacing
    'space-y-2': props.spacing === 'tight' && props.variant !== 'compact',
    'space-y-3': props.spacing === 'normal' && props.variant !== 'compact',
    'space-y-4': props.spacing === 'loose' && props.variant !== 'compact',
    
    // Divider
    'border-t border-cream-200 pt-4 mt-4': props.divider
  }
])

const contentClasses = computed(() => [
  'menu-section-content',
  {
    'space-y-1': props.variant === 'compact',
    'space-y-2': props.variant !== 'compact'
  }
])
</script>

<style scoped>
.menu-section-header {
  @apply mb-3;
}

.menu-section-title {
  @apply text-sm font-semibold text-bordeaux-800 uppercase tracking-wide;
}

.menu-section-description {
  @apply text-xs text-charcoal-600 mt-1;
}

.menu-section-footer {
  @apply mt-4 pt-3 border-t border-cream-200;
}

/* Variant highlighted */
.menu-section.bg-gradient-to-r .menu-section-title {
  @apply text-bordeaux-900;
}

.menu-section.bg-gradient-to-r .menu-section-description {
  @apply text-charcoal-700;
}
</style>