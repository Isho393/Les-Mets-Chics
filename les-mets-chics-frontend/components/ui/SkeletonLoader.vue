<template>
  <div class="animate-pulse">
    <!-- Recipe Card Skeleton -->
    <div v-if="type === 'recipe-card'" class="bg-white rounded-2xl shadow-soft overflow-hidden">
      <div class="aspect-[4/3] bg-cream-200"></div>
      <div class="p-6 space-y-3">
        <div class="h-4 bg-cream-200 rounded w-3/4"></div>
        <div class="h-3 bg-cream-200 rounded w-full"></div>
        <div class="h-3 bg-cream-200 rounded w-2/3"></div>
        <div class="flex justify-between items-center mt-4">
          <div class="h-3 bg-cream-200 rounded w-1/4"></div>
          <div class="h-3 bg-cream-200 rounded w-1/4"></div>
        </div>
      </div>
    </div>

    <!-- Recipe Grid Skeleton -->
    <div v-else-if="type === 'recipe-grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-for="i in count" :key="i" class="bg-white rounded-2xl shadow-soft overflow-hidden">
        <div class="aspect-[4/3] bg-cream-200"></div>
        <div class="p-6 space-y-3">
          <div class="h-4 bg-cream-200 rounded w-3/4"></div>
          <div class="h-3 bg-cream-200 rounded w-full"></div>
          <div class="h-3 bg-cream-200 rounded w-2/3"></div>
        </div>
      </div>
    </div>

    <!-- Text Lines Skeleton -->
    <div v-else-if="type === 'text'" class="space-y-3">
      <div v-for="i in count" :key="i" class="h-4 bg-cream-200 rounded" :class="getTextWidth(i)"></div>
    </div>

    <!-- Header Skeleton -->
    <div v-else-if="type === 'header'" class="space-y-4">
      <div class="h-8 bg-cream-200 rounded w-1/2 mx-auto"></div>
      <div class="h-4 bg-cream-200 rounded w-3/4 mx-auto"></div>
    </div>

    <!-- Avatar Skeleton -->
    <div v-else-if="type === 'avatar'" class="flex items-center gap-3">
      <div class="w-10 h-10 bg-cream-200 rounded-full"></div>
      <div class="space-y-2">
        <div class="h-3 bg-cream-200 rounded w-20"></div>
        <div class="h-2 bg-cream-200 rounded w-16"></div>
      </div>
    </div>

    <!-- Button Skeleton -->
    <div v-else-if="type === 'button'" class="h-12 bg-cream-200 rounded-xl" :class="buttonWidth"></div>

    <!-- Custom Skeleton -->
    <div v-else :class="customClasses">
      <slot />
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  type: {
    type: String,
    default: 'text',
    validator: (value) => ['recipe-card', 'recipe-grid', 'text', 'header', 'avatar', 'button', 'custom'].includes(value)
  },
  count: {
    type: Number,
    default: 3
  },
  width: {
    type: String,
    default: 'full'
  },
  height: {
    type: String,
    default: 'auto'
  },
  customClasses: {
    type: String,
    default: ''
  }
})

const getTextWidth = (index) => {
  const widths = ['w-full', 'w-5/6', 'w-4/5', 'w-3/4', 'w-2/3']
  return widths[index % widths.length]
}

const buttonWidth = computed(() => {
  const widths = {
    'sm': 'w-24',
    'md': 'w-32',
    'lg': 'w-40',
    'full': 'w-full'
  }
  return widths[props.width] || 'w-full'
})
</script>