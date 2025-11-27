<template>
  <NuxtLayout name="default">
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-12">
      <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8">
          <!-- Header -->
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <Icon name="mdi:lock-reset" class="text-white text-3xl" />
            </div>
            <h1 class="text-3xl font-serif font-bold text-primary-900 mb-2">Nouveau mot de passe</h1>
            <p class="text-primary-600">Choisissez un mot de passe sécurisé</p>
          </div>

          <!-- Success Message -->
          <div v-if="success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
              <Icon name="mdi:check-circle" class="text-xl mr-2 flex-shrink-0 mt-0.5" />
              <div>
                <p class="font-medium">Mot de passe réinitialisé !</p>
                <p class="text-sm mt-1">{{ success }}</p>
                <NuxtLink to="/connexion" class="text-sm font-semibold underline mt-2 inline-block">
                  Se connecter maintenant
                </NuxtLink>
              </div>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
              <Icon name="mdi:alert-circle" class="text-xl mr-2 flex-shrink-0 mt-0.5" />
              <div>
                <p>{{ error }}</p>
                <NuxtLink to="/mot-de-passe-oublie" class="text-sm font-semibold underline mt-2 inline-block">
                  Demander un nouveau lien
                </NuxtLink>
              </div>
            </div>
          </div>

          <!-- Form -->
          <form v-if="!success" @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Email (readonly) -->
            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">
                Email
              </label>
              <input 
                :value="email"
                type="email" 
                readonly
                class="w-full px-4 py-3 border border-primary-200 rounded-lg bg-gray-50 text-gray-600"
              />
            </div>

            <!-- New Password -->
            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">
                Nouveau mot de passe
              </label>
              <div class="relative">
                <input 
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  minlength="8"
                  class="w-full px-4 py-3 pr-12 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600"
                >
                  <Icon :name="showPassword ? 'mdi:eye-off' : 'mdi:eye'" class="text-xl" />
                </button>
              </div>
              <p class="text-xs text-primary-600 mt-1">Minimum 8 caractères</p>
            </div>

            <!-- Confirm Password -->
            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">
                Confirmer le mot de passe
              </label>
              <div class="relative">
                <input 
                  v-model="form.password_confirmation"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  required
                  minlength="8"
                  class="w-full px-4 py-3 pr-12 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  @click="showConfirmPassword = !showConfirmPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600"
                >
                  <Icon :name="showConfirmPassword ? 'mdi:eye-off' : 'mdi:eye'" class="text-xl" />
                </button>
              </div>
            </div>

            <button 
              type="submit" 
              :disabled="loading"
              class="w-full bg-gradient-to-r from-primary-500 to-accent-500 text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <Icon name="mdi:loading" class="animate-spin mr-2" />
                Réinitialisation...
              </span>
              <span v-else>Réinitialiser le mot de passe</span>
            </button>
          </form>

          <!-- Back to login -->
          <div class="mt-6 text-center">
            <NuxtLink to="/connexion" class="text-sm text-primary-600 hover:text-primary-900 flex items-center justify-center gap-2">
              <Icon name="mdi:arrow-left" />
              Retour à la connexion
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const config = useRuntimeConfig()
const route = useRoute()
const router = useRouter()

const email = ref(route.query.email || '')
const token = ref(route.query.token || '')

const form = ref({
  password: '',
  password_confirmation: ''
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const loading = ref(false)
const error = ref('')
const success = ref('')

// Vérifier que le token et l'email sont présents
onMounted(() => {
  if (!email.value || !token.value) {
    error.value = 'Lien de réinitialisation invalide. Veuillez demander un nouveau lien.'
  }
})

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  success.value = ''

  // Validation côté client
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Les mots de passe ne correspondent pas'
    loading.value = false
    return
  }

  if (form.value.password.length < 8) {
    error.value = 'Le mot de passe doit contenir au moins 8 caractères'
    loading.value = false
    return
  }

  try {
    const { data, error: fetchError } = await useFetch(`${config.public.apiBase}/password/reset`, {
      method: 'POST',
      body: {
        email: email.value,
        token: token.value,
        password: form.value.password,
        password_confirmation: form.value.password_confirmation
      }
    })

    if (fetchError.value) {
      throw fetchError.value
    }

    success.value = data.value.message
    
    // Rediriger vers la page de connexion après 3 secondes
    setTimeout(() => {
      router.push('/connexion')
    }, 3000)
  } catch (e) {
    error.value = e.data?.message || 'Une erreur est survenue. Veuillez réessayer.'
  } finally {
    loading.value = false
  }
}
</script>
