<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 via-white to-bordeaux-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
      
      <!-- Logo & Titre -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-2xl mb-4 animate-float">
          <Icon name="lucide:chef-hat" class="text-white text-2xl" />
        </div>
        <h1 class="text-3xl font-display font-bold text-bordeaux-900 mb-2 animate-fade-in-up">
          Bienvenue
        </h1>
        <p class="text-charcoal-600 animate-fade-in-up" style="animation-delay: 0.1s">
          Connectez-vous à votre espace gourmet
        </p>
      </div>

      <!-- Formulaire -->
      <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-premium p-8 border border-white/50 animate-fade-in-up" style="animation-delay: 0.2s">
        <form @submit.prevent="handleLogin" class="space-y-6">
          
          <!-- Email -->
          <div class="form-group">
            <label class="form-label">
              <Icon name="lucide:mail" class="mr-2" />
              Adresse email
            </label>
            <input 
              v-model="form.email"
              type="email" 
              required
              class="form-input"
              placeholder="votre@email.com"
              :class="{ 'border-red-300': error }"
            />
          </div>

          <!-- Mot de passe -->
          <div class="form-group">
            <label class="form-label">
              <Icon name="lucide:lock" class="mr-2" />
              Mot de passe
            </label>
            <div class="relative">
              <input 
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="form-input pr-12"
                placeholder="••••••••"
                :class="{ 'border-red-300': error }"
              />
              <button 
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-charcoal-400 hover:text-charcoal-600 transition-colors"
              >
                <Icon :name="showPassword ? 'lucide:eye-off' : 'lucide:eye'" />
              </button>
            </div>
          </div>

          <!-- Message d'erreur -->
          <div v-if="error" class="p-4 bg-red-50 border border-red-200 rounded-xl animate-shake">
            <div class="flex items-center">
              <Icon name="lucide:alert-circle" class="text-red-500 mr-2" />
              <p class="text-red-700 text-sm">{{ error }}</p>
            </div>
          </div>

          <!-- Options -->
          <div class="flex items-center justify-between">
            <label class="flex items-center cursor-pointer">
              <input 
                v-model="form.remember" 
                type="checkbox" 
                class="w-4 h-4 text-bordeaux-600 border-charcoal-300 rounded focus:ring-bordeaux-500 transition-colors"
              />
              <span class="ml-2 text-sm text-charcoal-600">Se souvenir de moi</span>
            </label>
            
            <NuxtLink 
              to="/mot-de-passe-oublie" 
              class="text-sm text-bordeaux-600 hover:text-bordeaux-800 font-medium transition-colors"
            >
              Mot de passe oublié ?
            </NuxtLink>
          </div>

          <!-- Bouton Connexion -->
          <button 
            type="submit" 
            :disabled="loading"
            class="auth-btn-primary group w-full"
          >
            <Icon v-if="loading" name="lucide:loader-2" class="mr-2 animate-spin" />
            <Icon v-else name="lucide:log-in" class="mr-2 group-hover:translate-x-1 transition-transform" />
            {{ loading ? 'Connexion...' : 'Se connecter' }}
          </button>

          <!-- Divider -->
          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-charcoal-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-4 bg-white text-charcoal-500">ou</span>
            </div>
          </div>

          <!-- Google OAuth -->
          <button 
            type="button"
            @click="handleGoogleLogin"
            class="auth-btn-google group w-full"
          >
            <Icon name="lucide:chrome" class="mr-2 group-hover:scale-110 transition-transform" />
            Continuer avec Google
          </button>
        </form>

        <!-- Lien Inscription -->
        <div class="text-center mt-8 pt-6 border-t border-charcoal-100">
          <p class="text-charcoal-600">
            Pas encore de compte ?
            <NuxtLink 
              to="/inscription" 
              class="text-bordeaux-600 hover:text-bordeaux-800 font-semibold ml-1 transition-colors"
            >
              Créer un compte
            </NuxtLink>
          </p>
        </div>
      </div>

      <!-- Message de redirection -->
      <div v-if="redirectMessage" class="mt-6 p-4 bg-gold-50 border border-gold-200 rounded-xl text-center animate-fade-in">
        <Icon name="lucide:info" class="text-gold-600 mb-2" />
        <p class="text-gold-800 text-sm">{{ redirectMessage }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
const { login, getRedirectPath } = useAuth()
const config = useRuntimeConfig()
const route = useRoute()
const { error: notify } = useNotifications()

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
const showPassword = ref(false)

// Message de redirection
const redirectMessage = computed(() => {
  if (route.query.redirect) {
    return 'Vous devez être connecté pour accéder à cette page'
  }
  return ''
})

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
      error.value = result.error || 'Email ou mot de passe incorrect'
    }
  } catch (e) {
    error.value = 'Une erreur est survenue lors de la connexion'
    notify('Erreur de connexion', { type: 'error' })
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
/* Form Styles */
.form-group {
  @apply space-y-2;
}

.form-label {
  @apply flex items-center text-sm font-medium text-bordeaux-700;
}

.form-input {
  @apply w-full px-4 py-3 border border-charcoal-300 rounded-xl bg-white/50 backdrop-blur-sm focus:ring-2 focus:ring-bordeaux-500 focus:border-bordeaux-500 transition-all duration-300 placeholder-charcoal-400;
}

.form-input:focus {
  @apply bg-white shadow-soft;
}

/* Auth Buttons */
.auth-btn-primary {
  @apply px-6 py-4 bg-gradient-to-r from-bordeaux-600 to-bordeaux-800 text-white rounded-xl font-semibold text-lg transition-all duration-300 hover:shadow-xl hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center;
}

.auth-btn-google {
  @apply px-6 py-4 bg-white border-2 border-charcoal-200 text-charcoal-700 rounded-xl font-semibold text-lg transition-all duration-300 hover:bg-charcoal-50 hover:border-charcoal-300 hover:shadow-md flex items-center justify-center;
}

/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-8px) rotate(2deg); }
}

@keyframes fade-in-up {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fade-in {
  0% { opacity: 0; }
  100% { opacity: 1; }
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

.animate-float {
  animation: float 4s ease-in-out infinite;
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out;
}

.animate-fade-in {
  animation: fade-in 0.4s ease-out;
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
}

/* Responsive */
@media (max-width: 640px) {
  .auth-btn-primary,
  .auth-btn-google {
    @apply px-4 py-3 text-base;
  }
}
</style>
