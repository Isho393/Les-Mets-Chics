<template>
  <NuxtLayout name="default">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center mb-12">
        <h1 class="text-5xl font-serif font-bold text-primary-900 mb-4">Toutes nos Recettes</h1>
        <p class="text-xl text-primary-600">Explorez notre collection de recettes gastronomiques</p>
      </div>

      <div class="flex flex-col md:flex-row gap-8 mb-8">
        <div class="flex-1">
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Rechercher une recette..." 
            class="w-full px-6 py-4 border-2 border-primary-200 rounded-full focus:ring-2 focus:ring-primary-500 focus:border-transparent"
          />
        </div>
        <select v-model="selectedCategory" class="px-6 py-4 border-2 border-primary-200 rounded-full focus:ring-2 focus:ring-primary-500">
          <option value="">Toutes les catégories</option>
          <option value="francaise">Française</option>
          <option value="italienne">Italienne</option>
          <option value="asiatique">Asiatique</option>
        </select>
      </div>

      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="i in 9" :key="i" class="animate-pulse">
          <div class="bg-gray-200 h-64 rounded-2xl mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
          <div class="h-4 bg-gray-200 rounded w-1/2"></div>
        </div>
      </div>

      <div v-else-if="filteredRecipes.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <RecipeCard v-for="recipe in filteredRecipes" :key="recipe.id" :recipe="recipe" />
      </div>

      <div v-else class="text-center py-20">
        <Icon name="mdi:food-off" class="text-6xl text-primary-300 mb-4" />
        <p class="text-xl text-primary-600">Aucune recette trouvée</p>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
definePageMeta({
  middleware: 'auth'
})

const api = useApi()
const searchQuery = ref('')
const selectedCategory = ref('')

const { data: recipesData, pending } = await useAsyncData('all-recipes', () => api.getRecipes())
const recipes = computed(() => recipesData.value?.recipes || [])

const filteredRecipes = computed(() => {
  let filtered = recipes.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(r => 
      r.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      r.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (selectedCategory.value) {
    filtered = filtered.filter(r => r.category.slug === selectedCategory.value)
  }
  
  return filtered
})
</script>
