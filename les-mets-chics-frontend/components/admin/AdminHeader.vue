<template>
  <div class="mb-8">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-display font-bold text-gray-900">{{ title }}</h1>
        <p v-if="subtitle" class="mt-2 text-gray-600">{{ subtitle }}</p>
      </div>
      
      <div v-if="$slots.default" class="flex items-center space-x-3">
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  }
})
</script>