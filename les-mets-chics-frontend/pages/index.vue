<template>
  <div class="min-h-screen overflow-hidden">

    <!-- Hero Section Immersif -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
      <!-- Background Video/Image -->
      <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-black/70 z-10"></div>
        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1920&h=1080&fit=crop"
          alt="Cuisine gastronomique" class="w-full h-full object-cover scale-110 animate-slow-zoom" />
      </div>

      <!-- Particules flottantes -->
      <div class="absolute inset-0 z-20">
        <div v-for="i in 12" :key="i" class="absolute w-2 h-2 bg-gold-400/30 rounded-full animate-float" :style="{
          left: Math.random() * 100 + '%',
          top: Math.random() * 100 + '%',
          animationDelay: Math.random() * 3 + 's',
          animationDuration: (3 + Math.random() * 2) + 's'
        }"></div>
      </div>

      <!-- Contenu Hero -->
      <div class="relative z-30 text-center px-4 max-w-5xl mx-auto">
        <div class="mb-8">
          <div
            class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-md rounded-full text-white/90 text-sm font-medium mb-6 animate-fade-in-up">
            <Icon name="lucide:sparkles" class="mr-2" />
            Gastronomie d'Exception
          </div>
        </div>

        <h1 class="text-5xl md:text-7xl lg:text-8xl font-display font-bold text-white mb-8 leading-tight">
          <span class="block animate-slide-in-left" style="animation-delay: 0.2s">
            Les Mets
          </span>
          <span
            class="block bg-gradient-to-r from-gold-400 to-gold-600 bg-clip-text text-transparent animate-slide-in-right"
            style="animation-delay: 0.4s">
            Chics
          </span>
        </h1>

        <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-3xl mx-auto leading-relaxed animate-fade-in-up"
          style="animation-delay: 0.6s">
          L'art culinaire à son apogée. Découvrez des recettes d'exception qui éveilleront vos sens.
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center animate-fade-in-up" style="animation-delay: 0.8s">
          <button @click="scrollToRecipes" class="hero-btn-primary group">
            <Icon name="lucide:chef-hat" class="mr-3 group-hover:rotate-12 transition-transform" />
            Découvrir l'Excellence
            <div
              class="absolute inset-0 bg-gradient-to-r from-gold-400/20 to-gold-600/20 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
          </button>

          <button @click="playIntroVideo" class="hero-btn-secondary group">
            <Icon name="lucide:play" class="mr-3 group-hover:scale-110 transition-transform" />
            Voir l'Expérience
          </button>
        </div>
      </div>

      <!-- Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 animate-bounce">
        <Icon name="lucide:chevron-down" class="text-white/70 text-2xl" />
      </div>
    </section>

    <!-- Section Signature -->
    <section class="py-24 bg-gradient-to-b from-white to-cream-50 relative overflow-hidden">
      <div class="absolute inset-0 bg-pattern opacity-50"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-20">
          <div
            class="inline-flex items-center px-6 py-3 bg-bordeaux-100 rounded-full text-bordeaux-800 font-medium mb-8 animate-fade-in">
            <Icon name="lucide:award" class="mr-2" />
            Sélection Premium
          </div>

          <h2 class="text-4xl md:text-6xl font-display font-bold text-bordeaux-900 mb-6 animate-fade-in-up">
            Créations d'Exception
          </h2>

          <p class="text-xl text-charcoal-600 max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s">
            Chaque recette est une œuvre d'art culinaire, pensée pour sublimer vos moments de dégustation.
          </p>
        </div>

        <!-- Grille de recettes premium -->
        <div ref="recipesSection" class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
          <!-- Recette Featured -->
          <div class="lg:col-span-2 group cursor-pointer" @click="handleRecipeClick(featuredRecipes[0])">
            <div
              class="relative h-96 lg:h-[500px] rounded-3xl overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-700 hover:-translate-y-2">
              <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop"
                alt="Recette signature"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

              <!-- Badge Premium -->
              <div class="absolute top-6 left-6">
                <div class="flex items-center px-4 py-2 bg-gold-500 text-white rounded-full text-sm font-bold">
                  <Icon name="lucide:crown" class="mr-2" />
                  Signature
                </div>
              </div>

              <!-- Contenu -->
              <div class="absolute bottom-0 left-0 right-0 p-8">
                <div class="flex items-center mb-4">
                  <div class="flex items-center text-gold-400 text-sm">
                    <Icon name="lucide:clock" class="mr-1" />
                    <span class="mr-4">45 min</span>
                    <Icon name="lucide:users" class="mr-1" />
                    <span>4 pers.</span>
                  </div>
                </div>

                <h3 class="text-3xl font-display font-bold text-white mb-3">
                  Bœuf Wellington Revisité
                </h3>

                <p class="text-white/90 text-lg leading-relaxed">
                  Une interprétation moderne du classique britannique, sublimée par nos techniques d'avant-garde.
                </p>
              </div>
            </div>
          </div>

          <!-- Recettes secondaires -->
          <div class="space-y-8">
            <div v-for="(recipe, index) in featuredRecipes.slice(1, 3)" :key="recipe.id" class="group cursor-pointer"
              @click="handleRecipeClick(recipe)">
              <div
                class="relative h-44 rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                <img :src="recipe.image" :alt="recipe.title"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-6">
                  <div class="flex items-center text-gold-400 text-xs mb-2">
                    <Icon name="lucide:clock" class="mr-1" />
                    <span class="mr-3">{{ recipe.time }}</span>
                    <Icon name="lucide:star" class="mr-1" />
                    <span>{{ recipe.rating }}</span>
                  </div>

                  <h4 class="text-white font-display font-semibold text-lg leading-tight">
                    {{ recipe.title }}
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CTA Découvrir -->
        <div class="text-center py-10">
          <NuxtLink :to="isAuthenticated ? '/recettes' : '/connexion'"
            class="premium-btn group relative inline-flex items-center justify-center px-8 py-4 overflow-hidden rounded-2xl bg-bordeaux-700 text-white transition-all duration-300 shadow-lg">
            <span class="relative z-10 flex items-center font-medium">
              <Icon name="lucide:compass"
                class="mr-3 w-5 h-5 group-hover:rotate-180 transition-transform duration-500" />
              Explorer Toutes les Créations
            </span>

            <div
              class="absolute inset-0 bg-gradient-to-r from-bordeaux-500 to-bordeaux-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- Origines du Monde -->
    <section class="py-24 bg-bordeaux-900 relative overflow-hidden">
      <!-- Pattern Background -->
      <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-pattern-circles"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-20">
          <h2 class="text-4xl md:text-6xl font-display font-bold text-white mb-6">
            Voyage Culinaire
          </h2>
          <p class="text-xl text-bordeaux-100 max-w-3xl mx-auto">
            Explorez les traditions gastronomiques les plus raffinées de chaque continent.
          </p>
        </div>

        <!-- Grille des origines -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="(origin, index) in worldOrigins" :key="origin.id" class="origin-card group cursor-pointer"
            @click="handleCategoryClick(origin)" :style="{ animationDelay: index * 0.1 + 's' }">
            <div
              class="relative h-80 rounded-2xl overflow-hidden bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-sm border border-white/20 hover:border-gold-400/50 transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl">
              <!-- Icon/Image -->
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-6xl mb-4 group-hover:scale-110 transition-transform duration-500">
                  {{ origin.emoji }}
                </div>
              </div>

              <!-- Overlay Content -->
              <div
                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              </div>

              <!-- Content -->
              <div
                class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                <h3 class="text-2xl font-display font-bold text-white mb-2">
                  {{ origin.name }}
                </h3>
                <p class="text-white/90 text-sm mb-3">
                  {{ origin.description }}
                </p>
                <div class="flex items-center text-gold-400 text-sm">
                  <Icon name="lucide:utensils" class="mr-2" />
                  <span>{{ origin.recipes_count }} recettes</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Final Premium -->
    <section class="py-24 bg-gradient-to-br from-cream-50 via-white to-gold-50 relative overflow-hidden">
      <div class="absolute inset-0 bg-pattern-large opacity-50"></div>

      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div class="mb-12">
          <div
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gold-100 to-gold-200 rounded-full text-gold-800 font-medium mb-8">
            <Icon name="lucide:crown" class="mr-2" />
            Rejoignez l'Excellence
          </div>
        </div>

        <h2 class="text-4xl md:text-6xl font-display font-bold text-bordeaux-900 mb-8">
          Votre Aventure Culinaire
          <span class="block text-gold-600">Commence Ici</span>
        </h2>

        <p class="text-xl text-charcoal-600 mb-12 max-w-3xl mx-auto leading-relaxed">
          Accédez à une collection exclusive de recettes d'exception, partagez vos créations et rejoignez une communauté
          de passionnés.
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center">
          <NuxtLink v-if="!isAuthenticated" to="/inscription" class="cta-primary group">
            <span class="relative z-10 flex items-center">
              <Icon name="lucide:sparkles" class="mr-3 group-hover:rotate-12 transition-transform" />
              Créer Mon Compte Premium
            </span>
            <div
              class="absolute inset-0 bg-gradient-to-r from-gold-400 to-gold-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
          </NuxtLink>

          <button class="cta-secondary group">
            <Icon name="lucide:play-circle" class="mr-3 group-hover:scale-110 transition-transform" />
            Découvrir en Vidéo
          </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-8 mt-16 pt-16 border-t border-charcoal-200">
          <div class="text-center">
            <div class="text-3xl md:text-4xl font-display font-bold text-bordeaux-900 mb-2">500+</div>
            <div class="text-charcoal-600">Recettes d'Exception</div>
          </div>
          <div class="text-center">
            <div class="text-3xl md:text-4xl font-display font-bold text-bordeaux-900 mb-2">50+</div>
            <div class="text-charcoal-600">Chefs Partenaires</div>
          </div>
          <div class="text-center">
            <div class="text-3xl md:text-4xl font-display font-bold text-bordeaux-900 mb-2">25</div>
            <div class="text-charcoal-600">Origines Culinaires</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <FooterPremium />
  </div>
