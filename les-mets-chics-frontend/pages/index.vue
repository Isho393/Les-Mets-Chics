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

      <!-- Categories Carousel -->
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

          <!-- Carousel Container -->
          <div class="relative">
            <!-- Navigation Buttons -->
            <button 
              @click="prevCategory" 
              class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white rounded-full p-3 shadow-lg transition-all duration-300 hover:scale-110"
              :class="{ 'opacity-50 cursor-not-allowed': currentIndex === 0 }"
            >
              <Icon name="mdi:chevron-left" class="text-2xl text-primary-900" />
            </button>
            
            <button 
              @click="nextCategory" 
              class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white rounded-full p-3 shadow-lg transition-all duration-300 hover:scale-110"
              :class="{ 'opacity-50 cursor-not-allowed': currentIndex >= categories.length - itemsPerView }"
            >
              <Icon name="mdi:chevron-right" class="text-2xl text-primary-900" />
            </button>

            <!-- Carousel Track -->
            <div class="overflow-hidden mx-12">
              <div 
                class="flex transition-transform duration-500 ease-in-out"
                :style="{ transform: `translateX(-${currentIndex * (100 / itemsPerView)}%)` }"
              >
                <div 
                  v-for="category in categories" 
                  :key="category.id"
                  class="flex-shrink-0 px-3"
                  :class="carouselItemClass"
                >
                  <!-- Mobile Layout -->
                  <div 
                    v-if="itemsPerView === 1 && window.innerWidth < 768"
                    @click="handleCategoryClick(category.slug)"
                    class="category-mobile-card group cursor-pointer relative overflow-hidden rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300"
                  >
                    <img 
                      :src="getCategoryImage(category.slug)" 
                      :alt="category.name"
                      class="w-full h-48 object-cover"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex flex-col justify-end p-4">
                      <h3 class="text-xl font-serif font-bold text-white mb-1">
                        {{ category.name }}
                      </h3>
                      <p class="text-sm text-white/80 mb-3">
                        {{ category.recipes_count }} recettes
                      </p>
                      <button class="btn-category-mobile">
                        Découvrir
                      </button>
                    </div>
                  </div>

                  <!-- Desktop/Tablet Diagonal Layout -->
                  <div 
                    v-else
                    @click="handleCategoryClick(category.slug)"
                    class="category-diagonal-card group cursor-pointer relative overflow-hidden rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2"
                  >
                    <!-- Left Triangle - Image -->
                    <div class="absolute inset-0">
                      <div class="diagonal-image-section">
                        <img 
                          :src="getCategoryImage(category.slug)" 
                          :alt="category.name"
                          class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        />
                      </div>
                    </div>

                    <!-- Right Triangle - Content -->
                    <div class="absolute inset-0">
                      <div class="diagonal-content-section bg-gradient-to-br from-primary-50 to-accent-50 flex flex-col justify-center items-end p-6 md:p-8">
                        <h3 class="text-xl md:text-2xl lg:text-3xl font-serif font-bold text-primary-900 mb-2 group-hover:text-accent-600 transition-colors">
                          {{ category.name }}
                        </h3>
                        <p class="text-sm md:text-base text-primary-600 mb-4">
                          {{ category.recipes_count }} recettes
                        </p>
                        <button class="btn-category">
                          Découvrir
                          <Icon name="mdi:arrow-right" class="ml-2 group-hover:translate-x-1 transition-transform" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Dots Indicator -->
            <div class="flex justify-center mt-8 space-x-2">
              <button 
                v-for="(dot, index) in Math.ceil(categories.length / itemsPerView)" 
                :key="index"
                @click="goToSlide(index)"
                class="w-3 h-3 rounded-full transition-all duration-300"
                :class="Math.floor(currentIndex / itemsPerView) === index ? 'bg-primary-900' : 'bg-primary-300 hover:bg-primary-500'"
              ></button>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="bg-green-50 flex items-center justify-center mx-auto px-4 lg:px-8 py-20">
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
const api = useApi()
const authStore = useAuthStore()

