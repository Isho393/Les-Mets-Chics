<template>
  <div>
    <NuxtLayout name="default">
      <!-- Hero Section -->
      <section class="relative h-[600px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/40 to-accent-900/40 z-10"></div>
        <div
          class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1920')] bg-cover bg-center">
        </div>

        <div class="relative z-20 text-center text-white px-4 max-w-4xl mx-auto">
          <h1 class="text-5xl md:text-7xl font-serif font-bold mb-6 typewriter">
            {{ displayedText }}
          </h1>
          <p class="text-xl md:text-2xl mb-8 text-primary-100 transition-all duration-1000" :class="showSubtitle ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
            Découvrez des recettes raffinées qui éveilleront vos papilles
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center transition-all duration-1000" :class="showButtons ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'">
            <button @click="handleExploreRecipes" class="btn-hero-primary">
              <Icon name="mdi:silverware-fork-knife" class="mr-2" />
              Explorer les recettes
            </button>
            <button @click="handleViewCategories" class="btn-hero-secondary">
              <Icon name="mdi:view-grid" class="mr-2" />
              Voir les catégories
            </button>
          </div>
        </div>
      </section>

      <!-- Featured Recipes -->
      <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-12">
          <h2 class="text-4xl font-serif font-bold text-primary-900 mb-4">
            Recettes du Moment
          </h2>
          <p class="text-lg text-primary-600">
            Nos créations culinaires les plus appréciées
          </p>
        </div>

        <div v-if="pending" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="i in 6" :key="i" class="animate-pulse">
            <div class="bg-gray-200 h-64 rounded-2xl mb-4"></div>
            <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
          </div>
        </div>

        <div v-else-if="recipes?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(recipe, index) in recipes.slice(0, 6)" :key="recipe.id" @click="handleRecipeClick(recipe.slug)"
            class="cursor-pointer transition-all duration-700"
            :class="showRecipes ? 'opacity-100 scale-100' : 'opacity-0 scale-75'"
            :style="{ transitionDelay: `${index * 350}ms` }">
            <RecipeCard :recipe="recipe" :clickable="false" />
          </div>
        </div>

        <div v-else class="text-center py-12">
          <Icon name="mdi:food-off" class="text-6xl text-primary-300 mb-4" />
          <p class="text-xl text-primary-600">Aucune recette disponible pour le moment</p>
        </div>

        <div class="text-center mt-12">
          <button @click="handleViewAllRecipes" class="btn-primary-large">
            Voir toutes les recettes
            <Icon name="mdi:arrow-right" class="ml-2" />
          </button>
        </div>
      </section>

      <!-- Categories Preview -->
      <section class="bg-gradient-to-br from-primary-50 to-accent-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12">
            <h2 class="text-4xl font-serif font-bold text-primary-900 mb-4">
              Explorez par Catégorie
            </h2>
            <p class="text-lg text-primary-600">
              Trouvez l'inspiration selon vos envies
            </p>
          </div>

          <div class="grid grid-cols-2 md:grid-cols-4 gap-6 h-full">
            <div v-for="category in categories" :key="category.id" @click="handleCategoryClick(category.slug)"
              class="category-card group cursor-pointer flex flex-col items-center justify-center">
              <div
                class="aspect-square rounded-2xl flex items-center justify-center mb-3 object-cover group-hover:scale-110 transition-transform">
                 <img :src='category.image1' alt="images catégories">
              </div>
              <h3 class="font-semibold text-primary-900 text-center">{{ category.name }}</h3>
              <p class="text-sm text-primary-600 text-center">{{ category.recipes_count }} recettes</p>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="bg-green-50 flex items-center justify-center mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-7xl bg-green-200 rounded-3xl p-12 text-center">
          <h2 class="text-4xl font-serif font-bold mb-4">
            Rejoignez Notre Communauté
          </h2>
          <p class="text-xl mb-8">
            Partagez vos créations et découvrez de nouvelles saveurs
          </p>
          <NuxtLink to="/inscription" class="btn-cta">
            S'inscrire gratuitement
            <Icon name="mdi:arrow-right" class="ml-2" />
          </NuxtLink>
        </div>
      </section>
    </NuxtLayout>
  </div>
