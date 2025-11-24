<template>
  <NuxtLayout name="default">
    <div v-if="pending" class="max-w-5xl mx-auto px-4 py-12">
      <div class="animate-pulse">
        <div class="h-96 bg-gray-200 rounded-3xl mb-8"></div>
        <div class="h-8 bg-gray-200 rounded w-3/4 mb-4"></div>
        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
      </div>
    </div>

    <div v-else-if="recipe" class="max-w-5xl mx-auto px-4 py-12">
      <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="relative h-96">
          <img :src="recipe.main_image_url" :alt="recipe.title" class="w-full h-full object-cover" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          <div class="absolute bottom-8 left-8 right-8 text-white">
            <span class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm mb-4">
              {{ recipe.category.name }}
            </span>
            <h1 class="text-5xl font-serif font-bold mb-4">{{ recipe.title }}</h1>
            <div class="flex items-center space-x-6 text-sm">
              <span class="flex items-center">
                <Icon name="mdi:clock-outline" class="mr-2" />
                {{ recipe.prep_time + recipe.cook_time }}min
              </span>
              <span class="flex items-center">
                <Icon name="mdi:account-group" class="mr-2" />
                {{ recipe.servings }} personnes
              </span>
              <button @click="toggleLike" class="flex items-center hover:scale-110 transition-transform">
                <Icon :name="isLiked ? 'mdi:heart' : 'mdi:heart-outline'" class="mr-2 text-red-500" />
                {{ likesCount }}
              </button>
            </div>
          </div>
        </div>

        <div class="p-8">
          <div class="flex items-center space-x-4 mb-8 pb-8 border-b border-primary-100">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center">
              <Icon name="mdi:account" class="text-white text-xl" />
            </div>
            <div>
              <p class="text-sm text-primary-600">Créé par</p>
              <p class="font-semibold text-primary-900">{{ recipe.author.name }}</p>
            </div>
          </div>

          <div class="prose max-w-none mb-8">
            <p class="text-lg text-primary-700">{{ recipe.description }}</p>
          </div>

          <div v-if="recipe.video_url" class="mb-8">
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Vidéo de la recette</h2>
            <video :src="recipe.video_url" controls class="w-full rounded-2xl"></video>
          </div>

          <div class="grid md:grid-cols-2 gap-8 mb-8">
            <div>
              <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Ingrédients</h2>
              <ul class="space-y-2">
                <li v-for="(ingredient, i) in recipe.ingredients" :key="i" class="flex items-start">
                  <Icon name="mdi:check-circle" class="text-accent-500 mr-2 mt-1" />
                  <span>{{ ingredient }}</span>
                </li>
              </ul>
            </div>

            <div>
              <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Étapes</h2>
              <ol class="space-y-4">
                <li v-for="(step, i) in recipe.steps" :key="i" class="flex">
                  <span class="flex-shrink-0 w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center font-bold mr-3">
                    {{ i + 1 }}
                  </span>
                  <span class="pt-1">{{ step }}</span>
                </li>
              </ol>
            </div>
          </div>

          <div class="border-t border-primary-100 pt-8">
            <h2 class="text-2xl font-serif font-bold text-primary-900 mb-6">Commentaires ({{ recipe.comments.length }})</h2>
            
            <form v-if="authStore.isAuthenticated" @submit.prevent="submitComment" class="mb-8">
              <textarea 
                v-model="newComment"
                placeholder="Partagez votre avis..."
                class="w-full px-4 py-3 border-2 border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 resize-none"
                rows="3"
              ></textarea>
              <button type="submit" :disabled="!newComment.trim()" class="btn-primary mt-2">
                Publier le commentaire
              </button>
            </form>

            <div class="space-y-4">
              <div v-for="comment in recipe.comments" :key="comment.id" class="bg-primary-50 rounded-lg p-4">
                <div class="flex items-center space-x-3 mb-2">
                  <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center">
                    <Icon name="mdi:account" class="text-white" />
                  </div>
                  <div>
                    <p class="font-semibold text-primary-900">{{ comment.user.name }}</p>
                    <p class="text-sm text-primary-600">{{ comment.created_at }}</p>
                  </div>
                </div>
                <p class="text-primary-700">{{ comment.body }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const route = useRoute()
const api = useApi()
const authStore = useAuthStore()

const { data: recipeData, pending } = await useAsyncData(`recipe-${route.params.slug}`, () => 
  api.getRecipe(route.params.slug)
)

const recipe = computed(() => recipeData.value?.recipe)
const isLiked = ref(false)
const likesCount = ref(recipe.value?.likes_count || 0)
const newComment = ref('')

const toggleLike = async () => {
  if (!authStore.isAuthenticated) {
    navigateTo('/connexion')
    return
  }
  try {
    const result = await api.toggleLike(route.params.slug)
    isLiked.value = result.liked
    likesCount.value = result.likes_count
  } catch (error) {
    console.error('Erreur lors du like:', error)
  }
}

const submitComment = async () => {
  if (!authStore.isAuthenticated) {
    navigateTo('/connexion')
    return
  }
  try {
    await api.addComment(route.params.slug, newComment.value)
    newComment.value = ''
    refreshNuxtData(`recipe-${route.params.slug}`)
  } catch (error) {
    console.error('Erreur lors de l\'ajout du commentaire:', error)
  }
}

onMounted(async () => {
  if (authStore.isAuthenticated) {
    try {
      const result = await api.checkLike(route.params.slug)
      isLiked.value = result.liked
    } catch (error) {
      console.error('Erreur lors de la vérification du like:', error)
    }
  }
})
</script>

<style scoped>
.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-accent-500 text-white px-6 py-2.5 rounded-full font-medium hover:shadow-lg transition-all disabled:opacity-50;
}
</style>
