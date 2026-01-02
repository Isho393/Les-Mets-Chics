<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 to-bordeaux-50 flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-3xl shadow-premium p-8">
        <div class="text-center mb-8">
          <div class="w-16 h-16 bg-gradient-to-br from-bordeaux-600 to-bordeaux-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <Icon name="lucide:user-plus" class="text-white text-3xl" />
          </div>
          <h1 class="text-3xl font-display font-bold text-bordeaux-900 mb-2">Inscription</h1>
          <p class="text-charcoal-600">Rejoignez notre communauté gastronomique</p>
        </div>

        <!-- Messages -->
        <ui-success-message 
          v-if="success"
          title="Inscription réussie !"
          message="Votre compte est en attente de validation. Vous recevrez un email une fois votre compte activé."
          :dismissible="false"
          class="mb-6"
        />

        <ui-error-message 
          v-if="error"
          :message="error"
          @close="error = ''"
          class="mb-6"
        />

        <form v-if="!success" @submit.prevent="handleRegister" class="space-y-6">
          <!-- Avatar Upload -->
          <div>
            <label class="block text-sm font-medium text-bordeaux-700 mb-2">Photo de profil (optionnel)</label>
            <div class="flex items-center space-x-4">
              <div v-if="avatarPreview" class="w-20 h-20 rounded-full overflow-hidden border-2 border-bordeaux-200">
                <img :src="avatarPreview" alt="Avatar preview" class="w-full h-full object-cover" />
              </div>
              <div v-else class="w-20 h-20 rounded-full bg-bordeaux-100 flex items-center justify-center">
                <Icon name="lucide:user" class="text-bordeaux-400 text-3xl" />
              </div>
              <label class="cursor-pointer bg-bordeaux-100 text-bordeaux-700 px-4 py-2 rounded-lg hover:bg-bordeaux-200 transition-colors">
                <input type="file" @change="handleAvatarChange" accept="image/*" class="hidden" />
                Choisir une photo
              </label>
            </div>
          </div>

          <!-- Form Fields -->
          <div>
            <label class="block text-sm font-medium text-bordeaux-700 mb-2">Nom complet</label>
            <input v-model="form.name" type="text" required class="input-field" placeholder="Jean Dupont" />
          </div>

          <div>
            <label class="block text-sm font-medium text-bordeaux-700 mb-2">Email</label>
            <input v-model="form.email" type="email" required class="input-field" placeholder="votre@email.com" />
          </div>

          <div>
            <label class="block text-sm font-medium text-bordeaux-700 mb-2">Mot de passe</label>
            <input v-model="form.password" type="password" required minlength="8" class="input-field" placeholder="••••••••" />
            <p class="text-xs text-charcoal-500 mt-1">Minimum 8 caractères</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-bordeaux-700 mb-2">Confirmer le mot de passe</label>
            <input v-model="form.password_confirmation" type="password" required class="input-field" placeholder="••••••••" />
          </div>

          <!-- Divider -->
          <div class="relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-cream-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-white text-charcoal-500">ou</span>
            </div>
          </div>

          <!-- Google Signup -->
          <ui-button 
            type="button"
            variant="outline"
            size="lg"
            full-width
            @click="handleGoogleSignup"
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

          <!-- Submit Button -->
          <ui-button 
            type="submit"
            variant="primary"
            size="lg"
            full-width
            :loading="loading"
          >
            S'inscrire
          </ui-button>
        </form>

        <!-- Login Link -->
        <div v-if="!success" class="mt-6 text-center">
          <p class="text-sm text-charcoal-600">
            Déjà membre ?
            <NuxtLink to="/connexion" class="text-bordeaux-700 hover:text-bordeaux-800 font-medium">
              Se connecter
            </NuxtLink>
          </p>
        </div>

        <!-- Success Actions -->
        <div v-if="success" class="mt-6 space-y-3">
          <ui-button 
            variant="primary"
            size="lg"
            full-width
            tag="NuxtLink"
            to="/en-attente"
          >
            Voir le statut de mon compte
          </ui-button>
          
          <ui-button 
            variant="ghost"
            size="md"
            full-width
            tag="NuxtLink"
            to="/"
          >
            Retour à l'accueil
          </ui-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { register } = useAuth()
const config = useRuntimeConfig()

// SEO
useHead({
  title: 'Inscription - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Rejoignez la communauté Les Mets Chics et découvrez des recettes gastronomiques exclusives.' }
  ]
})

// Middleware pour visiteurs uniquement
definePageMeta({
  middleware: 'guest'
})

// State
const form = ref({ 
  name: '', 
  email: '', 
  password: '', 
  password_confirmation: '' 
})
const avatarFile = ref(null)
const avatarPreview = ref(null)
const loading = ref(false)
const error = ref('')
const success = ref(false)

// Methods
const handleAvatarChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    // Validation
    if (file.size > 2 * 1024 * 1024) { // 2MB
      error.value = 'La photo ne doit pas dépasser 2MB'
      return
    }
    
    if (!['image/jpeg', 'image/png', 'image/jpg', 'image/gif'].includes(file.type)) {
      error.value = 'Format de photo non supporté (JPEG, PNG, JPG, GIF uniquement)'
      return
    }
    
    avatarFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      avatarPreview.value = e.target.result
    }
    reader.readAsDataURL(file)
    error.value = ''
  }
}

const handleRegister = async () => {
  loading.value = true
  error.value = ''
  
  // Validation côté client
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Les mots de passe ne correspondent pas'
    loading.value = false
    return
  }
  
  try {
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('email', form.value.email)
    formData.append('password', form.value.password)
    formData.append('password_confirmation', form.value.password_confirmation)
    
    if (avatarFile.value) {
      formData.append('avatar', avatarFile.value)
    }

    const result = await register(formData)
    
    if (result.success) {
      success.value = true
      // Reset form
      form.value = { name: '', email: '', password: '', password_confirmation: '' }
      avatarFile.value = null
      avatarPreview.value = null
    } else {
      error.value = result.error
    }
  } catch (e) {
    error.value = 'Erreur lors de l\'inscription. Veuillez réessayer.'
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
  @apply w-full px-4 py-3 border border-cream-300 rounded-xl focus:ring-2 focus:ring-bordeaux-500 focus:border-bordeaux-500 transition-all;
}
</style>
