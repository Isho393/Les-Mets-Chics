<template>
  <div 
    :class="cardClasses"
    @mouseenter="handleHover(true)"
    @mouseleave="handleHover(false)"
  >
    <!-- Image Container -->
    <div class="relative overflow-hidden" :class="imageContainerClasses">
      <img 
        :src="image" 
        :alt="title"
        class="w-full h-full object-cover transition-transform duration-700 ease-out"
        :class="{ 'scale-110': isHovered }"
        loading="lazy"
      />
      
      <!-- Overlay Gradient -->
      <div class="absolute inset-0 bg-gradient-to-t from-charcoal-900/60 via-transparent to-transparent opacity-0 transition-opacity duration-300"
           :class="{ 'opacity-100': isHovered }" />
      
      <!-- Badge/Category -->
      <div v-if="category" class="absolute top-4 left-4">
        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-bordeaux-800 text-sm font-medium rounded-full">
          {{ category }}
        </span>
      </div>
      
      <!-- Like Button -->
      <button v-if="showLike" 
              class="absolute top-4 right-4 p-2 bg-white/90 backdrop-blur-sm rounded-full transition-all duration-300 hover:bg-white hover:scale-110"
              @click.stop="$emit('like')">
        <Icon name="lucide:heart" :class="liked ? 'text-bordeaux-800 fill-current' : 'text-charcoal-600'" />
      </button>
    </div>
    
    <!-- Content -->
    <div :class="contentClasses">
      <!-- Title -->
      <h3 class="font-display text-xl font-semibold text-charcoal-900 mb-2 line-clamp-2">
        {{ title }}
      </h3>
      
      <!-- Description -->
      <p v-if="description" class="text-charcoal-600 text-sm mb-4 line-clamp-3">
        {{ description }}
      </p>
      
      <!-- Meta Info -->
      <div v-if="showMeta" class="flex items-center gap-4 text-xs text-charcoal-500 mb-4">
        <div v-if="prepTime" class="flex items-center gap-1">
          <Icon name="lucide:clock" class="w-3 h-3" />
          <span>{{ prepTime }}</span>
        </div>
        <div v-if="servings" class="flex items-center gap-1">
          <Icon name="lucide:users" class="w-3 h-3" />
          <span>{{ servings }} pers.</span>
        </div>
        <div v-if="difficulty" class="flex items-center gap-1">
          <Icon name="lucide:chef-hat" class="w-3 h-3" />
          <span>{{ difficulty }}</span>
        </div>
      </div>
      
      <!-- Author -->
      <div v-if="author" class="flex items-center gap-3">
        <UserAvatar 
          :avatar="author.avatar" 
          :name="author.name" 
          size="sm" 
        />
        <div>
          <p class="text-sm font-medium text-charcoal-800">{{ author.name }}</p>
          <p v-if="publishedAt" class="text-xs text-charcoal-500">{{ formatDate(publishedAt) }}</p>
        </div>
      </div>
      
      <!-- Action Button -->
      <div v-if="showAction" class="mt-4">
        <Button 
          variant="outline" 
          size="sm" 
          full-width
        >
          {{ actionText || 'Voir la recette' }}
        </Button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  // Contenu
  title: { type: String, required: true },
  description: String,
  image: { type: String, required: true },
  category: String,
  
  // Meta
  prepTime: String,
  servings: [String, Number],
  difficulty: String,
  publishedAt: String,
  
  // Auteur
  author: Object,
  
  // Interactions
  liked: Boolean,
  showLike: { type: Boolean, default: true },
  showMeta: { type: Boolean, default: true },
  showAction: { type: Boolean, default: true },
  actionText: String,
  
  // Variantes
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'featured', 'compact'].includes(value)
  },
  
  // Layout
  aspectRatio: {
    type: String,
    default: '4/3',
    validator: (value) => ['1/1', '4/3', '16/9', '3/4'].includes(value)
  }
})

const emit = defineEmits(['like'])

const isHovered = ref(false)

const handleHover = (hovered) => {
  isHovered.value = hovered
}

const cardClasses = computed(() => {
  const baseClasses = [
    'group bg-white rounded-2xl overflow-hidden',
    'transition-all duration-500 ease-out',
    'hover:shadow-premium hover:-translate-y-2'
  ]
  
  const variants = {
    default: ['shadow-soft'],
    featured: ['shadow-premium border border-gold-200'],
    compact: ['shadow-soft']
  }
  
  return [...baseClasses, ...variants[props.variant]].join(' ')
})

const imageContainerClasses = computed(() => {
  const aspectRatios = {
    '1/1': 'aspect-square',
    '4/3': 'aspect-[4/3]',
    '16/9': 'aspect-video',
    '3/4': 'aspect-[3/4]'
  }
  
  return aspectRatios[props.aspectRatio] || 'aspect-[4/3]'
})

const contentClasses = computed(() => {
  return props.variant === 'compact' ? 'p-4' : 'p-6'
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>