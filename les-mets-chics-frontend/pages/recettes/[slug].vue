<template>
  <div class="min-h-screen bg-cream-50">
    
    <!-- Hero Image Section -->
    <section class="relative h-[60vh] lg:h-[70vh] overflow-hidden">
      <img 
        :src="recipe.image || 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'"
        :alt="recipe.title"
        class="w-full h-full object-cover"
      />
      
      <!-- Gradient Overlay -->
      <div class="absolute inset-0 bg-gradient-to-t from-charcoal-900/80 via-charcoal-900/20 to-transparent"></div>
      
      <!-- Content Overlay -->
      <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 w-full">
          <div class="max-w-4xl">
            
            <!-- Category Badge -->
            <div class="mb-4">
              <span class="inline-flex items-center px-4 py-2 bg-gold-600/90 backdrop-blur-sm text-white rounded-full text-sm font-medium">
                <Icon name="lucide:utensils" class="w-4 h-4 mr-2" />
                {{ recipe.category }}
              </span>
            </div>
            
            <!-- Title -->
            <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
              {{ recipe.title }}
            </h1>
            
            <!-- Description -->
            <p class="text-xl text-cream-200 mb-8 max-w-3xl leading-relaxed">
              {{ recipe.description }}
            </p>
            
            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-6 text-white/90">
              <div class="flex items-center gap-2">
                <Icon name="lucide:clock" class="w-5 h-5 text-gold-400" />
                <span class="font-medium">{{ recipe.prep_time }}</span>
              </div>
              <div class="flex items-center gap-2">
                <Icon name="lucide:users" class="w-5 h-5 text-gold-400" />
                <span class="font-medium">{{ recipe.servings }} personnes</span>
              </div>
              <div class="flex items-center gap-2">
                <Icon name="lucide:chef-hat" class="w-5 h-5 text-gold-400" />
                <span class="font-medium">{{ recipe.difficulty }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Back Button -->
      <button 
        @click="$router.back()"
        class="absolute top-6 left-6 p-3 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-xl transition-all duration-300 text-white"
      >
        <Icon name="lucide:arrow-left" class="w-5 h-5" />
      </button>
      
      <!-- Action Buttons -->
      <div class="absolute top-6 right-6 flex gap-3">
        <button 
          @click="toggleLike"
          class="p-3 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-xl transition-all duration-300"
        >
          <Icon 
            name="lucide:heart" 
            :class="isLiked ? 'text-red-400 fill-current' : 'text-white'" 
            class="w-5 h-5"
          />
        </button>
        <button 
          @click="shareRecipe"
          class="p-3 bg-white/10 backdrop-blur-sm hover:bg-white/20 rounded-xl transition-all duration-300 text-white"
        >
          <Icon name="lucide:share-2" class="w-5 h-5" />
        </button>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 lg:py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
          
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-12">
            
            <!-- Ingredients Section -->
            <div class="bg-white rounded-3xl p-8 shadow-soft">
              <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-bordeaux-100 to-bordeaux-200 rounded-2xl flex items-center justify-center">
                  <Icon name="lucide:shopping-cart" class="w-6 h-6 text-bordeaux-800" />
                </div>
                <h2 class="font-display text-3xl font-bold text-bordeaux-800">Ingrédients</h2>
              </div>
              
              <div class="grid sm:grid-cols-2 gap-4">
                <div 
                  v-for="(ingredient, index) in recipe.ingredients" 
                  :key="index"
                  class="flex items-center gap-3 p-4 bg-cream-50 rounded-xl hover:bg-cream-100 transition-colors duration-200"
                >
                  <div class="w-2 h-2 bg-gold-600 rounded-full flex-shrink-0"></div>
                  <span class="text-charcoal-800 font-medium">{{ ingredient.quantity }}</span>
                  <span class="text-charcoal-600">{{ ingredient.name }}</span>
                </div>
              </div>
            </div>

            <!-- Instructions Section -->
            <div class="bg-white rounded-3xl p-8 shadow-soft">
              <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-gold-100 to-gold-200 rounded-2xl flex items-center justify-center">
                  <Icon name="lucide:list-ordered" class="w-6 h-6 text-gold-800" />
                </div>
                <h2 class="font-display text-3xl font-bold text-bordeaux-800">Préparation</h2>
              </div>
              
              <div class="space-y-6">
                <div 
                  v-for="(step, index) in recipe.steps" 
                  :key="index"
                  class="flex gap-6 group"
                >
                  <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-bordeaux-800 to-bordeaux-900 rounded-2xl flex items-center justify-center text-white font-bold text-lg group-hover:scale-110 transition-transform duration-300">
                    {{ index + 1 }}
                  </div>
                  <div class="flex-1 pt-2">
                    <p class="text-charcoal-700 leading-relaxed text-lg">{{ step.description }}</p>
                    <p v-if="step.duration" class="text-charcoal-500 text-sm mt-2 flex items-center gap-1">
                      <Icon name="lucide:timer" class="w-4 h-4" />
                      {{ step.duration }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Video Section -->
            <div v-if="recipe.video_url" class="bg-white rounded-3xl p-8 shadow-soft">
              <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-2xl flex items-center justify-center">
                  <Icon name="lucide:play" class="w-6 h-6 text-red-800" />
                </div>
                <h2 class="font-display text-3xl font-bold text-bordeaux-800">Tutoriel Vidéo</h2>
              </div>
              
              <div class="aspect-video rounded-2xl overflow-hidden bg-charcoal-100">
                <iframe 
                  :src="recipe.video_url"
                  class="w-full h-full"
                  frameborder="0"
                  allowfullscreen
                ></iframe>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-8">
            
            <!-- Chef Info -->
            <div class="bg-white rounded-3xl p-8 shadow-soft">
              <div class="text-center">
                <UserAvatar 
                  :avatar="recipe.author.avatar" 
                  :name="recipe.author.name" 
                  size="xl"
                  class="mx-auto mb-4"
                />
                <h3 class="font-display text-2xl font-bold text-bordeaux-800 mb-2">
                  {{ recipe.author.name }}
                </h3>
                <p class="text-charcoal-600 mb-6">Chef Cuisinier</p>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                  <div class="text-center">
                    <div class="text-2xl font-bold text-bordeaux-800">{{ recipe.author.recipes_count || 12 }}</div>
                    <div class="text-sm text-charcoal-600">Recettes</div>
                  </div>
                  <div class="text-center">
                    <div class="text-2xl font-bold text-bordeaux-800">{{ recipe.author.followers_count || 1200 }}</div>
                    <div class="text-sm text-charcoal-600">Abonnés</div>
                  </div>
                  <div class="text-center">
                    <div class="text-2xl font-bold text-bordeaux-800">4.9</div>
                    <div class="text-sm text-charcoal-600">Note</div>
                  </div>
                </div>
                
                <ui-button variant="outline" size="sm" full-width>
                  Suivre le chef
                </ui-button>
              </div>
            </div>

            <!-- Nutrition Info -->
            <div class="bg-white rounded-3xl p-8 shadow-soft">
              <h3 class="font-display text-xl font-bold text-bordeaux-800 mb-6">Informations Nutritionnelles</h3>
              
              <div class="space-y-4">
                <div v-for="nutrient in nutritionInfo" :key="nutrient.name" class="flex justify-between items-center">
                  <span class="text-charcoal-700">{{ nutrient.name }}</span>
                  <span class="font-semibold text-bordeaux-800">{{ nutrient.value }}</span>
                </div>
              </div>
            </div>

            <!-- Similar Recipes -->
            <div class="bg-white rounded-3xl p-8 shadow-soft">
              <h3 class="font-display text-xl font-bold text-bordeaux-800 mb-6">Recettes Similaires</h3>
              
              <div class="space-y-4">
                <NuxtLink 
                  v-for="similar in similarRecipes" 
                  :key="similar.id"
                  :to="`/recettes/${similar.slug}`"
                  class="flex gap-4 p-3 rounded-xl hover:bg-cream-50 transition-colors duration-200 group"
                >
                  <img 
                    :src="similar.image" 
                    :alt="similar.title"
                    class="w-16 h-16 rounded-xl object-cover group-hover:scale-105 transition-transform duration-300"
                  />
                  <div class="flex-1">
                    <h4 class="font-semibold text-charcoal-800 group-hover:text-bordeaux-800 transition-colors duration-200">
                      {{ similar.title }}
                    </h4>
                    <p class="text-sm text-charcoal-600">{{ similar.prep_time }}</p>
                  </div>
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Comments Section -->
    <section class="py-12 bg-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
          <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center">
            <Icon name="lucide:message-circle" class="w-6 h-6 text-blue-800" />
          </div>
          <h2 class="font-display text-3xl font-bold text-bordeaux-800">Commentaires</h2>
          <span class="px-3 py-1 bg-bordeaux-100 text-bordeaux-800 rounded-full text-sm font-medium">
            {{ comments.length }}
          </span>
        </div>

        <!-- Add Comment Form -->
        <div class="bg-cream-50 rounded-2xl p-6 mb-8">
          <div class="flex gap-4">
            <UserAvatar 
              :avatar="currentUser?.avatar" 
              :name="currentUser?.name" 
              size="md"
            />
            <div class="flex-1">
              <textarea 
                v-model="newComment"
                placeholder="Partagez votre expérience avec cette recette..."
                class="w-full p-4 border border-cream-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-bordeaux-500 focus:border-transparent"
                rows="3"
              ></textarea>
              <div class="flex justify-end mt-3">
                <ui-button 
                  variant="primary" 
                  size="sm"
                  @click="addComment"
                  :disabled="!newComment.trim()"
                >
                  Publier
                </ui-button>
              </div>
            </div>
          </div>
        </div>

        <!-- Comments List -->
        <div class="space-y-6">
          <div 
            v-for="comment in comments" 
            :key="comment.id"
            class="flex gap-4 p-6 bg-cream-50 rounded-2xl"
          >
            <UserAvatar 
              :avatar="comment.user.avatar" 
              :name="comment.user.name" 
              size="md"
            />
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-2">
                <h4 class="font-semibold text-charcoal-800">{{ comment.user.name }}</h4>
                <span class="text-sm text-charcoal-500">{{ formatDate(comment.created_at) }}</span>
              </div>
              <p class="text-charcoal-700 leading-relaxed">{{ comment.body }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

// Route params
const route = useRoute()
const slug = route.params.slug

// SEO
useHead({
  title: `${recipe.title} - Les Mets Chics`,
  meta: [
    { name: 'description', content: recipe.description }
  ]
})

// State
const isLiked = ref(false)
const newComment = ref('')
const currentUser = ref({ name: 'Utilisateur', avatar: null })

// Mock data - À remplacer par des appels API
const recipe = {
  title: 'Risotto aux Truffes Noires',
  description: 'Un classique de la cuisine italienne revisité avec des truffes du Périgord et un parmesan vieilli 24 mois pour une expérience gustative d\'exception.',
  image: 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
  category: 'Plats principaux',
  prep_time: '45 min',
  servings: 4,
  difficulty: 'Moyen',
  video_url: 'https://www.youtube.com/embed/dQw4w9WgXcQ',
  author: {
    name: 'Chef Laurent Dubois',
    avatar: null,
    recipes_count: 24,
    followers_count: 1200
  },
  ingredients: [
    { name: 'Riz Arborio', quantity: '300g' },
    { name: 'Truffes noires', quantity: '50g' },
    { name: 'Parmesan vieilli', quantity: '100g' },
    { name: 'Bouillon de légumes', quantity: '1L' },
    { name: 'Vin blanc sec', quantity: '200ml' },
    { name: 'Échalotes', quantity: '2 pièces' },
    { name: 'Beurre', quantity: '50g' },
    { name: 'Huile d\'olive', quantity: '2 c.à.s' }
  ],
  steps: [
    { 
      description: 'Faites chauffer le bouillon de légumes dans une casserole et maintenez-le au chaud.',
      duration: '5 min'
    },
    { 
      description: 'Émincez finement les échalotes et faites-les revenir dans l\'huile d\'olive jusqu\'à ce qu\'elles soient translucides.',
      duration: '3 min'
    },
    { 
      description: 'Ajoutez le riz Arborio et nacrez-le pendant 2 minutes en remuant constamment.',
      duration: '2 min'
    },
    { 
      description: 'Versez le vin blanc et laissez-le s\'évaporer complètement.',
      duration: '3 min'
    },
    { 
      description: 'Ajoutez le bouillon chaud louche par louche, en remuant constamment jusqu\'à absorption complète avant d\'ajouter la suivante.',
      duration: '18 min'
    },
    { 
      description: 'Incorporez le beurre, le parmesan râpé et les truffes émincées. Mélangez délicatement.',
      duration: '2 min'
    }
  ]
}

const nutritionInfo = [
  { name: 'Calories', value: '420 kcal' },
  { name: 'Protéines', value: '12g' },
  { name: 'Glucides', value: '65g' },
  { name: 'Lipides', value: '14g' },
  { name: 'Fibres', value: '2g' }
]

const similarRecipes = [
  {
    id: 1,
    title: 'Risotto aux Champignons',
    slug: 'risotto-champignons',
    image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
    prep_time: '35 min'
  },
  {
    id: 2,
    title: 'Risotto aux Fruits de Mer',
    slug: 'risotto-fruits-mer',
    image: 'https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
    prep_time: '40 min'
  }
]

const comments = ref([
  {
    id: 1,
    user: { name: 'Marie Dupont', avatar: null },
    body: 'Recette absolument délicieuse ! Les truffes apportent une saveur incomparable. Je recommande vivement.',
    created_at: '2024-01-15T10:30:00Z'
  },
  {
    id: 2,
    user: { name: 'Pierre Martin', avatar: null },
    body: 'Parfait pour un dîner romantique. La texture du risotto était parfaite, crémeux à souhait.',
    created_at: '2024-01-14T18:45:00Z'
  }
])

// Methods
const toggleLike = () => {
  isLiked.value = !isLiked.value
}

const shareRecipe = () => {
  if (navigator.share) {
    navigator.share({
      title: recipe.title,
      text: recipe.description,
      url: window.location.href
    })
  } else {
    // Fallback: copy to clipboard
    navigator.clipboard.writeText(window.location.href)
  }
}

const addComment = () => {
  if (!newComment.value.trim()) return
  
  comments.value.unshift({
    id: Date.now(),
    user: currentUser.value,
    body: newComment.value,
    created_at: new Date().toISOString()
  })
  
  newComment.value = ''
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>