<template>
  <div class="min-h-screen">
    <!-- Hero Immersif -->
    <section class="relative h-screen overflow-hidden">
      <!-- Background Media -->
      <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>
        <video 
          v-if="recipe?.video_url"
          :src="recipe.video_url"
          autoplay
          muted
          loop
          class="w-full h-full object-cover"
        />
        <img 
          v-else
          :src="recipe?.main_image_url || 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=1920&h=1080&fit=crop'"
          :alt="recipe?.title"
          class="w-full h-full object-cover scale-105 animate-slow-zoom"
        />
      </div>
      
      <!-- Contenu Hero -->
      <div class="relative z-20 h-full flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 w-full">
          <div class="max-w-4xl">
            <!-- Badges -->
            <div class="flex flex-wrap gap-3 mb-6">
              <span class="px-4 py-2 bg-gold-500 text-white rounded-full text-sm font-bold flex items-center">
                <Icon name="lucide:crown" class="mr-2" />
                {{ recipe?.category?.name || 'Signature' }}
              </span>
              <span v-if="recipe?.difficulty" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-medium">
                {{ recipe.difficulty }}
              </span>
            </div>
            
            <!-- Titre -->
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-display font-bold text-white mb-6 leading-tight animate-fade-in-up">
              {{ recipe?.title || 'Bœuf Wellington Revisité' }}
            </h1>
            
            <!-- Description -->
            <p class="text-xl md:text-2xl text-white/90 mb-8 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s">
              {{ recipe?.description || defaultDescription }}
            </p>
            
            <!-- Meta Info -->
            <div class="flex flex-wrap gap-6 mb-8 animate-fade-in-up" style="animation-delay: 0.4s">
              <div class="flex items-center text-white/90">
                <Icon name="lucide:clock" class="mr-2 text-gold-400" />
                <span class="font-medium">{{ recipe?.prep_time || '45' }} min</span>
              </div>
              <div class="flex items-center text-white/90">
                <Icon name="lucide:users" class="mr-2 text-gold-400" />
                <span class="font-medium">{{ recipe?.servings || '4' }} personnes</span>
              </div>
              <div class="flex items-center text-white/90">
                <Icon name="lucide:chef-hat" class="mr-2 text-gold-400" />
                <span class="font-medium">{{ recipe?.cook_time || '30' }} min cuisson</span>
              </div>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-wrap gap-4 animate-fade-in-up" style="animation-delay: 0.6s">
              <button 
                @click="toggleLike"
                :class="likeButtonClasses"
              >
                <Icon :name="isLiked ? 'lucide:heart' : 'lucide:heart'" :class="isLiked ? 'fill-current' : ''" />
                <span class="ml-2">{{ isLiked ? 'Aimé' : likeText }}</span>
                <span v-if="likesCount" class="ml-1">({{ likesCount }})</span>
              </button>
              
              <button class="recipe-action-btn">
                <Icon name="lucide:bookmark" />
                <span class="ml-2">Sauvegarder</span>
              </button>
              
              <button class="recipe-action-btn">
                <Icon name="lucide:share-2" />
                <span class="ml-2">Partager</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Scroll Indicator -->
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-30 animate-bounce">
        <Icon name="lucide:chevron-down" class="text-white/70 text-2xl" />
      </div>
    </section>

    <!-- Contenu Principal -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
          
          <!-- Ingrédients -->
          <div class="lg:col-span-1">
            <div class="sticky top-24">
              <div class="bg-cream-50 rounded-3xl p-8 shadow-soft">
                <h2 class="text-2xl font-display font-bold text-bordeaux-900 mb-6 flex items-center">
                  <Icon name="lucide:shopping-cart" class="mr-3 text-gold-600" />
                  Ingrédients
                </h2>
                
                <div class="space-y-4">
                  <div v-for="(ingredient, index) in ingredients" :key="index" 
                       class="flex items-center p-3 bg-white rounded-xl hover:shadow-md transition-shadow">
                    <div class="w-3 h-3 bg-bordeaux-400 rounded-full mr-4 flex-shrink-0"></div>
                    <div class="flex-1">
                      <span class="font-medium text-charcoal-800">{{ ingredient.quantity }}</span>
                      <span class="text-charcoal-600 ml-2">{{ ingredient.name }}</span>
                    </div>
                  </div>
                </div>
                
                <!-- Nutrition Info -->
                <div class="mt-8 pt-6 border-t border-cream-200">
                  <h3 class="font-semibold text-bordeaux-900 mb-4">Informations nutritionnelles</h3>
                  <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="text-center p-3 bg-white rounded-lg">
                      <div class="font-bold text-bordeaux-900">420</div>
                      <div class="text-charcoal-600">Calories</div>
                    </div>
                    <div class="text-center p-3 bg-white rounded-lg">
                      <div class="font-bold text-bordeaux-900">28g</div>
                      <div class="text-charcoal-600">Protéines</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Instructions -->
          <div class="lg:col-span-2">
            <h2 class="text-3xl font-display font-bold text-bordeaux-900 mb-8 flex items-center">
              <Icon name="lucide:chef-hat" class="mr-3 text-gold-600" />
              Préparation
            </h2>
            
            <div class="space-y-8">
              <div v-for="(step, index) in steps" :key="index" 
                   class="recipe-step group">
                <div class="flex gap-6">
                  <!-- Numéro -->
                  <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-bordeaux-500 to-bordeaux-700 text-white rounded-full flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                      {{ index + 1 }}
                    </div>
                  </div>
                  
                  <!-- Contenu -->
                  <div class="flex-1">
                    <div class="bg-white rounded-2xl p-6 shadow-soft hover:shadow-premium transition-shadow">
                      <h3 v-if="step.title" class="font-display font-semibold text-xl text-bordeaux-900 mb-3">
                        {{ step.title }}
                      </h3>
                      <p class="text-charcoal-700 leading-relaxed mb-4">
                        {{ step.description }}
                      </p>
                      
                      <!-- Temps et conseils -->
                      <div v-if="step.time || step.tip" class="flex flex-wrap gap-4 text-sm">
                        <div v-if="step.time" class="flex items-center text-gold-600">
                          <Icon name="lucide:timer" class="mr-1" />
                          <span>{{ step.time }}</span>
                        </div>
                        <div v-if="step.tip" class="flex items-center text-bordeaux-600">
                          <Icon name="lucide:lightbulb" class="mr-1" />
                          <span>{{ step.tip }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Commentaires -->
    <section class="py-16 bg-cream-50">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-display font-bold text-bordeaux-900 mb-8 text-center">
          Avis des Gourmets
        </h2>
        
        <!-- Formulaire de commentaire -->
        <div v-if="isAuthenticated" class="bg-white rounded-2xl p-6 shadow-soft mb-8">
          <h3 class="font-semibold text-bordeaux-900 mb-4">Partagez votre expérience</h3>
          <form @submit.prevent="submitComment">
            <textarea 
              v-model="newComment"
              placeholder="Décrivez votre expérience avec cette recette..."
              class="w-full p-4 border border-cream-200 rounded-xl focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent resize-none"
              rows="4"
            ></textarea>
            <div class="flex justify-end mt-4">
              <button type="submit" class="premium-btn">
                <Icon name="lucide:send" class="mr-2" />
                Publier
              </button>
            </div>
          </form>
        </div>
        
        <!-- Liste des commentaires -->
        <div class="space-y-6">
          <div v-for="comment in comments" :key="comment.id" 
               class="bg-white rounded-2xl p-6 shadow-soft hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
              <UserAvatar :user="comment.user" size="md" />
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h4 class="font-semibold text-bordeaux-900">{{ comment.user.name }}</h4>
                  <span class="text-sm text-charcoal-500">{{ formatDate(comment.created_at) }}</span>
                </div>
                <p class="text-charcoal-700 leading-relaxed">
                  {{ comment.body }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const route = useRoute()
const { isAuthenticated } = useAuth()
const { success, error } = useNotifications()

const defaultDescription = "Une interprétation moderne du classique britannique, sublimée par nos techniques d'avant-garde."
const likeText = "J'aime"

const recipe = ref(null)
const isLiked = ref(false)
const likesCount = ref(0)
const newComment = ref('')
const comments = ref([])

const ingredients = ref([
  { name: 'Filet de bœuf', quantity: '800g' },
  { name: 'Pâte feuilletée', quantity: '500g' },
  { name: 'Champignons de Paris', quantity: '300g' },
  { name: 'Échalotes', quantity: '2 pièces' },
  { name: 'Œufs', quantity: '2 pièces' },
  { name: 'Crème fraîche', quantity: '200ml' }
])

const steps = ref([
  {
    title: 'Préparation de la viande',
    description: 'Saisir le filet de bœuf dans une poêle très chaude avec un peu d\'huile. Assaisonner généreusement.',
    time: '10 min',
    tip: 'La viande doit être dorée sur toutes les faces'
  },
  {
    title: 'Duxelles de champignons',
    description: 'Hacher finement les champignons et échalotes. Faire revenir jusqu\'à évaporation complète de l\'eau.',
    time: '15 min',
    tip: 'Bien faire évaporer l\'eau pour éviter la pâte détrempée'
  },
  {
    title: 'Assemblage',
    description: 'Étaler la pâte, disposer la duxelles, placer la viande et enrober délicatement.',
    time: '20 min'
  }
])

const toggleLike = async () => {
  try {
    isLiked.value = !isLiked.value
    likesCount.value += isLiked.value ? 1 : -1
    success(isLiked.value ? 'Recette ajoutée aux favoris' : 'Recette retirée des favoris')
  } catch (err) {
    error('Erreur lors de la mise à jour')
  }
}

const submitComment = async () => {
  if (!newComment.value.trim()) return
  
  try {
    comments.value.unshift({
      id: Date.now(),
      body: newComment.value,
      user: { name: 'Vous', avatar: null },
      created_at: new Date().toISOString()
    })
    
    newComment.value = ''
    success('Commentaire publié avec succès')
  } catch (err) {
    error('Erreur lors de la publication')
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const likeButtonClasses = computed(() => {
  const base = 'recipe-action-btn transition-all duration-300'
  return isLiked.value 
    ? `${base} bg-red-500 text-white hover:bg-red-600` 
    : `${base} hover:bg-red-50 hover:text-red-600`
})

useHead({
  title: `${recipe.value?.title || 'Recette'} - Les Mets Chics`,
  meta: [
    { name: 'description', content: recipe.value?.description || 'Découvrez cette recette d\'exception' }
  ]
})

onMounted(() => {
  recipe.value = {
    title: 'Bœuf Wellington Revisité',
    description: defaultDescription,
    category: { name: 'Signature' },
    prep_time: '45',
    cook_time: '30',
    servings: '4'
  }
  
  likesCount.value = 127
})
</script>

<style scoped>
.recipe-action-btn {
  @apply px-6 py-3 bg-white/20 backdrop-blur-sm text-white border border-white/30 rounded-xl font-medium transition-all duration-300 hover:bg-white/30 flex items-center;
}

.premium-btn {
  @apply px-6 py-3 bg-gradient-to-r from-bordeaux-500 to-bordeaux-700 text-white rounded-xl font-semibold transition-all duration-300 hover:shadow-lg hover:scale-105 flex items-center;
}

.recipe-step {
  @apply opacity-0 transform translate-y-4;
  animation: fadeInUp 0.6s ease-out forwards;
}

.recipe-step:nth-child(1) { animation-delay: 0.1s; }
.recipe-step:nth-child(2) { animation-delay: 0.2s; }
.recipe-step:nth-child(3) { animation-delay: 0.3s; }

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slow-zoom {
  0% { transform: scale(1); }
  100% { transform: scale(1.05); }
}

.animate-slow-zoom {
  animation: slow-zoom 20s ease-in-out infinite alternate;
}
</style>