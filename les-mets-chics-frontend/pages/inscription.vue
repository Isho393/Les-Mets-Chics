<template>
  <NuxtLayout name="default">
    <div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-4 py-12">
      <div class="max-w-md w-full">
        <div class="bg-white rounded-3xl shadow-2xl p-8">
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <Icon name="mdi:account-plus" class="text-white text-3xl" />
            </div>
            <h1 class="text-3xl font-serif font-bold text-primary-900 mb-2">Inscription</h1>
            <p class="text-primary-600">Rejoignez notre communauté gastronomique</p>
          </div>

          <div v-if="success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
              <Icon name="mdi:check-circle" class="mr-2 mt-0.5" />
              <div>
                <p class="font-medium">Inscription réussie !</p>
                <p class="text-sm mt-1">Votre compte est en attente de validation.</p>
              </div>
            </div>
          </div>

          <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
              <Icon name="mdi:alert-circle" class="mr-2" />
              <span>{{ error }}</span>
            </div>
          </div>

          <form v-if="!success" @submit.prevent="handleRegister" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">Nom complet</label>
              <input v-model="form.name" type="text" required class="input-field" placeholder="Jean Dupont" />
            </div>

            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">Email</label>
              <input v-model="form.email" type="email" required class="input-field" placeholder="votre@email.com" />
            </div>

            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">Mot de passe</label>
              <input v-model="form.password" type="password" required minlength="8" class="input-field" placeholder="••••••••" />
              <p class="text-xs text-primary-500 mt-1">Minimum 8 caractères</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-primary-700 mb-2">Confirmer le mot de passe</label>
              <input v-model="form.password_confirmation" type="password" required class="input-field" placeholder="••••••••" />
            </div>

            <button type="submit" :disabled="loading" class="btn-submit">
              <span v-if="loading" class="flex items-center justify-center">
                <Icon name="mdi:loading" class="animate-spin mr-2" />
                Inscription...
              </span>
              <span v-else>S'inscrire</span>
            </button>
          </form>

          <NuxtLink v-if="!success" to="/connexion" class="btn-secondary-full mt-4">
            Se connecter
          </NuxtLink>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const authStore = useAuthStore()
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const loading = ref(false)
const error = ref('')
const success = ref(false)

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  try {
    await authStore.register(form.value)
    success.value = true
  } catch (e) {
    error.value = e.data?.message || 'Erreur lors de l\'inscription'
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
  @apply w-full bg-gradient-to-r from-primary-500 to-accent-500 text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all disabled:opacity-50;
}
.btn-secondary-full {
  @apply block w-full text-center border-2 border-primary-500 text-primary-700 py-3 rounded-lg font-semibold hover:bg-primary-50 transition-colors;
}
</style>
