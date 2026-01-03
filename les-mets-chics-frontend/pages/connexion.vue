<template>
  <NuxtLayout name="default">
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-12">
      <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8">
          <!-- Header -->
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <Icon name="mdi:chef-hat" class="text-white text-3xl" />
            </div>
            <h1 class="text-3xl font-serif font-bold text-primary-900 mb-2">Connexion</h1>
            <p class="text-primary-600">Bienvenue sur Les Mets Chics</p>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
              <Icon name="mdi:alert-circle" class="mr-2" />
              <span>{{ error }}</span>
            </div>
          </div>

          <!-- Form -->
          <form @submit.prevent="handleLogin" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">
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
              <label class="block text-sm font-medium text-primary-700 mb-2">
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
                <input type="checkbox" class="rounded text-primary-500 mr-2">
                <span class="text-sm text-primary-600">Se souvenir de moi</span>
              </label>
              <NuxtLink to="/mot-de-passe-oublie" class="text-sm text-primary-600 hover:text-primary-900">
                Mot de passe oublié ?
              </NuxtLink>
            </div>

            <!-- Divider -->
            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-primary-200"></div>
              </div>
              <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-primary-500">ou</span>
              </div>
            </div>

            <!-- Google Login Button -->
            <button 
              type="button" 
              @click="handleGoogleLogin"
              class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
            >
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Continuer avec Google
            </button>

            <button 
              type="submit" 
              :disabled="loading"
              class="btn-submit"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <Icon name="mdi:loading" class="animate-spin mr-2" />
                Connexion...
              </span>
              <span v-else>Se connecter</span>
            </button>
          </form>

          <!-- Divider -->
          <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-primary-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-4 bg-white text-primary-500">Nouveau sur Les Mets Chics ?</span>
            </div>
          </div>

          <!-- Register Link -->
          <NuxtLink to="/inscription" class="btn-secondary-full">
            Créer un compte
          </NuxtLink>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const authStore = useAuthStore()
const router = useRouter()
const config = useRuntimeConfig()

const form = ref({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  try {
    await authStore.login(form.value)
    router.push('/')
  } catch (e) {
    error.value = e.data?.message || 'Une erreur est survenue lors de la connexion'
  } finally {
    loading.value = false
  }
}

const handleGoogleLogin = () => {
  window.location.href = `${config.public.apiBase}/auth/google`
}
</script>

<style scoped>
.input-field {
  @apply w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all;
}

.btn-submit {
  @apply w-full bg-gradient-to-r from-primary-500 to-accent-500 text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed;
}

.btn-secondary-full {
  @apply block w-full text-center border-2 border-primary-500 text-primary-700 py-3 rounded-lg font-semibold hover:bg-primary-50 transition-colors;
}
</style>
