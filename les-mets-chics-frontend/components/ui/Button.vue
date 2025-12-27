<template>
  <component
    :is="tag"
    :class="buttonClasses"
    :disabled="disabled || loading"
    v-bind="$attrs"
    @click="handleClick"
  >
    <Icon v-if="loading" name="lucide:loader-2" class="animate-spin" />
    <Icon v-else-if="icon && iconPosition === 'left'" :name="icon" />
    
    <span v-if="$slots.default" :class="{ 'opacity-0': loading }">
      <slot />
    </span>
    
    <Icon v-if="icon && iconPosition === 'right'" :name="icon" />
  </component>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'outline', 'ghost', 'gold'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
  },
  tag: {
    type: String,
    default: 'button'
  },
  icon: String,
  iconPosition: {
    type: String,
    default: 'left',
    validator: (value) => ['left', 'right'].includes(value)
  },
  loading: Boolean,
  disabled: Boolean,
  fullWidth: Boolean
})

const emit = defineEmits(['click'])

const handleClick = (event) => {
  if (!props.disabled && !props.loading) {
    emit('click', event)
  }
}

const buttonClasses = computed(() => {
  const baseClasses = [
    'inline-flex items-center justify-center gap-2',
    'font-medium transition-all duration-300 ease-out',
    'focus:outline-none focus:ring-2 focus:ring-offset-2',
    'disabled:opacity-50 disabled:cursor-not-allowed',
    'transform hover:scale-[1.02] active:scale-[0.98]'
  ]

  // Variantes
  const variants = {
    primary: [
      'bg-bordeaux-800 text-white',
      'hover:bg-bordeaux-700 hover:shadow-premium',
      'focus:ring-bordeaux-500'
    ],
    secondary: [
      'bg-cream-100 text-bordeaux-800 border border-cream-200',
      'hover:bg-cream-200 hover:shadow-soft',
      'focus:ring-cream-300'
    ],
    outline: [
      'border-2 border-bordeaux-800 text-bordeaux-800 bg-transparent',
      'hover:bg-bordeaux-800 hover:text-white hover:shadow-premium',
      'focus:ring-bordeaux-500'
    ],
    ghost: [
      'text-bordeaux-800 bg-transparent',
      'hover:bg-cream-100 hover:text-bordeaux-900',
      'focus:ring-cream-300'
    ],
    gold: [
      'bg-gradient-to-r from-gold-600 to-gold-700 text-white',
      'hover:from-gold-700 hover:to-gold-800 hover:shadow-gold',
      'focus:ring-gold-500'
    ]
  }

  // Tailles
  const sizes = {
    sm: ['px-3 py-1.5 text-sm rounded-lg', 'h-8'],
    md: ['px-4 py-2 text-base rounded-xl', 'h-10'],
    lg: ['px-6 py-3 text-lg rounded-xl', 'h-12'],
    xl: ['px-8 py-4 text-xl rounded-2xl', 'h-14']
  }

  const classes = [
    ...baseClasses,
    ...variants[props.variant],
    ...sizes[props.size]
  ]

  if (props.fullWidth) {
    classes.push('w-full')
  }

  return classes.join(' ')
})
</script>