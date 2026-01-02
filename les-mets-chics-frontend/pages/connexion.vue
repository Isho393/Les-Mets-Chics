<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 to-bordeaux-50 flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-3xl shadow-premium p-8">
        <!-- Header -->
        <div class="text-center mb-8">
          <div class="w-16 h-16 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <Icon name="lucide:chef-hat" class="text-white text-3xl" />
          </div>
          <h1 class="text-3xl font-display font-bold text-bordeaux-900 mb-2">Connexion</h1>
          <p class="text-charcoal-600">Bienvenue sur Les Mets Chics</p>
        </div>

        <!-- Error Message -->
        <ui-error-message 
          v-if="error"
          :message="error"
          @close="error = ''"
          class="mb-6"
        />

        <!-- Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-bordeaux-700 mb-2">
              Email
            </label>
            <input 
              v-model="form.email"
              type="email" 
              required
              class="input-field"
              placeholder="votre@email.com"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-bordeaux-700 mb-2">
              Mot de passe
            </label>
            <input 
              v-model="form.password"
              type="password" 
              required
              class="input-field"
              placeholder="••••••••"
            />
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input 
                v-model="form.remember"
                type="checkbox" 
                class="rounded text-bordeaux-500 border-cream-300 focus:ring-bordeaux-500 mr-2"
              >
              <span class="text-sm text-charcoal-600">Se souvenir de moi</span>
            </label>
            <NuxtLink 
              to="/mot-de-passe-oublie" 
              class="text-sm text-bordeaux-600 hover:text-bordeaux-800 font-medium"
            >
              Mot de passe oublié ?
            </NuxtLink>
          </div>

          <!-- Submit Button -->
          <ui-button 
            type="submit"
            variant="primary"
            size="lg"
            full-width
            :loading="loading"
          >
            Se connecter
          </ui-button>

          <!-- Divider -->
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-cream-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-charcoal-500">ou</span>
            </div>
          </div>

          <!-- Google Login Button -->
          <ui-button 
            type="button"
            variant="outline"
            size="lg"
            full-width
            @click="handleGoogleLogin"
            class="border-charcoal-300 text-charcoal-700 hover:bg-charcoal-50"
          >
            <svg class="w-5 h-5" viewBox="0 0 24 24">
              <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continuer avec Google
          </ui-button>
        </form>

        <!-- Divider -->
        <div class="relative my-6">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-cream-200"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-charcoal-500">Nouveau sur Les Mets Chics ?</span>
          </div>
        </div>

        <!-- Register Link -->
        <ui-button 
          variant="outline"
          size="lg"
          full-width
          tag="NuxtLink"
          to="/inscription"
        >
          Créer un compte
        </ui-button>
      </div>
    </div>
  </div>
</template>

<script setup>
const { login, getRedirectPath } = useAuth()
const config = useRuntimeConfig()
const route = useRoute()

// SEO
useHead({
  title: 'Connexion - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Connectez-vous à votre compte Les Mets Chics pour accéder aux recettes exclusives.' }
  ]
})

// Middleware pour visiteurs uniquement
definePageMeta({
  middleware: 'guest'
})

// State
const form = ref({
  email: '',
  password: '',
  remember: false
})
const loading = ref(false)
const error = ref('')

// Methods
const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    const result = await login(form.value)
    
    if (result.success) {
      // Redirection selon le statut et le paramètre redirect
      const redirectTo = route.query.redirect || getRedirectPath()
      await navigateTo(redirectTo)
    } else {
      error.value = result.error
    }
  } catch (e) {
    error.value = 'Une erreur est survenue lors de la connexion'
  } finally {
    loading.value = false
  }
}

const handleGoogleLogin = () => {
  // Conserver le paramètre de redirection pour Google OAuth
  const redirectParam = route.query.redirect ? `?redirect=${encodeURIComponent(route.query.redirect)}` : ''
  window.location.href = `${config.public.apiBase}/auth/google${redirectParam}`
}
</script>

<style scoped>
.input-field {
  @apply w-full px-4 py-3 border border-cream-300 rounded-xl focus:ring-2 focus:ring-bordeaux-500 focus:border-bordeaux-500 transition-all;
}
</style>
