<template>
  <NuxtLayout name="default">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div v-if="pending" class="animate-pulse">
        <div class="h-12 bg-gray-200 rounded w-1/3 mb-4"></div>
        <div class="h-6 bg-gray-200 rounded w-1/2 mb-12"></div>
      </div>

      <div v-else-if="categoryData" class="mb-12">
        <div class="flex items-center mb-4">
          <NuxtLink to="/categories" class="text-primary-600 hover:text-primary-900 flex items-center">
            <Icon name="mdi:arrow-left" class="mr-2" />
            Retour aux catégories
          </NuxtLink>
        </div>
        <h1 class="text-5xl font-serif font-bold text-primary-900 mb-4">{{ categoryData.category.name }}</h1>
        <p class="text-xl text-primary-600">{{ categoryData.recipes.length }} recettes disponibles</p>
      </div>

      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="i in 6" :key="i" class="animate-pulse">
          <div class="bg-gray-200 h-64 rounded-2xl mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4"></div>
        </div>
      </div>

      <div v-else-if="categoryData?.recipes.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <RecipeCard v-for="recipe in categoryData.recipes" :key="recipe.id" :recipe="recipe" />
      </div>

      <div v-else class="text-center py-20">
        <Icon name="mdi:food-off" class="text-6xl text-primary-300 mb-4" />
        <p class="text-xl text-primary-600">Aucune recette dans cette catégorie</p>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const route = useRoute()
const api = useApi()

const { data: categoryData, pending } = await useAsyncData(
  `category-${route.params.slug}`, 
  () => api.getCategoryRecipes(route.params.slug)
)
</script>
