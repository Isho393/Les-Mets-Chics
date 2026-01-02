<template>
  <div class="bg-white rounded-2xl shadow-soft hover:shadow-premium transition-all duration-300 overflow-hidden group">
    
    <!-- Image -->
    <div class="relative aspect-[4/3] overflow-hidden">
      <img 
        :src="image" 
        :alt="title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
      />
      
      <!-- Overlay gradient -->
      <div class="absolute inset-0 bg-gradient-to-t from-charcoal-900/60 via-transparent to-transparent"></div>
      
      <!-- Lock overlay pour indiquer le contenu protégé -->
      <div class="absolute inset-0 bg-charcoal-900/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        <div class="bg-white/90 backdrop-blur-sm rounded-full p-3">
          <Icon name="lucide:lock" class="w-6 h-6 text-bordeaux-800" />
        </div>
      </div>
      
      <!-- Badge catégorie -->
      <div class="absolute top-4 left-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/90 backdrop-blur-sm text-bordeaux-800">
          {{ category }}
        </span>
      </div>
      
      <!-- Temps de préparation -->
      <div class="absolute bottom-4 right-4">
        <div class="flex items-center gap-1 px-2 py-1 bg-charcoal-900/60 backdrop-blur-sm rounded-full text-white text-xs">
          <Icon name="lucide:clock" class="w-3 h-3" />
          {{ prepTime }}
        </div>
      </div>
    </div>
    
    <!-- Contenu -->
    <div class="p-6">
      
      <!-- Titre -->
      <h3 class="font-display text-xl font-bold text-bordeaux-800 mb-2 line-clamp-2 group-hover:text-bordeaux-900 transition-colors duration-200">
        {{ title }}
      </h3>
      
      <!-- Description tronquée -->
      <p class="text-charcoal-600 text-sm mb-4 line-clamp-2">
        {{ description }}
      </p>
      
      <!-- Auteur -->
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-gradient-to-br from-bordeaux-200 to-bordeaux-300 rounded-full flex items-center justify-center">
            <Icon name="lucide:chef-hat" class="w-3 h-3 text-bordeaux-800" />
          </div>
          <span class="text-xs text-charcoal-500">{{ author }}</span>
        </div>
        
        <!-- Indicateur de contenu protégé -->
        <div class="flex items-center gap-1 text-xs text-bordeaux-600">
          <Icon name="lucide:eye-off" class="w-3 h-3" />
          <span>Connexion requise</span>
        </div>
      </div>
      
      <!-- Call to action subtil -->
      <div class="mt-4 pt-4 border-t border-cream-200">
        <div class="flex items-center justify-between text-xs">
          <span class="text-charcoal-500">Recette complète disponible</span>
          <div class="flex items-center gap-1 text-bordeaux-700 font-medium">
            <span>Voir plus</span>
            <Icon name="lucide:arrow-right" class="w-3 h-3 group-hover:translate-x-1 transition-transform duration-200" />
          </div>
        </div>
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
  description: {
    type: String,
    required: true
  },
  image: {
    type: String,
    required: true
  },
  category: {
    type: String,
    required: true
  },
  prepTime: {
    type: String,
    required: true
  },
  author: {
    type: String,
    required: true
  }
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>