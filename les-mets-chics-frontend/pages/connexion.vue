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