</template>

<script setup>
const { isAuthenticated } = useAuth()

// SEO
useHead({
  title: 'Les Mets Chics - L\'Art Culinaire d\'Exception',
  meta: [
    { name: 'description', content: 'Découvrez l\'art culinaire à son apogée. Recettes gastronomiques d\'exception, techniques d\'avant-garde et voyage culinaire autour du monde.' }
  ]
})

// État
const loading = ref(true)
const recipesSection = ref(null)

// Données premium
const featuredRecipes = ref([
  {
    id: 1,
    title: 'Bœuf Wellington Revisité',
    image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=600&fit=crop',
    time: '45 min',
    rating: '4.9',
    category: 'Signature'
  },
  {
    id: 2,
    title: 'Sashimi de Thon Rouge',
    image: 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?w=400&h=300&fit=crop',
    time: '20 min',
    rating: '4.8',
    category: 'Japonaise'
  },
  {
    id: 3,
    title: 'Risotto aux Truffes',
    image: 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=400&h=300&fit=crop',
    time: '35 min',
    rating: '4.7',
    category: 'Italienne'
  }
])

const worldOrigins = ref([
  {
    id: 1,
    name: 'France',
    slug: 'francaise',
    emoji: '🇫🇷',
    description: 'L\'élégance culinaire française dans toute sa splendeur',
    recipes_count: 125
  },
  {
    id: 2,
    name: 'Japon',
    slug: 'japonaise',
    emoji: '🇯🇵',
    description: 'L\'art de la précision et de la pureté des saveurs',
    recipes_count: 89
  },
  {
    id: 3,
    name: 'Italie',
    slug: 'italienne',
    emoji: '🇮🇹',
    description: 'La passion méditerranéenne sublimée',
    recipes_count: 156
  },
  {
    id: 4,
    name: 'Pérou',
    slug: 'peruvienne',
    emoji: '🇵🇪',
    description: 'La révolution culinaire sud-américaine',
    recipes_count: 67
  }
])

