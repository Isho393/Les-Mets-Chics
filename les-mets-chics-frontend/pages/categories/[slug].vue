<template>
  <div class="min-h-screen">
    <!-- Hero Catégorie -->
    <section class="relative h-96 overflow-hidden">
      <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-black/70 z-10"></div>
        <img 
          :src="categoryHero"
          :alt="category?.name"
          class="w-full h-full object-cover scale-110 animate-slow-zoom"
        />
      </div>
      
      <div class="relative z-20 h-full flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
          <div class="text-center">
            <div class="text-6xl mb-4 animate-float">{{ categoryEmoji }}</div>
            <h1 class="text-4xl md:text-6xl font-display font-bold text-white mb-4 animate-fade-in-up">
              Cuisine {{ category?.name || route.params.slug }}
            </h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s">
              {{ categoryDescription }}
            </p>
            <div class="flex justify-center gap-6 mt-6 text-gold-400 animate-fade-in-up" style="animation-delay: 0.4s">
              <div class="text-center">
                <div class="text-2xl font-bold">{{ recipesCount }}</div>
                <div class="text-sm">Recettes</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold">{{ chefsCount }}</div>
                <div class="text-sm">Chefs</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Filtres & Tri -->
    <section class="py-8 bg-white border-b border-cream-200 sticky top-16 z-40 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
          <!-- Filtres -->
          <div class="flex flex-wrap items-center gap-4">
            <select v-model="filters.difficulty" class="filter-select">
              <option value="">Toutes difficultés</option>
              <option value="facile">Facile</option>
              <option value="moyen">Moyen</option>
              <option value="difficile">Difficile</option>
            </select>
            
            <select v-model="filters.time" class="filter-select">
              <option value="">Tous temps</option>
              <option value="15">Moins de 15 min</option>
              <option value="30">Moins de 30 min</option>
              <option value="60">Moins d'1h</option>
            </select>
          </div>
          
          <!-- Tri -->
          <div class="flex items-center gap-4">
            <span class="text-sm text-charcoal-600">Trier par:</span>
            <select v-model="sortBy" class="filter-select">
              <option value="popular">Popularité</option>
              <option value="recent">Plus récent</option>
              <option value="time">Temps de préparation</option>
              <option value="rating">Note</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <!-- Grille de Recettes -->
    <section class="py-16 bg-gradient-to-b from-white to-cream-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Recettes Featured -->
        <div v-if="featuredRecipes.length" class="mb-16">
          <h2 class="text-3xl font-display font-bold text-bordeaux-900 mb-8 text-center">
            Sélection du Chef
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div v-for="recipe in featuredRecipes" :key="recipe.id" 
                 class="group cursor-pointer" @click="goToRecipe(recipe)">
              <div class="relative h-80 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                <img :src="recipe.image" :alt="recipe.title" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                
                <div class="absolute top-4 left-4">
                  <span class="px-3 py-1 bg-gold-500 text-white rounded-full text-sm font-bold">
                    <Icon name="lucide:star" class="mr-1" />
                    Featured
                  </span>
                </div>
                
                <div class="absolute bottom-0 left-0 right-0 p-6">
                  <h3 class="text-2xl font-display font-bold text-white mb-2">{{ recipe.title }}</h3>
                  <div class="flex items-center text-gold-400 text-sm">
                    <Icon name="lucide:clock" class="mr-1" />
                    <span class="mr-4">{{ recipe.time }}</span>
                    <Icon name="lucide:users" class="mr-1" />
                    <span>{{ recipe.servings }} pers.</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Toutes les Recettes -->
        <div class="mb-8">
          <h2 class="text-2xl font-display font-bold text-bordeaux-900 mb-2">
            Toutes les Recettes
          </h2>
          <p class="text-charcoal-600">{{ filteredRecipes.length }} recettes trouvées</p>
        </div>

        <!-- Grille -->
        <div v-if="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <RecipeCard 
            v-for="recipe in paginatedRecipes" 
            :key="recipe.id"
            :recipe="recipe"
            @click="goToRecipe(recipe)"
            class="recipe-card-animate"
          />
        </div>

        <!-- Loading -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <ui-skeleton-loader v-for="i in 9" :key="i" />
        </div>

        <!-- Pagination -->
        <div v-if="totalPages > 1" class="flex justify-center mt-12">
          <div class="flex items-center gap-2">
            <button 
              v-for="page in totalPages" 
              :key="page"
              @click="currentPage = page"
              :class="paginationButtonClass(page)"
            >
              {{ page }}
            </button>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const route = useRoute()
const { isAuthenticated } = useAuth()

// État
const loading = ref(true)
const currentPage = ref(1)
const itemsPerPage = 9
const filters = ref({
  difficulty: '',
  time: ''
})
const sortBy = ref('popular')

