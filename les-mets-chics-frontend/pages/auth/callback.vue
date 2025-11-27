<template>
  <NuxtLayout name="default">
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-12">
      <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8 text-center">
          <div v-if="status === 'loading'" class="space-y-4">
            <Icon name="mdi:loading" class="text-primary-500 text-5xl animate-spin mx-auto" />
            <p class="text-primary-700">Authentification en cours...</p>
          </div>

          <div v-else-if="status === 'success'" class="space-y-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto">
              <Icon name="mdi:check-circle" class="text-green-600 text-3xl" />
            </div>
            <h2 class="text-2xl font-serif font-bold text-primary-900">Connexion réussie !</h2>
            <p class="text-primary-600">Redirection en cours...</p>
          </div>

          <div v-else-if="status === 'pending'" class="space-y-4">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
              <Icon name="mdi:clock-outline" class="text-yellow-600 text-3xl" />
            </div>
            <h2 class="text-2xl font-serif font-bold text-primary-900">Compte en attente</h2>
            <p class="text-primary-600">{{ message || 'Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.' }}</p>
            <NuxtLink to="/" class="inline-block mt-4 btn-primary">
              Retour à l'accueil
            </NuxtLink>
          </div>

          <div v-else-if="status === 'error'" class="space-y-4">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
              <Icon name="mdi:alert-circle" class="text-red-600 text-3xl" />
            </div>
            <h2 class="text-2xl font-serif font-bold text-primary-900">Erreur</h2>
            <p class="text-primary-600">{{ message || 'Une erreur est survenue lors de l\'authentification' }}</p>
            <NuxtLink to="/connexion" class="inline-block mt-4 btn-primary">
              Réessayer
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

const status = ref('loading')
const message = ref('')

onMounted(async () => {
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

    // Rediriger vers la page d'accueil après 1 seconde
    setTimeout(() => {
      router.push('/')
    }, 1000)
  }
})
</script>

<style scoped>
.btn-primary {
  @apply bg-gradient-to-r from-primary-500 to-accent-500 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all;
}
</style>