// Carousel state
const currentIndex = ref(0)
const itemsPerView = ref(1)

// Responsive items per view
const updateItemsPerView = () => {
  if (process.client) {
    const width = window.innerWidth
    if (width >= 1024) itemsPerView.value = 2      // lg: 2 items
    else if (width >= 768) itemsPerView.value = 1  // md: 1 item
    else itemsPerView.value = 1                    // sm: 1 item
  }
}

// Add window reference for template
const window = process.client ? globalThis.window : { innerWidth: 1024 }

const carouselItemClass = computed(() => {
  return {
    'w-full': itemsPerView.value === 1,
    'w-1/2': itemsPerView.value === 2,
  }
})

// Carousel navigation
const nextCategory = () => {
  if (currentIndex.value < categories.value.length - itemsPerView.value) {
    currentIndex.value++
  }
}

const prevCategory = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--
  }
}

const goToSlide = (index) => {
  currentIndex.value = index * itemsPerView.value
}

// Category images mapping (temporary solution)
const getCategoryImage = (slug) => {
  const imageMap = {
    'francaise': 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800',
    'italienne': 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=800',
    'asiatique': 'https://images.unsplash.com/photo-1617093727343-374698b1b08d?w=800',
    'desserts': 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=800'
  }
  return imageMap[slug] || 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800'
}

// Typewriter effect
const fullText = "L'Art de la Gastronomie"
const displayedText = ref("")
const showSubtitle = ref(false)
const showButtons = ref(false)
const showRecipes = ref(false)
const typewriterSpeed = 100

onMounted(() => {
  updateItemsPerView()
  window.addEventListener('resize', updateItemsPerView)
  
  // Masquer le spinner initial avant de commencer l'animation typewriter
  setTimeout(() => {
    if (window.hideInitialSpinner) {
      window.hideInitialSpinner()
    }
  }, 500)
  
  // Démarrer l'animation typewriter après avoir masqué le spinner
  setTimeout(() => {
    let index = 0
    const typeInterval = setInterval(() => {
      if (index < fullText.length) {
        displayedText.value += fullText[index]
        index++
      } else {
        clearInterval(typeInterval)
        setTimeout(() => showSubtitle.value = true, 300)
        setTimeout(() => showButtons.value = true, 600)
        setTimeout(() => showRecipes.value = true, 900)
      }
    }, typewriterSpeed)
  }, 700)
})

onUnmounted(() => {
  if (process.client) {
    window.removeEventListener('resize', updateItemsPerView)
  }
})

const { data: recipesData, pending } = await useAsyncData('recipes', () =>
  api.getRecipes()
)

const { data: categoriesData } = await useAsyncData('categories', () =>
  api.getCategories()
)

const recipes = computed(() => recipesData.value?.recipes || [])
const categories = computed(() => categoriesData.value?.categories || [])

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

.btn-category {
  @apply inline-flex items-center bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-full font-medium text-sm transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg;
}

.btn-category-mobile {
  @apply inline-flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-4 py-2 rounded-full font-medium text-sm transition-all duration-300 border border-white/30;
} 

.category-mobile-card {
  height: 200px;
}

.category-diagonal-card {
  height: 280px;
  @apply sm:h-80 md:h-96;
}

/* Diagonal Split using CSS clip-path */
.diagonal-image-section {
  @apply absolute inset-0;
  clip-path: polygon(0 0, 60% 0, 40% 100%, 0 100%);
}

.diagonal-content-section {
  @apply absolute inset-0;
  clip-path: polygon(40% 0, 100% 0, 100% 100%, 60% 100%);
}

/* Responsive diagonal adjustments */
@media (max-width: 768px) {
  .diagonal-image-section {
    clip-path: none;
    @apply rounded-t-2xl;
  }
  
  .diagonal-content-section {
    clip-path: none;
    @apply rounded-b-2xl;
    top: 60%;
  }
  
  .category-diagonal-card {
    height: 240px;
  }
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