// Données par catégorie
const categoryData = {
  francaise: {
    name: 'Française',
    emoji: '🇫🇷',
    description: 'L\'élégance culinaire française dans toute sa splendeur',
    hero: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1920&h=600&fit=crop'
  },
  japonaise: {
    name: 'Japonaise', 
    emoji: '🇯🇵',
    description: 'L\'art de la précision et de la pureté des saveurs',
    hero: 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=1920&h=600&fit=crop'
  },
  italienne: {
    name: 'Italienne',
    emoji: '🇮🇹', 
    description: 'La passion méditerranéenne sublimée',
    hero: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=1920&h=600&fit=crop'
  }
}

// Computed
const category = computed(() => categoryData[route.params.slug])
const categoryEmoji = computed(() => category.value?.emoji || '🍽️')
const categoryDescription = computed(() => category.value?.description || 'Découvrez des recettes d\'exception')
const categoryHero = computed(() => category.value?.hero || 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1920&h=600&fit=crop')

// Données de démonstration
const recipesCount = ref(156)
const chefsCount = ref(12)

const featuredRecipes = ref([
  {
    id: 1,
    title: 'Coq au Vin Moderne',
    image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop',
    time: '2h30',
    servings: 6
  },
  {
    id: 2,
    title: 'Bouillabaisse Revisitée',
    image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop',
    time: '1h45',
    servings: 4
  }
])

const allRecipes = ref([
  { id: 3, title: 'Ratatouille Gastronomique', time: '45min', difficulty: 'moyen', rating: 4.8, image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop' },
  { id: 4, title: 'Soufflé au Grand Marnier', time: '1h', difficulty: 'difficile', rating: 4.9, image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop' },
  { id: 5, title: 'Escargots de Bourgogne', time: '30min', difficulty: 'facile', rating: 4.7, image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop' },
  { id: 6, title: 'Tarte Tatin Moderne', time: '1h15', difficulty: 'moyen', rating: 4.6, image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop' },
  { id: 7, title: 'Cassoulet Traditionnel', time: '3h', difficulty: 'difficile', rating: 4.8, image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop' },
  { id: 8, title: 'Crème Brûlée Vanille', time: '2h', difficulty: 'moyen', rating: 4.7, image: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop' }
])

const filteredRecipes = computed(() => {
  let filtered = [...allRecipes.value]
  
  if (filters.value.difficulty) {
    filtered = filtered.filter(r => r.difficulty === filters.value.difficulty)
  }
  
  if (filters.value.time) {
    const maxTime = parseInt(filters.value.time)
    filtered = filtered.filter(r => parseInt(r.time) <= maxTime)
  }
  
  // Tri
  filtered.sort((a, b) => {
    switch (sortBy.value) {
      case 'recent': return b.id - a.id
      case 'time': return parseInt(a.time) - parseInt(b.time)
      case 'rating': return b.rating - a.rating
      default: return b.rating - a.rating // popularité par défaut
    }
  })
  
  return filtered
})

const totalPages = computed(() => Math.ceil(filteredRecipes.value.length / itemsPerPage))

const paginatedRecipes = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredRecipes.value.slice(start, start + itemsPerPage)
})

// Actions
const goToRecipe = (recipe) => {
  if (!isAuthenticated.value) {
    navigateTo(`/connexion?redirect=${encodeURIComponent(`/recettes/${recipe.slug || recipe.id}`)}`)
  } else {
    navigateTo(`/recettes/${recipe.slug || recipe.id}`)
  }
}

const paginationButtonClass = (page) => {
  const base = 'px-4 py-2 rounded-lg font-medium transition-colors'
  return page === currentPage.value 
    ? `${base} bg-bordeaux-600 text-white`
    : `${base} text-bordeaux-600 hover:bg-bordeaux-50`
}

// SEO
useHead({
  title: `Cuisine ${category.value?.name || route.params.slug} - Les Mets Chics`,
  meta: [
    { name: 'description', content: `Découvrez nos recettes de cuisine ${category.value?.name || route.params.slug} d'exception` }
  ]
})

// Initialisation
onMounted(async () => {
  await new Promise(resolve => setTimeout(resolve, 800))
  loading.value = false
  
  // Animation des cartes
  nextTick(() => {
    const cards = document.querySelectorAll('.recipe-card-animate')
    cards.forEach((card, index) => {
      card.style.animationDelay = `${index * 0.1}s`
      card.classList.add('animate-fade-in-up')
    })
  })
})
</script>

<style scoped>
.filter-select {
  @apply px-4 py-2 border border-cream-300 rounded-lg bg-white text-charcoal-700 focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent;
}

.recipe-card-animate {
  @apply opacity-0 transform translate-y-4;
}

@keyframes slow-zoom {
  0% { transform: scale(1); }
  100% { transform: scale(1.05); }
}

.animate-slow-zoom {
  animation: slow-zoom 20s ease-in-out infinite alternate;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-10px) rotate(5deg); }
}

.animate-float {
  animation: float 4s ease-in-out infinite;
}
</style>