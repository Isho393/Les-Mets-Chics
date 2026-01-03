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
            <h1 class="text-3xl font-serif font-bold text-primary-900 mb-2">Mot de passe oublié ?</h1>
            <p class="text-primary-600">Pas de souci, nous allons vous aider à le réinitialiser</p>
          </div>

          <!-- Success Message -->
          <div v-if="success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
              <Icon name="mdi:check-circle" class="text-xl mr-2 flex-shrink-0 mt-0.5" />
              <div>
                <p class="font-medium">Email envoyé !</p>
                <p class="text-sm mt-1">{{ success }}</p>
              </div>
            </div>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
              <Icon name="mdi:alert-circle" class="mr-2" />
              <span>{{ error }}</span>
            </div>
          </div>

          <!-- Form -->
          <form v-if="!success" @submit.prevent="handleSubmit" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">
                Adresse email
              </label>
              <input 
                v-model="email"
                type="email" 
                required
                class="w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                placeholder="votre@email.com"
              />
              <p class="text-xs text-primary-600 mt-2">
                Entrez l'adresse email associée à votre compte. Nous vous enverrons un lien pour réinitialiser votre mot de passe.
              </p>
            </div>

            <button 
              type="submit" 
              :disabled="loading"
              class="w-full bg-gradient-to-r from-primary-500 to-accent-500 text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="flex items-center justify-center">
                <Icon name="mdi:loading" class="animate-spin mr-2" />
                Envoi en cours...
              </span>
              <span v-else>Envoyer le lien de réinitialisation</span>
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
const email = ref('')
const loading = ref(false)
const error = ref('')
const success = ref('')

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  success.value = ''

  try {
    const { data, error: fetchError } = await useFetch(`${config.public.apiBase}/password/forgot`, {
      method: 'POST',
      body: { email: email.value }
    })

    if (fetchError.value) {
      throw fetchError.value
    }

    success.value = data.value.message
    email.value = ''
  } catch (e) {
    error.value = e.data?.message || 'Une erreur est survenue. Veuillez réessayer.'
  } finally {
    loading.value = false
  }
}
</script>
