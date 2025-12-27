<template>
  <div class="min-h-screen bg-cream-50">
    
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-br from-bordeaux-800 to-bordeaux-900 overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 border border-white/20 rounded-full"></div>
        <div class="absolute bottom-20 right-20 w-24 h-24 border border-white/20 rounded-full"></div>
        <div class="absolute top-1/2 left-1/3 w-16 h-16 border border-white/20 rounded-full"></div>
      </div>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
          Nos Recettes
          <span class="text-gold-400">d'Exception</span>
        </h1>
        <p class="text-xl text-bordeaux-200 max-w-3xl mx-auto mb-8">
          Découvrez notre collection de {{ totalRecipes }} recettes gastronomiques, créées par des chefs passionnés pour sublimer vos repas
        </p>
        
        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto">
          <div class="relative">
            <Icon name="lucide:search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-charcoal-400" />
            <input 
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher une recette, un ingrédient..."
              class="w-full pl-12 pr-4 py-4 bg-white/95 backdrop-blur-sm rounded-2xl border-0 text-charcoal-800 placeholder-charcoal-500 focus:outline-none focus:ring-2 focus:ring-gold-500 shadow-soft"
              @input="handleSearch"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Filters & Content -->
    <section class="py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filters Bar -->
        <div class="bg-white rounded-2xl shadow-soft p-6 mb-8">
          <div class="flex flex-col lg:flex-row gap-6">
            
            <!-- Categories Filter -->
            <div class="flex-1">
              <label class="block text-sm font-medium text-charcoal-700 mb-2">Catégorie</label>
              <select 
                v-model="selectedCategory"
                class="w-full px-4 py-3 bg-cream-50 border border-cream-200 rounded-xl text-charcoal-800 focus:outline-none focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent"
                @change="applyFilters"
              >
                <option value="">Toutes les catégories</option>
                <option v-for="category in categories" :key="category.id" :value="category.slug">
                  {{ category.name }}
                </option>
              </select>
            </div>

            <!-- Difficulty Filter -->
            <div class="flex-1">
              <label class="block text-sm font-medium text-charcoal-700 mb-2">Difficulté</label>
              <select 
                v-model="selectedDifficulty"
                class="w-full px-4 py-3 bg-cream-50 border border-cream-200 rounded-xl text-charcoal-800 focus:outline-none focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent"
                @change="applyFilters"
              >
                <option value="">Toutes</option>
                <option value="facile">Facile</option>
                <option value="moyen">Moyen</option>
                <option value="difficile">Difficile</option>
              </select>
            </div>

            <!-- Time Filter -->
            <div class="flex-1">
              <label class="block text-sm font-medium text-charcoal-700 mb-2">Temps de préparation</label>
              <select 
                v-model="selectedTime"
                class="w-full px-4 py-3 bg-cream-50 border border-cream-200 rounded-xl text-charcoal-800 focus:outline-none focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent"
                @change="applyFilters"
              >
                <option value="">Tous</option>
                <option value="15">Moins de 15 min</option>
                <option value="30">Moins de 30 min</option>
                <option value="60">Moins d'1h</option>
                <option value="120">Plus d'1h</option>
              </select>
            </div>

            <!-- Sort -->
            <div class="flex-1">
              <label class="block text-sm font-medium text-charcoal-700 mb-2">Trier par</label>
              <select 
                v-model="sortBy"
                class="w-full px-4 py-3 bg-cream-50 border border-cream-200 rounded-xl text-charcoal-800 focus:outline-none focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent"
                @change="applyFilters"
              >
                <option value="recent">Plus récent</option>
                <option value="popular">Plus populaire</option>
                <option value="title">Nom A-Z</option>
                <option value="time">Temps de préparation</option>
              </select>
            </div>
          </div>

          <!-- Active Filters -->
          <div v-if="hasActiveFilters" class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-cream-200">
            <span class="text-sm text-charcoal-600 mr-2">Filtres actifs:</span>
            <button 
              v-if="selectedCategory"
              @click="clearFilter('category')"
              class="inline-flex items-center gap-1 px-3 py-1 bg-bordeaux-100 text-bordeaux-800 rounded-full text-sm hover:bg-bordeaux-200 transition-colors duration-200"
            >
              {{ getCategoryName(selectedCategory) }}
              <Icon name="lucide:x" class="w-3 h-3" />
            </button>
            <button 
              v-if="selectedDifficulty"
              @click="clearFilter('difficulty')"
              class="inline-flex items-center gap-1 px-3 py-1 bg-bordeaux-100 text-bordeaux-800 rounded-full text-sm hover:bg-bordeaux-200 transition-colors duration-200"
            >
              {{ selectedDifficulty }}
              <Icon name="lucide:x" class="w-3 h-3" />
            </button>
            <button 
              @click="clearAllFilters"
              class="inline-flex items-center gap-1 px-3 py-1 bg-charcoal-100 text-charcoal-700 rounded-full text-sm hover:bg-charcoal-200 transition-colors duration-200"
            >
              Tout effacer
              <Icon name="lucide:x" class="w-3 h-3" />
            </button>
          </div>
        </div>

        <!-- Results Info -->
        <div class="flex justify-between items-center mb-8">
          <div class="text-charcoal-600">
            <span class="font-medium text-bordeaux-800">{{ filteredRecipes.length }}</span> 
            recette{{ filteredRecipes.length > 1 ? 's' : '' }} trouvée{{ filteredRecipes.length > 1 ? 's' : '' }}
          </div>
          
          <!-- View Toggle -->
          <div class="flex bg-cream-100 rounded-xl p-1">
            <button 
              @click="viewMode = 'grid'"
              :class="[
                'px-4 py-2 rounded-lg transition-all duration-200',
                viewMode === 'grid' 
                  ? 'bg-white text-bordeaux-800 shadow-soft' 
                  : 'text-charcoal-600 hover:text-bordeaux-800'
              ]"
            >
              <Icon name="lucide:grid-3x3" class="w-4 h-4" />
            </button>
            <button 
              @click="viewMode = 'list'"
              :class="[
                'px-4 py-2 rounded-lg transition-all duration-200',
                viewMode === 'list' 
                  ? 'bg-white text-bordeaux-800 shadow-soft' 
                  : 'text-charcoal-600 hover:text-bordeaux-800'
              ]"
            >
              <Icon name="lucide:list" class="w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="n in 6" :key="n" class="bg-white rounded-2xl shadow-soft overflow-hidden animate-pulse">
            <div class="aspect-[4/3] bg-cream-200"></div>
            <div class="p-6 space-y-3">
              <div class="h-4 bg-cream-200 rounded w-3/4"></div>
              <div class="h-3 bg-cream-200 rounded w-full"></div>
              <div class="h-3 bg-cream-200 rounded w-2/3"></div>
            </div>
          </div>
        </div>

        <!-- Recipes Grid -->
        <div v-else-if="filteredRecipes.length > 0">
          
          <!-- Grid View -->
          <div 
            v-if="viewMode === 'grid'"
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
          >
            <RecipeCard
              v-for="recipe in paginatedRecipes"
              :key="recipe.id"
              :title="recipe.title"
              :description="recipe.description"
              :image="recipe.image"
              :category="recipe.category"
              :prep-time="recipe.prep_time"
              :servings="recipe.servings"
              :difficulty="recipe.difficulty"
              :author="recipe.author"
              :published-at="recipe.published_at"
              :liked="recipe.liked"
              class="animate-fade-in"
              @action="navigateTo(`/recettes/${recipe.slug}`)"
              @like="toggleLike(recipe.id)"
            />
          </div>

          <!-- List View -->
          <div v-else class="space-y-6">
            <div 
              v-for="recipe in paginatedRecipes"
              :key="recipe.id"
              class="bg-white rounded-2xl shadow-soft overflow-hidden hover:shadow-premium transition-all duration-300 hover:-translate-y-1 animate-fade-in"
            >
              <div class="flex flex-col md:flex-row">
                <div class="md:w-80 aspect-[4/3] md:aspect-square relative overflow-hidden">
                  <img 
                    :src="recipe.image" 
                    :alt="recipe.title"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                  />
                  <div class="absolute top-4 left-4">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-bordeaux-800 text-sm font-medium rounded-full">
                      {{ recipe.category }}
                    </span>
                  </div>
                </div>
                
                <div class="flex-1 p-6 flex flex-col justify-between">
                  <div>
                    <h3 class="font-display text-2xl font-semibold text-charcoal-900 mb-3">
                      {{ recipe.title }}
                    </h3>
                    <p class="text-charcoal-600 mb-4 line-clamp-3">
                      {{ recipe.description }}
                    </p>
                    
                    <!-- Meta Info -->
                    <div class="flex items-center gap-6 text-sm text-charcoal-500 mb-4">
                      <div class="flex items-center gap-1">
                        <Icon name="lucide:clock" class="w-4 h-4" />
                        <span>{{ recipe.prep_time }}</span>
                      </div>
                      <div class="flex items-center gap-1">
                        <Icon name="lucide:users" class="w-4 h-4" />
                        <span>{{ recipe.servings }} pers.</span>
                      </div>
                      <div class="flex items-center gap-1">
                        <Icon name="lucide:chef-hat" class="w-4 h-4" />
                        <span>{{ recipe.difficulty }}</span>
                      </div>
                    </div>
                  </div>
                  
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                      <UserAvatar 
                        :avatar="recipe.author.avatar" 
                        :name="recipe.author.name" 
                        size="sm" 
                      />
                      <div>
                        <p class="text-sm font-medium text-charcoal-800">{{ recipe.author.name }}</p>
                        <p class="text-xs text-charcoal-500">{{ formatDate(recipe.published_at) }}</p>
                      </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                      <button 
                        @click="toggleLike(recipe.id)"
                        class="p-2 hover:bg-cream-100 rounded-xl transition-colors duration-200"
                      >
                        <Icon 
                          name="lucide:heart" 
                          :class="recipe.liked ? 'text-bordeaux-800 fill-current' : 'text-charcoal-400'" 
                        />
                      </button>
                      <Button 
                        variant="outline" 
                        size="sm"
                        @click="navigateTo(`/recettes/${recipe.slug}`)"
                      >
                        Voir la recette
                      </Button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="flex justify-center mt-12">
            <div class="flex items-center gap-2">
              <button 
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="p-2 rounded-xl border border-cream-200 text-charcoal-600 hover:bg-cream-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
              >
                <Icon name="lucide:chevron-left" class="w-4 h-4" />
              </button>
              
              <button 
                v-for="page in visiblePages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'px-4 py-2 rounded-xl transition-all duration-200',
                  page === currentPage 
                    ? 'bg-bordeaux-800 text-white' 
                    : 'border border-cream-200 text-charcoal-600 hover:bg-cream-100'
                ]"
              >
                {{ page }}
              </button>
              
              <button 
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="p-2 rounded-xl border border-cream-200 text-charcoal-600 hover:bg-cream-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
              >
                <Icon name="lucide:chevron-right" class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-16">
          <div class="w-24 h-24 bg-cream-200 rounded-full flex items-center justify-center mx-auto mb-6">
            <Icon name="lucide:search-x" class="w-12 h-12 text-charcoal-400" />
          </div>
          <h3 class="font-display text-2xl font-semibold text-charcoal-800 mb-4">
            Aucune recette trouvée
          </h3>
          <p class="text-charcoal-600 mb-6 max-w-md mx-auto">
            Essayez de modifier vos critères de recherche ou explorez nos catégories
          </p>
          <Button 
            variant="outline"
            @click="clearAllFilters"
          >
            Voir toutes les recettes
          </Button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'