</template>

<script setup>
import france1 from '../public/categories/Cuisine_française.jpg';
import france2 from '../public/categories/Cuisine_française1.jpg';
import italie1 from '../public/categories/Cuisine_italienne.jpg';
import italie2 from '../public/categories/Cuisine_italienne1.jpg';
import asie1 from '../public/categories/Cuisine_asiatique.jpg';
import asie2 from '../public/categories/Cuisine_asiatique1.jpg';
import dessert1 from '../public/categories/Desert.jpg';
import dessert2 from '../public/categories/Desert1.jpg';
const api = useApi()
const authStore = useAuthStore()

// Typewriter effect
const fullText = "L'Art de la Gastronomie"
const displayedText = ref("")
const showSubtitle = ref(false)
const showButtons = ref(false)
const showRecipes = ref(false)
const typewriterSpeed = 100

onMounted(() => {
  let index = 0
  const typeInterval = setInterval(() => {
    if (index < fullText.length) {
      displayedText.value += fullText[index]
      index++
    } else {
      clearInterval(typeInterval)
      // Déclencher les autres animations après le typewriter
      setTimeout(() => {
        showSubtitle.value = true
      }, 300)
      setTimeout(() => {
        showButtons.value = true
      }, 600)
      // Déclencher l'animation des recettes après un délai
      setTimeout(() => {
        showRecipes.value = true
      }, 1200)
    }
  }, typewriterSpeed)
})

const { data: recipesData, pending } = await useAsyncData('recipes', () =>
  api.getRecipes()
)

const recipes = computed(() => recipesData.value?.recipes || [])
console.log(recipes);


const categories = ref([
  { id: 1, name: 'Française', slug: 'francaise', recipes_count: 12, image1: france1, image2: france2 },
  { id: 2, name: 'Italienne', slug: 'italienne', recipes_count: 8, image1: italie1, image2: italie2 },
  { id: 3, name: 'Asiatique', slug: 'asiatique', recipes_count: 10, image1: asie1, image2: asie2 },
  { id: 4, name: 'Desserts', slug: 'desserts', recipes_count: 15, image1: dessert1, image2:  dessert2}
])

const handleRecipeClick = (slug) => {
  if (!authStore.isAuthenticated) {
    navigateTo('/connexion')
  } else {
    navigateTo(`/recettes/${slug}`)
  }
}

const handleCategoryClick = (slug) => {
  if (!authStore.isAuthenticated) {
    navigateTo('/connexion')
  } else {
    navigateTo(`/categories/${slug}`)
  }
}

const handleExploreRecipes = () => {
  if (authStore.isAuthenticated) {
    navigateTo('/recettes')
  } else {
    navigateTo('/connexion')
  }
}

const handleViewCategories = () => {
  if (authStore.isAuthenticated) {
    navigateTo('/categories')
  } else {
    navigateTo('/connexion')
  }
}

const handleViewAllRecipes = () => {
  if (authStore.isAuthenticated) {
    navigateTo('/recettes')
  } else {
    navigateTo('/connexion')
  }
}
</script>

<style scoped>
.btn-hero-primary {
  @apply inline-flex items-center bg-white text-primary-900 px-8 py-4 rounded-full font-semibold text-lg hover:bg-primary-50 transform hover:scale-105 transition-all shadow-xl;
}

.btn-hero-secondary {
  @apply inline-flex items-center border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white/10 transition-all;
}

.btn-primary-large {
  @apply inline-flex items-center bg-gradient-to-r from-green-900 to-accent-500 text-white px-8 py-4 rounded-full font-semibold text-lg hover:shadow-xl transform hover:-translate-y-1 transition-all;
}

.btn-cta {
  @apply inline-flex items-center bg-gradient-to-r from-green-900 to-accent-500 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-primary-50 transform hover:scale-105 transition-all;
}

.category-card {
  @apply block p-6 bg-white rounded-2xl shadow-md hover:shadow-xl transition-all;
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 1s ease-out;
}

.typewriter {
  border-right: 3px solid white;
  animation: blink 1s infinite;
}

@keyframes blink {
  0%, 50% { border-color: white; }
  51%, 100% { border-color: transparent; }
}
</style>
