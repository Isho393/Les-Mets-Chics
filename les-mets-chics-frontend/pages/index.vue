<template>
  <div class="overflow-hidden">
    
    <!-- Hero Section Simplifié -->
    <section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
      <!-- Background -->
      <div class="absolute inset-0 z-0">
        <div class="w-full h-full bg-gradient-to-br from-bordeaux-900 via-bordeaux-800 to-charcoal-900 opacity-95"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center mix-blend-overlay opacity-30"></div>
      </div>
      
      <!-- Hero Content -->
      <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="animate-fade-in">
          <!-- Badge Premium -->
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-cream-100 text-sm font-medium mb-8">
            <Icon name="lucide:sparkles" class="w-4 h-4 text-gold-400" />
            Gastronomie d'exception
          </div>
          
          <!-- Titre Principal -->
          <h1 class="font-display text-4xl sm:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
            Les Mets
            <span class="bg-gradient-to-r from-gold-400 to-gold-600 bg-clip-text text-transparent">
              Chics
            </span>
          </h1>
          
          <!-- Sous-titre -->
          <p class="text-xl sm:text-2xl text-cream-200 mb-8 max-w-3xl mx-auto leading-relaxed">
            Découvrez une collection raffinée de recettes gastronomiques créées par des chefs d'exception
          </p>
          
          <!-- CTA Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <ui-button 
              variant="gold" 
              size="lg"
              class="group"
              @click="scrollToRecipes"
            >
              <Icon name="lucide:chef-hat" class="group-hover:rotate-12 transition-transform duration-300" />
              Découvrir les recettes
            </ui-button>
            <ui-button 
              variant="outline" 
              size="lg"
              class="border-white/30 text-white hover:bg-white hover:text-bordeaux-800"
              tag="NuxtLink"
              to="/inscription"
            >
              <Icon name="lucide:user-plus" />
              Rejoindre la communauté
            </ui-button>
          </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <Icon name="lucide:chevron-down" class="w-6 h-6 text-white/60" />
        </div>
      </div>
    </section>

    <!-- Section Recettes (Aperçu limité) -->
    <section ref="recipesSection" class="py-20 bg-cream-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-16">
          <h2 class="font-display text-4xl lg:text-5xl font-bold text-bordeaux-800 mb-6">
            Nos Créations
            <span class="text-gold-700">Signature</span>
          </h2>
          <p class="text-xl text-charcoal-600 max-w-3xl mx-auto leading-relaxed mb-8">
            Un aperçu de nos recettes exclusives. Connectez-vous pour découvrir tous les détails, ingrédients et techniques.
          </p>
          
          <!-- Teasing Banner -->
          <div class="bg-gradient-to-r from-bordeaux-50 to-gold-50 rounded-2xl p-6 mb-12 max-w-2xl mx-auto">
            <div class="flex items-center justify-center gap-3 mb-3">
              <Icon name="lucide:lock" class="w-5 h-5 text-bordeaux-700" />
              <span class="font-semibold text-bordeaux-800">Contenu exclusif</span>
            </div>
            <p class="text-charcoal-600 text-sm mb-4">
              Accédez aux recettes complètes, vidéos, techniques et rejoignez notre communauté de gourmets
            </p>
            <div class="flex gap-2 justify-center">
              <ui-button variant="primary" size="sm" tag="NuxtLink" to="/inscription">
                Créer un compte
              </ui-button>
              <ui-button variant="ghost" size="sm" tag="NuxtLink" to="/connexion">
                Se connecter
              </ui-button>
            </div>
          </div>
        </div>

        <!-- Recettes Grid (Aperçu seulement) -->
        <div v-if="loading" class="mb-12">
          <ui-skeleton-loader type="recipe-grid" :count="6" />
        </div>
        
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
          <div
            v-for="recipe in publicRecipes"
            :key="recipe.id"
            class="group cursor-pointer transform transition-all duration-300 hover:scale-105"
            @click="handleRecipeClick(recipe.slug)"
          >
            <RecipePreview
              :title="recipe.title"
              :description="recipe.description"
              :image="recipe.main_image_url || recipe.image"
              :category="recipe.category?.name || recipe.category"
              :prep-time="recipe.prep_time"
              :author="recipe.author?.name || recipe.author"
            />
          </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center">
          <div v-if="loading" class="max-w-md mx-auto">
            <ui-skeleton-loader type="custom" custom-classes="bg-white rounded-2xl shadow-premium p-8">
              <div class="space-y-4">
                <div class="w-12 h-12 bg-cream-200 rounded-full mx-auto"></div>
                <div class="h-6 bg-cream-200 rounded w-3/4 mx-auto"></div>
                <div class="h-4 bg-cream-200 rounded w-full"></div>
                <div class="h-12 bg-cream-200 rounded-xl w-full mt-6"></div>
              </div>
            </ui-skeleton-loader>
          </div>
          
          <div v-else class="bg-white rounded-2xl shadow-premium p-8 max-w-md mx-auto">
            <Icon name="lucide:chef-hat" class="w-12 h-12 text-bordeaux-700 mx-auto mb-4" />
            <h3 class="font-display text-2xl font-bold text-bordeaux-800 mb-3">
              Plus de {{ totalRecipes }}+ recettes vous attendent
            </h3>
            <p class="text-charcoal-600 mb-6">
              Techniques détaillées, vidéos exclusives, et une communauté passionnée
            </p>
            <ui-button 
              variant="primary" 
              size="lg"
              full-width
              tag="NuxtLink"
              to="/inscription"
            >
              Commencer gratuitement
              <Icon name="lucide:arrow-right" />
            </ui-button>
            <p class="text-xs text-charcoal-500 mt-3">
              Déjà membre ? 
              <NuxtLink to="/connexion" class="text-bordeaux-700 hover:text-bordeaux-800 font-medium">
                Se connecter
              </NuxtLink>
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Section À Propos (Simplifiée) -->
    <section class="py-20 bg-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-4xl lg:text-5xl font-bold text-bordeaux-800 mb-6">
          L'Art de la
          <span class="text-gold-700">Gastronomie</span>
        </h2>
        <p class="text-xl text-charcoal-600 mb-8 leading-relaxed max-w-2xl mx-auto">
          Chez Les Mets Chics, nous croyons que la cuisine est un art qui unit les cultures et éveille les émotions. Chaque recette est une invitation au voyage gustatif.
        </p>
        
        <ui-button 
          variant="outline" 
          size="lg"
          tag="NuxtLink"
          to="/a-propos"
        >
          En savoir plus
          <Icon name="lucide:arrow-right" />
        </ui-button>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const { handleRecipeClick } = useNavigation()
