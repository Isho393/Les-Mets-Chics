<template>
  <div class="overflow-hidden">
    
    <!-- Hero Section Immersive -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
      <!-- Background Video/Image -->
      <div class="absolute inset-0 z-0">
        <div class="w-full h-full bg-gradient-to-br from-bordeaux-900 via-bordeaux-800 to-charcoal-900 opacity-95"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center mix-blend-overlay opacity-30"></div>
      </div>
      
      <!-- Floating Elements -->
      <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-2 h-2 bg-gold-500 rounded-full animate-float opacity-60"></div>
        <div class="absolute top-40 right-20 w-1 h-1 bg-cream-200 rounded-full animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-32 left-1/4 w-3 h-3 bg-gold-600 rounded-full animate-float opacity-40" style="animation-delay: 2s;"></div>
      </div>

      <!-- Hero Content -->
      <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
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
            Découvrez une collection raffinée de recettes gastronomiques qui éveilleront vos sens et sublimeront vos papilles
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
            >
              <Icon name="lucide:play" />
              Voir la vidéo
            </ui-button>
          </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
          <Icon name="lucide:chevron-down" class="w-6 h-6 text-white/60" />
        </div>
      </div>
    </section>

    <!-- Section Statistiques -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="stat in stats" :key="stat.label" class="text-center group">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-bordeaux-100 to-bordeaux-200 rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
              <Icon :name="stat.icon" class="w-8 h-8 text-bordeaux-800" />
            </div>
            <div class="text-3xl font-display font-bold text-bordeaux-800 mb-2">{{ stat.value }}</div>
            <div class="text-charcoal-600 font-medium">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Section Recettes Vedettes -->
    <section ref="recipesSection" class="py-20 bg-cream-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="text-center mb-16">
          <h2 class="font-display text-4xl lg:text-5xl font-bold text-bordeaux-800 mb-6">
            Nos Créations
            <span class="text-gold-700">Signature</span>
          </h2>
          <p class="text-xl text-charcoal-600 max-w-3xl mx-auto leading-relaxed">
            Une sélection exclusive de nos recettes les plus raffinées, créées par nos chefs partenaires pour éveiller vos sens
          </p>
        </div>

        <!-- Recettes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
          <div
            v-for="recipe in featuredRecipes"
            :key="recipe.id"
            class="animate-slide-up cursor-pointer"
            :style="{ animationDelay: `${recipe.id * 0.1}s` }"
            @click="navigateTo(`/recettes/${recipe.slug}`)"
          >
            <ui-recipe-card
              :title="recipe.title"
              :description="recipe.description"
              :image="recipe.image"
              :category="recipe.category"
              :prep-time="recipe.prep_time"
              :servings="recipe.servings"
              :author="recipe.author"
              :liked="recipe.liked"
              variant="featured"
              @like="toggleLike(recipe.id)"
            />
          </div>
        </div>

        <!-- CTA Voir Plus -->
        <div class="text-center">
          <ui-button 
            variant="outline" 
            size="lg"
            tag="NuxtLink"
            to="/recettes"
          >
            Voir toutes les recettes
            <Icon name="lucide:arrow-right" />
          </ui-button>
        </div>
      </div>
    </section>

    <!-- Section Catégories -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16">
          <h2 class="font-display text-4xl lg:text-5xl font-bold text-bordeaux-800 mb-6">
            Explorez par
            <span class="text-gold-700">Univers</span>
          </h2>
          <p class="text-xl text-charcoal-600 max-w-2xl mx-auto">
            Chaque catégorie raconte une histoire culinaire unique
          </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <NuxtLink
            v-for="category in categories"
            :key="category.id"
            :to="`/categories/${category.slug}`"
            class="group relative overflow-hidden rounded-2xl aspect-square bg-gradient-to-br from-bordeaux-100 to-cream-200 hover:shadow-premium transition-all duration-500 hover:-translate-y-2"
          >
            <!-- Background Image -->
            <div class="absolute inset-0">
              <img 
                :src="category.image" 
                :alt="category.name"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-bordeaux-900/80 via-bordeaux-900/20 to-transparent"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 p-6 h-full flex flex-col justify-end">
              <div class="transform group-hover:-translate-y-2 transition-transform duration-300">
                <h3 class="font-display text-2xl font-bold text-white mb-2">
                  {{ category.name }}
                </h3>
                <p class="text-cream-200 text-sm mb-3">
                  {{ category.recipes_count }} recettes
                </p>
                <div class="flex items-center text-gold-400 text-sm font-medium">
                  Découvrir
                  <Icon name="lucide:arrow-right" class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300" />
                </div>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Section À Propos -->
    <section class="py-20 bg-gradient-to-br from-cream-100 to-cream-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          
          <!-- Content -->
          <div class="order-2 lg:order-1">
            <h2 class="font-display text-4xl lg:text-5xl font-bold text-bordeaux-800 mb-6">
              L'Art de la
              <span class="text-gold-700">Gastronomie</span>
            </h2>
            <p class="text-xl text-charcoal-600 mb-8 leading-relaxed">
              Chez Les Mets Chics, nous croyons que la cuisine est un art qui unit les cultures et éveille les émotions. Chaque recette est une invitation au voyage gustatif.
            </p>
            
            <!-- Features -->
            <div class="space-y-4 mb-8">
              <div v-for="feature in features" :key="feature.title" class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-bordeaux-100 rounded-xl flex items-center justify-center">
                  <Icon :name="feature.icon" class="w-6 h-6 text-bordeaux-800" />
                </div>
                <div>
                  <h3 class="font-semibold text-bordeaux-800 mb-1">{{ feature.title }}</h3>
                  <p class="text-charcoal-600">{{ feature.description }}</p>
                </div>
              </div>
            </div>
            
            <ui-button 
              variant="primary" 
              size="lg"
              tag="NuxtLink"
              to="/a-propos"
            >
              Notre histoire
              <Icon name="lucide:arrow-right" />
            </ui-button>
          </div>

          <!-- Image -->
          <div class="order-1 lg:order-2 relative">
            <div class="relative rounded-3xl overflow-hidden shadow-premium">
              <img 
                src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80"
                alt="Chef en cuisine"
                class="w-full h-96 lg:h-[500px] object-cover"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-bordeaux-900/30 to-transparent"></div>
            </div>
            
            <!-- Floating Card -->
            <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl p-6 shadow-premium animate-float">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-gold-500 to-gold-600 rounded-xl flex items-center justify-center">
                  <Icon name="lucide:award" class="w-6 h-6 text-white" />
                </div>
                <div>
                  <div class="font-display text-2xl font-bold text-bordeaux-800">5★</div>
                  <div class="text-sm text-charcoal-600">Excellence culinaire</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 bg-bordeaux-800">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display text-4xl lg:text-5xl font-bold text-white mb-6">
          Restez Inspiré
        </h2>
        <p class="text-xl text-bordeaux-200 mb-8 max-w-2xl mx-auto">
          Recevez nos dernières créations culinaires et conseils de chefs directement dans votre boîte mail
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
          <input 
            type="email"
            placeholder="Votre adresse email"
            class="flex-1 px-6 py-4 rounded-xl border-0 bg-white/10 backdrop-blur-sm text-white placeholder-bordeaux-300 focus:outline-none focus:ring-2 focus:ring-gold-500"
          />
          <ui-button variant="gold" size="lg">
            S'abonner
          </ui-button>
        </div>
        
        <p class="text-sm text-bordeaux-300 mt-4">
          Pas de spam, que du contenu premium. Désabonnement en un clic.
        </p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// SEO