// Actions
const scrollToRecipes = () => {
  recipesSection.value?.scrollIntoView({ behavior: 'smooth' })
}

const playIntroVideo = () => {
  // Logique pour ouvrir une modal vidéo
  console.log('Ouverture vidéo d\'introduction')
}

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

// Animations au montage
onMounted(() => {
  loading.value = false

  // Animation des éléments au scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-fade-in-up')
      }
    })
  }, observerOptions)

  // Observer les éléments à animer
  document.querySelectorAll('.origin-card').forEach(el => {
    observer.observe(el)
  })
})
</script>

<style scoped>
/* Hero Buttons */
.hero-btn-primary {
  @apply relative px-8 py-4 bg-gradient-to-r from-gold-500 to-gold-600 text-white rounded-2xl font-semibold text-lg transition-all duration-300 hover:shadow-2xl hover:scale-105 overflow-hidden;
}

.hero-btn-secondary {
  @apply px-8 py-4 bg-white/10 backdrop-blur-md text-white border border-white/30 rounded-2xl font-semibold text-lg transition-all duration-300 hover:bg-white/20 hover:border-white/50;
}

/* Premium Buttons */
.premium-btn {
  @apply relative px-8 py-4 bg-gradient-to-r from-bordeaux-500 to-bordeaux-700 text-white rounded-2xl font-semibold text-lg transition-all duration-300 hover:shadow-2xl hover:scale-105 overflow-hidden;
}

