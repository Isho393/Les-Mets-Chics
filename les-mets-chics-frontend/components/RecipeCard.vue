<template>
  <component 
    :is="clickable ? 'NuxtLink' : 'div'"
    :to="clickable ? `/recettes/${recipe.slug}` : undefined"
    class="group block bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all transform hover:-translate-y-2"
  >
    <!-- Image -->
    <div class="relative h-64 overflow-hidden bg-primary-100">
      <img 
        :src="recipe.main_image_url || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800'" 
        :alt="recipe.title"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
      />
      <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-primary-900">
        {{ recipe.category.name }}
      </div>
    </div>

    <!-- Content -->
    <div class="p-6">
      <h3 class="text-xl font-serif font-bold text-primary-900 mb-2 group-hover:text-accent-600 transition-colors">
        {{ recipe.title }}
      </h3>
      
      <p class="text-primary-600 text-sm mb-4 line-clamp-2">
        {{ recipe.description }}
      </p>

      <!-- Meta Info -->
      <div class="flex items-center justify-between text-sm text-primary-500 mb-4">
        <div class="flex items-center space-x-4">
          <span v-if="recipe.prep_time" class="flex items-center">
            <Icon name="mdi:clock-outline" class="mr-1" />
            {{ recipe.prep_time }}min
          </span>
          <span v-if="recipe.servings" class="flex items-center">
            <Icon name="mdi:account-group" class="mr-1" />
            {{ recipe.servings }} pers.
          </span>
        </div>
      </div>

      <!-- Author & Stats -->
      <div class="flex items-center justify-between pt-4 border-t border-primary-100">
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-gradient-to-r from-gray-900 to-accent-500 rounded-full flex items-center justify-center">
            <Icon name="mdi:account" class="text-white text-xl" />
          </div>
          <span class="text-sm text-primary-700 font-medium">{{ recipe.author.name }}</span>
        </div>
        
        <div class="flex items-center space-x-3 text-primary-500">
          <span class="flex items-center text-sm">
            <Icon name="mdi:heart" class="mr-1 text-red-500" />
            {{ recipe.likes_count }}
          </span>
          <span class="flex items-center text-sm">
            <Icon name="mdi:comment" class="mr-1" />
            {{ recipe.comments_count }}
          </span>
        </div>
      </div>
    </div>
  </component>
</template>

<script setup>
defineProps({
  recipe: {
    type: Object,
    required: true
  },
  clickable: {
    type: Boolean,
    default: true
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
