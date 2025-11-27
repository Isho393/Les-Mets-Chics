<template>
  <div 
    :class="[
      'rounded-full overflow-hidden flex items-center justify-center',
      sizeClasses,
      'bg-gradient-to-br from-primary-400 to-accent-400'
    ]"
  >
    <img 
      v-if="avatar" 
      :src="avatar" 
      :alt="name"
      class="w-full h-full object-cover"
    />
    <span 
      v-else 
      :class="['text-white font-semibold', textSizeClass]"
    >
      {{ initials }}
    </span>
  </div>
</template>

<script setup>
const props = defineProps({
  avatar: {
    type: String,
    default: null
  },
  name: {
    type: String,
    required: true
  },
  size: {
    type: String,
    default: 'md', // xs, sm, md, lg, xl
    validator: (value) => ['xs', 'sm', 'md', 'lg', 'xl'].includes(value)
  }
})

const initials = computed(() => {
  const names = props.name.split(' ')
  if (names.length >= 2) {
    return (names[0][0] + names[names.length - 1][0]).toUpperCase()
  }
  return props.name.substring(0, 2).toUpperCase()
})

const sizeClasses = computed(() => {
  const sizes = {
    xs: 'w-6 h-6',
    sm: 'w-8 h-8',
    md: 'w-10 h-10',
    lg: 'w-12 h-12',
    xl: 'w-24 h-24'
  }
  return sizes[props.size]
})

const textSizeClass = computed(() => {
  const sizes = {
    xs: 'text-xs',
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
    xl: 'text-5xl'
  }
  return sizes[props.size]
})
</script>
