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
                <p class="text-sm mt-1">Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé.</p>
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
              <label class="block text-sm font-medium text-primary-700 mb-2">Photo de profil (optionnel)</label>
              <div class="flex items-center space-x-4">
                <div v-if="avatarPreview" class="w-20 h-20 rounded-full overflow-hidden border-2 border-primary-200">
                  <img :src="avatarPreview" alt="Avatar preview" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-20 h-20 rounded-full bg-primary-100 flex items-center justify-center">
                  <Icon name="mdi:account" class="text-primary-400 text-3xl" />
                </div>
                <label class="cursor-pointer bg-primary-100 text-primary-700 px-4 py-2 rounded-lg hover:bg-primary-200 transition-colors">
                  <input type="file" @change="handleAvatarChange" accept="image/*" class="hidden" />
                  Choisir une photo
                </label>
              </div>
            </div>

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

            <div class="relative">
              <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-primary-200"></div>
              </div>
              <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-primary-500">ou</span>
              </div>
            </div>

            <button type="button" @click="handleGoogleSignup" class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
              <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Continuer avec Google
            </button>

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
const config = useRuntimeConfig()
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const avatarFile = ref(null)
const avatarPreview = ref(null)
const loading = ref(false)
const error = ref('')
const success = ref(false)

const handleAvatarChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    avatarFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      avatarPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  try {
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('email', form.value.email)
    formData.append('password', form.value.password)
    formData.append('password_confirmation', form.value.password_confirmation)
    if (avatarFile.value) {
      formData.append('avatar', avatarFile.value)
    }

    await authStore.register(formData)
    success.value = true
  } catch (e) {
    error.value = e.data?.message || 'Erreur lors de l\'inscription'
  } finally {
    loading.value = false
  }
}

const handleGoogleSignup = () => {
  window.location.href = `${config.public.apiBase}/auth/google`
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