useHead({
  title: 'Les Mets Chics - Gastronomie Raffinée & Recettes d\'Exception',
  meta: [
    { name: 'description', content: 'Découvrez une collection exclusive de recettes gastronomiques raffinées. Les Mets Chics vous invite à un voyage culinaire d\'exception.' }
  ]
})

// Refs
const recipesSection = ref(null)

// Data
const stats = [
  { icon: 'lucide:chef-hat', value: '150+', label: 'Recettes' },
  { icon: 'lucide:users', value: '5K+', label: 'Gourmets' },
  { icon: 'lucide:award', value: '25+', label: 'Chefs' },
  { icon: 'lucide:star', value: '4.9', label: 'Note moyenne' }
]

const featuredRecipes = ref([
  {
    id: 1,
    title: 'Risotto aux Truffes Noires',
    description: 'Un classique revisité avec des truffes du Périgord et un parmesan vieilli 24 mois',
    image: 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    category: 'Plats principaux',
    prep_time: '45 min',
    servings: 4,
    author: { name: 'Chef Laurent', avatar: null },
    liked: false,
    slug: 'risotto-truffes-noires'
  },
  {
    id: 2,
    title: 'Soufflé au Grand Marnier',
    description: 'Dessert aérien parfumé à la liqueur d\'orange, servi avec une crème anglaise vanillée',
    image: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    category: 'Desserts',
    prep_time: '30 min',
    servings: 6,
    author: { name: 'Chef Marie', avatar: null },
    liked: true,
    slug: 'souffle-grand-marnier'
  },
  {
    id: 3,
    title: 'Tataki de Thon Rouge',
    description: 'Thon rouge saisi aux graines de sésame, accompagné d\'une salade d\'algues wakamé',
    image: 'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
    category: 'Entrées',
    prep_time: '20 min',
    servings: 2,
    author: { name: 'Chef Tanaka', avatar: null },
    liked: false,
    slug: 'tataki-thon-rouge'
  }
])

const categories = [
  {
    id: 1,
    name: 'Entrées',
    slug: 'entrees',
    recipes_count: 45,
    image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 2,
    name: 'Plats',
    slug: 'plats',
    recipes_count: 78,
    image: 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 3,
    name: 'Desserts',
    slug: 'desserts',
    recipes_count: 32,
    image: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: 4,
    name: 'Boissons',
    slug: 'boissons',
    recipes_count: 18,
    image: 'https://images.unsplash.com/photo-1544145945-f90425340c7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
  }
]

const features = [
  {
    icon: 'lucide:sparkles',
    title: 'Recettes Exclusives',
    description: 'Créations originales de chefs reconnus'
  },
  {
    icon: 'lucide:video',
    title: 'Tutoriels Vidéo',
    description: 'Apprenez les techniques pas à pas'
  },
  {
    icon: 'lucide:users',
    title: 'Communauté',
    description: 'Partagez vos créations avec d\'autres passionnés'
  }
]

// Methods
const scrollToRecipes = () => {
  recipesSection.value?.scrollIntoView({ behavior: 'smooth' })
}

const toggleLike = (recipeId) => {
  const recipe = featuredRecipes.value.find(r => r.id === recipeId)
  if (recipe) {
    recipe.liked = !recipe.liked
  }
}

// Lifecycle
onMounted(() => {
  // Animation d'entrée pour les éléments
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-fade-in-up')
      }
    })
  })
  
  document.querySelectorAll('.animate-slide-up').forEach(el => {
    observer.observe(el)
  })
})
</script>