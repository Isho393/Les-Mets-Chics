<template>
  <div class="min-h-screen">
    
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-cream-50 to-bordeaux-50 py-20 lg:py-32">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-display font-bold text-bordeaux-900 mb-6 animate-fade-in-up">
            Les Mets Chics
          </h1>
          <p class="text-xl md:text-2xl text-charcoal-600 mb-8 max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s">
            Découvrez des recettes gastronomiques raffinées du monde entier
          </p>
          
          <!-- CTA Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up" style="animation-delay: 0.4s">
            <NuxtLink 
              v-if="!isAuthenticated"
              to="/connexion"
              class="btn-primary-lg"
            >
              <Icon name="lucide:chef-hat" class="mr-2" />
              Découvrir les recettes
            </NuxtLink>
            <NuxtLink 
              v-else
              to="/recettes"
              class="btn-primary-lg"
            >
              <Icon name="lucide:chef-hat" class="mr-2" />
              Voir toutes les recettes
            </NuxtLink>
            <NuxtLink 
              to="/a-propos"
              class="btn-outline-lg"
            >
              En savoir plus
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Aperçu des recettes -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-display font-bold text-bordeaux-900 mb-4">
            Recettes Populaires
          </h2>
          <p class="text-lg text-charcoal-600 max-w-2xl mx-auto">
            Un aperçu de nos créations culinaires les plus appréciées
          </p>
        </div>

        <!-- Grille de recettes -->
        <div v-if="featuredRecipes.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <RecipeCard 
            v-for="recipe in featuredRecipes" 
            :key="recipe.id"
            :recipe="recipe"
            :show-actions="false"
            @click="handleRecipeClick(recipe)"
          />
        </div>
        
        <!-- État de chargement -->
        <div v-else-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <ui-skeleton-loader v-for="i in 6" :key="i" class="h-80" />
        </div>
        
        <!-- Aucune recette -->
        <div v-else class="text-center py-12">
          <Icon name="lucide:chef-hat" class="text-6xl text-charcoal-300 mb-4" />
          <p class="text-charcoal-500">Aucune recette disponible pour le moment</p>
        </div>

        <!-- Bouton voir plus -->
        <div class="text-center mt-12">
          <NuxtLink 
            v-if="!isAuthenticated"
            to="/connexion"
            class="btn-primary"
          >
            Voir toutes les recettes
          </NuxtLink>
          <NuxtLink 
            v-else
            to="/recettes"
            class="btn-primary"
          >
            Voir toutes les recettes
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Origines culinaires -->
    <section class="py-16 bg-cream-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-display font-bold text-bordeaux-900 mb-4">
            Origines Culinaires
          </h2>
          <p class="text-lg text-charcoal-600 max-w-2xl mx-auto">
            Explorez les saveurs authentiques du monde entier
          </p>
        </div>

        <!-- Grille des catégories -->
        <div v-if="categories.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          <div 
            v-for="category in categories" 
            :key="category.id"
            @click="handleCategoryClick(category)"
            class="category-card group cursor-pointer"
          >
            <div class="aspect-square bg-gradient-to-br from-bordeaux-100 to-bordeaux-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center hover-lift">
              <Icon name="lucide:globe" class="text-4xl text-bordeaux-600 mb-3 group-hover:scale-110 transition-transform" />
              <h3 class="font-display font-semibold text-bordeaux-900 mb-2">{{ category.name }}</h3>
              <p class="text-sm text-charcoal-600">{{ category.recipes_count || 0 }} recettes</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Final -->
    <section class="py-16 bg-bordeaux-900 text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-display font-bold mb-6">
          Rejoignez notre communauté gourmande
        </h2>
        <p class="text-xl text-bordeaux-100 mb-8">
          Accédez à des centaines de recettes exclusives et partagez vos créations
        </p>
        <NuxtLink 
          v-if="!isAuthenticated"
          to="/inscription"
          class="btn-white-lg"
        >
          <Icon name="lucide:user-plus" class="mr-2" />
          Créer mon compte
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup>
const { isAuthenticated } = useAuth()

// SEO
useHead({
  title: 'Les Mets Chics - Recettes Gastronomiques Raffinées',
  meta: [
    { name: 'description', content: 'Découvrez des recettes gastronomiques raffinées du monde entier. Rejoignez notre communauté de passionnés de cuisine.' }
  ]
})

// État
const loading = ref(true)
const featuredRecipes = ref([])
const categories = ref([])

// Charger les données
onMounted(async () => {
  try {
    // Simuler le chargement des recettes populaires
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Ici vous feriez les vrais appels API
    featuredRecipes.value = [
      { id: 1, title: 'Coq au Vin', image: '/placeholder-recipe.jpg', category: 'Française' },
      { id: 2, title: 'Sushi Traditionnel', image: '/placeholder-recipe.jpg', category: 'Japonaise' },
      { id: 3, title: 'Pasta Carbonara', image: '/placeholder-recipe.jpg', category: 'Italienne' }
    ]
    
    categories.value = [
      { id: 1, name: 'Française', slug: 'francaise', recipes_count: 25 },
      { id: 2, name: 'Japonaise', slug: 'japonaise', recipes_count: 18 },
      { id: 3, name: 'Italienne', slug: 'italienne', recipes_count: 22 },
      { id: 4, name: 'Méditerranéenne', slug: 'mediterraneenne', recipes_count: 15 }
    ]
  } catch (error) {
    console.error('Erreur lors du chargement:', error)
  } finally {
    loading.value = false
  }
})

// Actions
const handleRecipeClick = (recipe) => {
  if (!isAuthenticated.value) {
    navigateTo(`/connexion?redirect=${encodeURIComponent(`/recettes/${recipe.slug || recipe.id}`)}`)
  } else {
    navigateTo(`/recettes/${recipe.slug || recipe.id}`)
  }
}

const handleCategoryClick = (category) => {
  if (!isAuthenticated.value) {
    navigateTo(`/connexion?redirect=${encodeURIComponent(`/categories/${category.slug}`)}`)
  } else {
    navigateTo(`/categories/${category.slug}`)
  }
}
</script>

<style scoped>
.btn-primary-lg {
  @apply px-8 py-4 bg-bordeaux-600 text-white rounded-xl hover:bg-bordeaux-700 transition-colors font-semibold text-lg flex items-center justify-center;
}

.btn-outline-lg {
  @apply px-8 py-4 border-2 border-bordeaux-600 text-bordeaux-600 rounded-xl hover:bg-bordeaux-600 hover:text-white transition-colors font-semibold text-lg;
}

.btn-primary {
  @apply px-6 py-3 bg-bordeaux-600 text-white rounded-lg hover:bg-bordeaux-700 transition-colors font-medium;
}

.btn-white-lg {
  @apply px-8 py-4 bg-white text-bordeaux-900 rounded-xl hover:bg-cream-50 transition-colors font-semibold text-lg flex items-center justify-center;
}

.category-card {
  @apply transition-all duration-300;
}
</style>