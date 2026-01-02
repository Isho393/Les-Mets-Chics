<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 to-bordeaux-50 flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-3xl shadow-premium p-8 text-center">
        
        <!-- Loading State -->
        <div v-if="status === 'loading'" class="space-y-4">
          <ui-loading-spinner size="xl" variant="primary" />
          <p class="text-bordeaux-700 font-medium">Authentification en cours...</p>
        </div>

        <!-- Success State -->
        <div v-else-if="status === 'success'" class="space-y-4">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
            <Icon name="lucide:check-circle" class="text-green-600 text-3xl" />
          </div>
          <h2 class="text-2xl font-display font-bold text-bordeaux-900">Connexion réussie !</h2>
          <p class="text-charcoal-600">Redirection en cours...</p>
        </div>

        <!-- Pending State -->
        <div v-else-if="status === 'pending'" class="space-y-4">
          <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
            <Icon name="lucide:clock" class="text-yellow-600 text-3xl" />
          </div>
          <h2 class="text-2xl font-display font-bold text-bordeaux-900">Compte en attente</h2>
          <p class="text-charcoal-600">
            {{ message || 'Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.' }}
          </p>
          <ui-button 
            variant="primary"
            size="lg"
            tag="NuxtLink"
            to="/en-attente"
            class="mt-4"
          >
            Voir le statut
          </ui-button>
        </div>

        <!-- Error State -->
        <div v-else-if="status === 'error'" class="space-y-4">
          <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
            <Icon name="lucide:alert-circle" class="text-red-600 text-3xl" />
          </div>
          <h2 class="text-2xl font-display font-bold text-bordeaux-900">Erreur d'authentification</h2>
          <p class="text-charcoal-600">
            {{ message || 'Une erreur est survenue lors de l\'authentification avec Google.' }}
          </p>
          <div class="flex gap-3 justify-center mt-6">
            <ui-button 
              variant="primary"
              size="md"
              tag="NuxtLink"
              to="/connexion"
            >
              Réessayer
            </ui-button>
            <ui-button 
              variant="ghost"
              size="md"
              tag="NuxtLink"
              to="/"
            >
              Accueil
            </ui-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const { redirectAfterLogin } = useNavigation()
const authStore = useAuthStore()

// SEO
useHead({
  title: 'Authentification - Les Mets Chics',
  meta: [
    { name: 'robots', content: 'noindex, nofollow' }
  ]
})

// State
const status = ref('loading')
const message = ref('')

// Gestion du callback OAuth
onMounted(async () => {
  try {
    const { token, status: authStatus, message: authMessage } = route.query

    status.value = authStatus || 'loading'
    message.value = authMessage || ''

    if (authStatus === 'success' && token) {
      // Stocker le token et récupérer les infos utilisateur
      authStore.token = token
      authStore.isAuthenticated = true
      
      if (process.client) {
        localStorage.setItem('token', token)
      }

      // Récupérer les infos utilisateur
      await authStore.fetchUser()

      // Redirection selon le statut utilisateur
      setTimeout(async () => {
        await redirectAfterLogin()
      }, 1500)
      
    } else if (authStatus === 'pending') {
      // Compte en attente, rediriger vers la page dédiée
      setTimeout(() => {
        navigateTo('/en-attente')
      }, 2000)
      
    } else if (authStatus === 'error') {
      // Erreur d'authentification
      console.error('OAuth Error:', authMessage)
    }
    
  } catch (error) {
    console.error('Callback error:', error)
    status.value = 'error'
    message.value = 'Une erreur inattendue est survenue'
  }
})
</script>