// SEO
useHead({
  title: 'Recettes Gastronomiques - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Découvrez notre collection de recettes gastronomiques raffinées. Filtrez par catégorie, difficulté et temps de préparation.' }
  ]
})

// State
const loading = ref(true)
const searchQuery = ref('')
const selectedCategory = ref('')
const selectedDifficulty = ref('')
const selectedTime = ref('')
const sortBy = ref('recent')
const viewMode = ref('grid')
const currentPage = ref(1)
const recipesPerPage = 12

// Mock data
const recipes = ref([])
const categories = ref([
  { id: 1, name: 'Entrées', slug: 'entrees' },
  { id: 2, name: 'Plats principaux', slug: 'plats' },
  { id: 3, name: 'Desserts', slug: 'desserts' },
  { id: 4, name: 'Boissons', slug: 'boissons' }
])

// Computed
const totalRecipes = computed(() => recipes.value.length)

const hasActiveFilters = computed(() => {
  return selectedCategory.value || selectedDifficulty.value || selectedTime.value || searchQuery.value
})

const filteredRecipes = computed(() => {
  let filtered = [...recipes.value]
  
  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(recipe => 
      recipe.title.toLowerCase().includes(query) ||
      recipe.description.toLowerCase().includes(query)
    )
  }
  
  // Category filter
  if (selectedCategory.value) {
    filtered = filtered.filter(recipe => recipe.category_slug === selectedCategory.value)
  }
  
  // Difficulty filter
  if (selectedDifficulty.value) {
    filtered = filtered.filter(recipe => recipe.difficulty.toLowerCase() === selectedDifficulty.value)
  }
  
  // Time filter
  if (selectedTime.value) {
    const maxTime = parseInt(selectedTime.value)
    filtered = filtered.filter(recipe => {
      const prepTime = parseInt(recipe.prep_time)
      return maxTime === 120 ? prepTime >= 60 : prepTime <= maxTime
    })
  }
  
  // Sort
  filtered.sort((a, b) => {
    switch (sortBy.value) {
      case 'popular':
        return b.likes - a.likes
      case 'title':
        return a.title.localeCompare(b.title)
      case 'time':
        return parseInt(a.prep_time) - parseInt(b.prep_time)
      default: // recent
        return new Date(b.published_at) - new Date(a.published_at)
    }
  })
  
  return filtered
})

