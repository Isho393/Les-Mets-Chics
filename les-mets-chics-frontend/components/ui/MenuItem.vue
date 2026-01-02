<template>
  <component
    :is="tag"
    :to="to"
    :href="href"
    :class="itemClasses"
    @click="handleClick"
  >
    <div class="menu-item-content">
      <!-- Icon -->
      <div v-if="icon" class="menu-item-icon">
        <Icon :name="icon" :class="iconClasses" />
      </div>
      
      <!-- Content -->
      <div class="menu-item-text">
        <span class="menu-item-label">{{ label }}</span>
        <span v-if="description" class="menu-item-description">{{ description }}</span>
      </div>
      
      <!-- Badge -->
      <div v-if="badge" class="menu-item-badge">
        <span :class="badgeClasses">{{ badge }}</span>
      </div>
      
      <!-- Arrow -->
      <div v-if="hasArrow" class="menu-item-arrow">
        <Icon name="lucide:chevron-right" class="w-4 h-4 text-charcoal-400 transition-transform duration-200 group-hover:translate-x-1" />
      </div>
    </div>
  </component>
</template>

<script setup>
const props = defineProps({
  label: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  },
  icon: {
    type: String,
    default: ''
  },
  badge: {
    type: [String, Number],
    default: ''
  },
  to: {
    type: String,
    default: ''
  },
  href: {
    type: String,
    default: ''
  },
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'primary', 'danger', 'ghost'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  isActive: {
    type: Boolean,
    default: false
  },
  hasArrow: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

defineEmits(['click'])

// Déterminer le tag à utiliser
const tag = computed(() => {
  if (props.to) return 'NuxtLink'
  if (props.href) return 'a'
  return 'button'
})

// Classes du conteneur
const itemClasses = computed(() => [
  'menu-item group',
  'flex items-center w-full text-left transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-bordeaux-500 focus:ring-offset-2',
  // Tailles
  {
    'px-3 py-2 rounded-lg': props.size === 'sm',
    'px-4 py-3 rounded-xl': props.size === 'md',
    'px-6 py-4 rounded-xl': props.size === 'lg'
  },
  // Variants
  {
    'text-charcoal-700 hover:text-bordeaux-800 hover:bg-bordeaux-50': props.variant === 'default' && !props.isActive,
    'text-white bg-bordeaux-700 hover:bg-bordeaux-800': props.variant === 'primary' || props.isActive,
    'text-red-700 hover:text-red-800 hover:bg-red-50': props.variant === 'danger',
    'text-charcoal-600 hover:text-charcoal-800 hover:bg-transparent': props.variant === 'ghost'
  },
  // États
  {
    'opacity-50 cursor-not-allowed': props.disabled,
    'bg-bordeaux-100 text-bordeaux-900': props.isActive && props.variant === 'default'
  }
])

// Classes de l'icône
const iconClasses = computed(() => [
  'w-5 h-5 transition-colors duration-200',
  {
    'text-charcoal-500 group-hover:text-bordeaux-700': props.variant === 'default' && !props.isActive,
    'text-white': props.variant === 'primary' || props.isActive,
    'text-red-500 group-hover:text-red-700': props.variant === 'danger',
    'text-bordeaux-700': props.isActive && props.variant === 'default'
  }
])

// Classes du badge
const badgeClasses = computed(() => [
  'inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full',
  {
    'bg-bordeaux-100 text-bordeaux-800': props.variant === 'default',
    'bg-white/20 text-white': props.variant === 'primary',
    'bg-red-100 text-red-800': props.variant === 'danger'
  }
])

// Gestion du clic
const handleClick = (event) => {
  if (props.disabled) {
    event.preventDefault()
    return
  }
  emit('click', event)
}
</script>

<style scoped>
.menu-item-content {
  @apply flex items-center w-full gap-3;
}

.menu-item-text {
  @apply flex-1 min-w-0;
}

.menu-item-label {
  @apply block font-medium;
}

.menu-item-description {
  @apply block text-sm opacity-75 mt-0.5;
}

.menu-item-icon {
  @apply flex-shrink-0;
}

.menu-item-badge {
  @apply flex-shrink-0;
}

.menu-item-arrow {
  @apply flex-shrink-0;
}

/* Touch targets pour mobile */
@media (hover: none) and (pointer: coarse) {
  .menu-item {
    min-height: 44px;
  }
}
</style>