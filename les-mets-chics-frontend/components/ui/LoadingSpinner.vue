<template>
  <div class="flex items-center justify-center" :class="containerClasses">
    <div class="relative">
      <!-- Spinner -->
      <div 
        class="animate-spin rounded-full border-solid"
        :class="spinnerClasses"
      ></div>
      
      <!-- Center dot (optionnel) -->
      <div 
        v-if="showCenterDot"
        class="absolute inset-0 flex items-center justify-center"
      >
        <div class="w-2 h-2 bg-current rounded-full opacity-60"></div>
      </div>
    </div>
    
    <!-- Text -->
    <span v-if="text" class="ml-3 font-medium" :class="textClasses">
      {{ text }}
    </span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'white', 'gold'].includes(value)
  },
  text: {
    type: String,
    default: ''
  },
  showCenterDot: {
    type: Boolean,
    default: false
  },
  fullScreen: {
    type: Boolean,
    default: false
  }
})

const containerClasses = computed(() => {
  const classes = []
  
  if (props.fullScreen) {
    classes.push('fixed inset-0 bg-white/80 backdrop-blur-sm z-50')
  }
  
  return classes
})

const spinnerClasses = computed(() => {
  const classes = []
  
  // Tailles
  const sizes = {
    xs: 'w-4 h-4 border-2',
    sm: 'w-5 h-5 border-2',
    md: 'w-6 h-6 border-2',
    lg: 'w-8 h-8 border-3',
    xl: 'w-12 h-12 border-4'
  }
  
  classes.push(sizes[props.size])
  
  // Variants (couleurs)
  const variants = {
    primary: 'border-bordeaux-200 border-t-bordeaux-800',
    secondary: 'border-charcoal-200 border-t-charcoal-600',
    white: 'border-white/30 border-t-white',
    gold: 'border-gold-200 border-t-gold-600'
  }
  
  classes.push(variants[props.variant])
  
  return classes
})

const textClasses = computed(() => {
  const variants = {
    primary: 'text-bordeaux-800',
    secondary: 'text-charcoal-600',
    white: 'text-white',
    gold: 'text-gold-600'
  }
  
  return variants[props.variant]
})
</script>