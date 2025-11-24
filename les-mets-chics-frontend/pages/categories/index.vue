<template>
  <NuxtLayout name="default">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center mb-12">
        <h1 class="text-5xl font-serif font-bold text-primary-900 mb-4">Catégories</h1>
        <p class="text-xl text-primary-600">Découvrez nos recettes par catégorie</p>
      </div>

      <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="i in 8" :key="i" class="animate-pulse">
          <div class="bg-gray-200 h-48 rounded-2xl mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-3/4"></div>
        </div>
      </div>

      <div v-else-if="categories?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <NuxtLink 
          v-for="category in categories" 
          :key="category.id"
          :to="`/categories/${category.slug}`"
          class="group"
        >
          <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all">
            <div class="aspect-square bg-gradient-to-br from-primary-400 to-accent-400 flex items-center justify-center">
              <Icon name="mdi:chef-hat" class="text-white text-7xl group-hover:scale-110 transition-transform" />
            </div>
            <div class="p-6">
              <h3 class="text-2xl font-serif font-bold text-primary-900 mb-2">{{ category.name }}</h3>
              <p class="text-primary-600">{{ category.recipes_count }} recettes</p>
              <p v-if="category.description" class="text-sm text-primary-500 mt-2">{{ category.description }}</p>
            </div>
          </div>
        </NuxtLink>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const api = useApi()
const { data: categoriesData, pending } = await useAsyncData('categories', () => api.getCategories())
const categories = computed(() => categoriesData.value?.categories || [])
</script>