.cta-primary {
  @apply relative px-10 py-5 bg-gradient-to-r from-bordeaux-600 to-bordeaux-800 text-white rounded-2xl font-bold text-xl transition-all duration-300 hover:shadow-2xl hover:scale-105 overflow-hidden;
}

.cta-secondary {
  @apply px-10 py-5 bg-white border-2 border-bordeaux-600 text-bordeaux-600 rounded-2xl font-bold text-xl transition-all duration-300 hover:bg-bordeaux-600 hover:text-white;
}

/* Animations personnalisées */
@keyframes slow-zoom {
  0% {
    transform: scale(1);
  }

  100% {
    transform: scale(1.05);
  }
}

@keyframes slide-in-left {
  0% {
    opacity: 0;
    transform: translateX(-100px);
  }

  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes slide-in-right {
  0% {
    opacity: 0;
    transform: translateX(100px);
  }

  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fade-in-up {
  0% {
    opacity: 0;
    transform: translateY(50px);
  }

  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  0% {
    opacity: 0;
  }

  100% {
    opacity: 1;
  }
}

@keyframes float {

  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }

  33% {
    transform: translateY(-10px) rotate(1deg);
  }

  66% {
    transform: translateY(5px) rotate(-1deg);
  }
}

/* Classes d'animation */
.animate-slow-zoom {
  animation: slow-zoom 20s ease-in-out infinite alternate;
}

.animate-slide-in-left {
  animation: slide-in-left 1s ease-out;
}

.animate-slide-in-right {
  animation: slide-in-right 1s ease-out;
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out;
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

/* Origin Cards */
.origin-card {
  @apply opacity-0 transform translate-y-8;
  animation: fade-in-up 0.8s ease-out forwards;
}

/* Hover Effects */
.hover-lift {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
  transform: translateY(-8px);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Responsive */
@media (max-width: 768px) {

  .hero-btn-primary,
  .hero-btn-secondary {
    @apply px-6 py-3 text-base;
  }

  .cta-primary,
  .cta-secondary {
    @apply px-8 py-4 text-lg;
  }
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom shadows */
.shadow-3xl {
  box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
}

/* Background patterns */
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f5d085' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.bg-pattern-circles {
  background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M50 50m-40 0a40 40 0 1 1 80 0a40 40 0 1 1 -80 0' stroke='%23ffffff' stroke-width='1' fill='none'/%3E%3C/svg%3E");
}

.bg-pattern-large {
  background-image: url("data:image/svg+xml,%3Csvg width='200' height='200' viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f5d085' fill-opacity='0.03'%3E%3Cpath d='M100 100c55.228 0 100-44.772 100-100S155.228 0 100 0 0 44.772 0 100s44.772 100 100 100z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>