const totalPages = computed(() => Math.ceil(filteredRecipes.value.length / recipesPerPage))

const paginatedRecipes = computed(() => {
  const start = (currentPage.value - 1) * recipesPerPage
  const end = start + recipesPerPage
  return filteredRecipes.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages = []
  const total = totalPages.value
  const current = currentPage.value
  
  if (total <= 7) {
    for (let i = 1; i <= total; i++) {
      pages.push(i)
    }
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) pages.push(i)
      pages.push('...', total)
    } else if (current >= total - 3) {
      pages.push(1, '...')
      for (let i = total - 4; i <= total; i++) pages.push(i)
    } else {
      pages.push(1, '...')
      for (let i = current - 1; i <= current + 1; i++) pages.push(i)
      pages.push('...', total)
    }
  }
  
  return pages.filter(page => page !== '...' || pages.indexOf(page) === pages.lastIndexOf(page))
})

// Methods
const handleSearch = debounce(() => {
  currentPage.value = 1
  applyFilters()
}, 300)

const applyFilters = () => {
  currentPage.value = 1
}

const clearFilter = (filterType) => {
  switch (filterType) {
    case 'category':
      selectedCategory.value = ''
      break
    case 'difficulty':
      selectedDifficulty.value = ''
      break
    case 'time':
      selectedTime.value = ''
      break
  }
  applyFilters()
}

const clearAllFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = ''
  selectedDifficulty.value = ''
  selectedTime.value = ''
  sortBy.value = 'recent'
  applyFilters()
}

const getCategoryName = (slug) => {
  const category = categories.value.find(cat => cat.slug === slug)
  return category ? category.name : slug
}

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}

const toggleLike = (recipeId) => {
  const recipe = recipes.value.find(r => r.id === recipeId)
  if (recipe) {
    recipe.liked = !recipe.liked
    recipe.likes += recipe.liked ? 1 : -1
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

// Debounce utility
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Lifecycle
onMounted(async () => {
  // Simulate API call
  await new Promise(resolve => setTimeout(resolve, 1000))
  
  // Mock recipes data
  recipes.value = Array.from({ length: 24 }, (_, i) => ({
    id: i + 1,
    title: `Recette Gastronomique ${i + 1}`,
    slug: `recette-${i + 1}`,
    description: 'Une description alléchante de cette recette d\'exception qui vous fera saliver rien qu\'à la lecture.',
    image: `https://images.unsplash.com/photo-${1565299624946 + i}?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80`,
    category: ['Entrées', 'Plats principaux', 'Desserts', 'Boissons'][i % 4],
    category_slug: ['entrees', 'plats', 'desserts', 'boissons'][i % 4],
    prep_time: `${15 + (i % 4) * 15} min`,
    servings: 2 + (i % 4),
    difficulty: ['Facile', 'Moyen', 'Difficile'][i % 3],
    author: {
      name: `Chef ${['Laurent', 'Marie', 'Pierre', 'Sophie'][i % 4]}`,
      avatar: null
    },
    published_at: new Date(Date.now() - i * 24 * 60 * 60 * 1000).toISOString(),
    liked: Math.random() > 0.7,
    likes: Math.floor(Math.random() * 100)
  }))
  
  loading.value = false
})

// Watch for route query changes
watch(() => useRoute().query, (newQuery) => {
  if (newQuery.search) {
    searchQuery.value = newQuery.search
  }
  if (newQuery.category) {
    selectedCategory.value = newQuery.category
  }
}, { immediate: true })
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>