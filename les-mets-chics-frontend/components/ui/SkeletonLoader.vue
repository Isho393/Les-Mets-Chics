<template>
  <div 
    :class="containerClasses"
    :style="{ height: height }"
  >
    <!-- Image Skeleton -->
    <div v-if="showImage" :class="imageClasses">
      <div class="w-full h-full bg-gradient-to-r from-charcoal-200 via-charcoal-100 to-charcoal-200 animate-shimmer"></div>
    </div>
    
    <!-- Content Skeleton -->
    <div v-if="showContent" :class="contentClasses">
      <!-- Title Lines -->
      <div v-for="i in titleLines" :key="`title-${i}`" class="mb-3">
        <div 
          class="h-4 bg-gradient-to-r from-charcoal-200 via-charcoal-100 to-charcoal-200 rounded animate-shimmer"
          :style="{ width: i === titleLines ? '75%' : '100%' }"
        ></div>
      </div>
      
      <!-- Description Lines -->
      <div v-for="i in descriptionLines" :key="`desc-${i}`" class="mb-2">
        <div 
          class="h-3 bg-gradient-to-r from-charcoal-200 via-charcoal-100 to-charcoal-200 rounded animate-shimmer"
          :style="{ 
            width: i === descriptionLines ? '60%' : '100%',
            animationDelay: i * 0.1 + 's'
          }"
        ></div>
      </div>
      
      <!-- Meta Info -->
      <div v-if="showMeta" class="flex items-center gap-4 mt-4">
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 bg-charcoal-200 rounded-full animate-shimmer"></div>
          <div class="w-12 h-3 bg-charcoal-200 rounded animate-shimmer"></div>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-4 h-4 bg-charcoal-200 rounded-full animate-shimmer"></div>
          <div class="w-16 h-3 bg-charcoal-200 rounded animate-shimmer"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  variant: {
    type: String,
    default: 'card'
  },
  height: {
    type: String,
    default: 'auto'
  },
  showImage: {
    type: Boolean,
    default: true
  },
  showContent: {
    type: Boolean,
    default: true
  },
  showMeta: {
    type: Boolean,
    default: true
  },
  titleLines: {
    type: Number,
    default: 2
  },
  descriptionLines: {
    type: Number,
    default: 3
  },
  aspectRatio: {
    type: String,
    default: '4/3'
  }
})

const containerClasses = computed(() => {
  return 'bg-white rounded-2xl overflow-hidden shadow-soft animate-pulse'
})

const imageClasses = computed(() => {
  const aspectRatios = {
    '1/1': 'aspect-square',
    '4/3': 'aspect-[4/3]',
    '16/9': 'aspect-video'
  }
  return `${aspectRatios[props.aspectRatio] || 'aspect-[4/3]'} overflow-hidden`
})

const contentClasses = computed(() => {
  return 'p-6'
})
</script>

<style scoped>
@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

.animate-shimmer {
  background-size: 200% 100%;
  animation: shimmer 1.5s ease-in-out infinite;
}
</style>