const recipesStore = useRecipesStore()

// SEO
useHead({
  title: 'Les Mets Chics - Gastronomie Raffinée & Recettes d\'Exception',
  meta: [
    { name: 'description', content: 'Découvrez une collection exclusive de recettes gastronomiques raffinées. Les Mets Chics vous invite à un voyage culinaire d\'exception.' }
  ]
})

// Refs
const recipesSection = ref(null)
const publicRecipes = ref([])
const totalRecipes = ref(150)
const loading = ref(true)

// Methods
const scrollToRecipes = () => {
  recipesSection.value?.scrollIntoView({ behavior: 'smooth' })
}

const loadPublicRecipes = async () => {
  try {
    loading.value = true
    const recipes = await recipesStore.fetchPublicRecipes()
    // Limiter à 6 recettes pour l'aperçu
    publicRecipes.value = recipes.slice(0, 6)
    totalRecipes.value = recipes.length
  } catch (error) {
    console.error('Erreur lors du chargement des recettes:', error)
    // Fallback avec données statiques
    publicRecipes.value = [
      {
        id: 1,
        title: 'Risotto aux Truffes Noires',
        description: 'Un classique revisité avec des truffes du Périgord',
        image: 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        category: 'Plats principaux',
        prep_time: '45 min',
        author: 'Chef Laurent',
        slug: 'risotto-truffes-noires'
      },
      {
        id: 2,
        title: 'Soufflé au Grand Marnier',
        description: 'Dessert aérien parfumé à la liqueur d\'orange',
        image: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        category: 'Desserts',
        prep_time: '30 min',
        author: 'Chef Marie',
        slug: 'souffle-grand-marnier'
      },
      {
        id: 3,
        title: 'Tataki de Thon Rouge',
        description: 'Thon rouge saisi aux graines de sésame',
        image: 'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        category: 'Entrées',
        prep_time: '20 min',
        author: 'Chef Tanaka',
        slug: 'tataki-thon-rouge'
      }
    ]
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadPublicRecipes()
})
</script>