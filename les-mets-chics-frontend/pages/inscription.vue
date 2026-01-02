<template>
  <div class="min-h-screen bg-gradient-to-br from-cream-50 via-white to-gold-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-lg w-full">
      
      <!-- Logo & Titre -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-gold-500 to-gold-700 rounded-2xl mb-4 animate-float">
          <Icon name="lucide:sparkles" class="text-white text-2xl" />
        </div>
        <h1 class="text-3xl font-display font-bold text-bordeaux-900 mb-2 animate-fade-in-up">
          Rejoignez l'Excellence
        </h1>
        <p class="text-charcoal-600 animate-fade-in-up" style="animation-delay: 0.1s">
          Créez votre compte premium et découvrez l'art culinaire
        </p>
      </div>

      <!-- Formulaire -->
      <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-premium p-8 border border-white/50 animate-fade-in-up" style="animation-delay: 0.2s">
        <form @submit.prevent="handleRegister" class="space-y-6">
          
          <!-- Nom -->
          <div class="form-group">
            <label class="form-label">
              <Icon name="lucide:user" class="mr-2" />
              Nom complet
            </label>
            <input 
              v-model="form.name"
              type="text" 
              required
              class="form-input"
              placeholder="Votre nom complet"
              :class="{ 'border-red-300': errors.name, 'border-green-300': form.name && !errors.name }"
              @blur="validateName"
            />
            <p v-if="errors.name" class="form-error">{{ errors.name }}</p>
          </div>

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
              :class="{ 'border-red-300': errors.email, 'border-green-300': form.email && !errors.email }"
              @blur="validateEmail"
            />
            <p v-if="errors.email" class="form-error">{{ errors.email }}</p>
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
                :class="{ 'border-red-300': errors.password, 'border-green-300': form.password && !errors.password }"
                @input="validatePassword"
              />
              <button 
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-charcoal-400 hover:text-charcoal-600 transition-colors"
              >
                <Icon :name="showPassword ? 'lucide:eye-off' : 'lucide:eye'" />
              </button>
            </div>
            
            <!-- Indicateur de force -->
            <div v-if="form.password" class="mt-2">
              <div class="flex gap-1 mb-1">
                <div v-for="i in 4" :key="i" 
                     :class="passwordStrengthClass(i)" 
                     class="h-1 flex-1 rounded-full transition-colors"></div>
              </div>
              <p :class="passwordStrengthTextClass" class="text-xs">
                {{ passwordStrengthText }}
              </p>
            </div>
            <p v-if="errors.password" class="form-error">{{ errors.password }}</p>
          </div>

          <!-- Confirmation mot de passe -->
          <div class="form-group">
            <label class="form-label">
              <Icon name="lucide:shield-check" class="mr-2" />
              Confirmer le mot de passe
            </label>
            <div class="relative">
              <input 
                v-model="form.password_confirmation"
                :type="showPasswordConfirm ? 'text' : 'password'"
                required
                class="form-input pr-12"
                placeholder="••••••••"
                :class="{ 'border-red-300': errors.password_confirmation, 'border-green-300': form.password_confirmation && !errors.password_confirmation }"
                @blur="validatePasswordConfirmation"
              />
              <button 
                type="button"
                @click="showPasswordConfirm = !showPasswordConfirm"
                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-charcoal-400 hover:text-charcoal-600 transition-colors"
              >
                <Icon :name="showPasswordConfirm ? 'lucide:eye-off' : 'lucide:eye'" />
              </button>
            </div>
            <p v-if="errors.password_confirmation" class="form-error">{{ errors.password_confirmation }}</p>
          </div>

          <!-- Conditions -->
          <div class="form-group">
            <label class="flex items-start cursor-pointer">
              <input 
                v-model="form.terms" 
                type="checkbox" 
                required
                class="w-4 h-4 text-bordeaux-600 border-charcoal-300 rounded focus:ring-bordeaux-500 mt-1 mr-3"
              />
              <span class="text-sm text-charcoal-600 leading-relaxed">
                J'accepte les 
                <NuxtLink to="/cgv" class="text-bordeaux-600 hover:text-bordeaux-800 font-medium">
                  conditions générales
                </NuxtLink>
                et la 
                <NuxtLink to="/confidentialite" class="text-bordeaux-600 hover:text-bordeaux-800 font-medium">
                  politique de confidentialité
                </NuxtLink>
              </span>
            </label>
          </div>

          <!-- Newsletter -->
          <div class="form-group">
            <label class="flex items-center cursor-pointer">
              <input 
                v-model="form.newsletter" 
                type="checkbox" 
                class="w-4 h-4 text-gold-600 border-charcoal-300 rounded focus:ring-gold-500 mr-3"
              />
              <span class="text-sm text-charcoal-600">
                Recevoir les dernières recettes et actualités culinaires
              </span>
            </label>
          </div>

          <!-- Message d'erreur global -->
          <div v-if="globalError" class="p-4 bg-red-50 border border-red-200 rounded-xl animate-shake">
            <div class="flex items-center">
              <Icon name="lucide:alert-circle" class="text-red-500 mr-2" />
              <p class="text-red-700 text-sm">{{ globalError }}</p>
            </div>
          </div>

          <!-- Bouton Inscription -->
          <button 
            type="submit" 
            :disabled="loading || !isFormValid"
            class="auth-btn-primary group w-full"
            :class="{ 'opacity-50 cursor-not-allowed': !isFormValid }"
          >
            <Icon v-if="loading" name="lucide:loader-2" class="mr-2 animate-spin" />
            <Icon v-else name="lucide:sparkles" class="mr-2 group-hover:rotate-12 transition-transform" />
            {{ loading ? 'Création du compte...' : 'Créer mon compte premium' }}
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
            @click="registerWithGoogle"
            class="auth-btn-google group w-full"
          >
            <Icon name="lucide:chrome" class="mr-2 group-hover:scale-110 transition-transform" />
            S'inscrire avec Google
          </button>
        </form>

        <!-- Lien Connexion -->
        <div class="text-center mt-8 pt-6 border-t border-charcoal-100">
          <p class="text-charcoal-600">
            Déjà membre ?
            <NuxtLink 
              to="/connexion" 
              class="text-bordeaux-600 hover:text-bordeaux-800 font-semibold ml-1 transition-colors"
            >
              Se connecter
            </NuxtLink>
          </p>
        </div>
      </div>

      <!-- Avantages Premium -->
      <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 animate-fade-in-up" style="animation-delay: 0.4s">
        <div class="text-center p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/50">
          <Icon name="lucide:crown" class="text-gold-600 text-2xl mb-2 mx-auto" />
          <p class="text-sm font-medium text-bordeaux-900">Recettes Exclusives</p>
        </div>
        <div class="text-center p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/50">
          <Icon name="lucide:users" class="text-gold-600 text-2xl mb-2 mx-auto" />
          <p class="text-sm font-medium text-bordeaux-900">Communauté Premium</p>
        </div>
        <div class="text-center p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/50">
          <Icon name="lucide:video" class="text-gold-600 text-2xl mb-2 mx-auto" />
          <p class="text-sm font-medium text-bordeaux-900">Vidéos HD</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { register } = useAuth()
const config = useRuntimeConfig()
const { success, error: notify } = useNotifications()

// SEO
useHead({
  title: 'Inscription - Les Mets Chics',
  meta: [
    { name: 'description', content: 'Rejoignez Les Mets Chics et découvrez des recettes gastronomiques d\'exception.' }
  ]
})

// Middleware
definePageMeta({
  middleware: 'guest'
})

// État
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
  newsletter: true
})

const errors = ref({})
const loading = ref(false)
const globalError = ref('')
const showPassword = ref(false)
const showPasswordConfirm = ref(false)

// Validation
const validateName = () => {
  if (!form.value.name) {
    errors.value.name = 'Le nom est requis'
  } else if (form.value.name.length < 2) {
    errors.value.name = 'Le nom doit contenir au moins 2 caractères'
  } else {
    delete errors.value.name
  }
}

const validateEmail = () => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!form.value.email) {
    errors.value.email = 'L\'email est requis'
  } else if (!emailRegex.test(form.value.email)) {
    errors.value.email = 'Format d\'email invalide'
  } else {
    delete errors.value.email
  }
}

const validatePassword = () => {
  if (!form.value.password) {
    errors.value.password = 'Le mot de passe est requis'
  } else if (form.value.password.length < 8) {
    errors.value.password = 'Le mot de passe doit contenir au moins 8 caractères'
  } else {
    delete errors.value.password
  }
  
  // Revalider la confirmation si elle existe
  if (form.value.password_confirmation) {
    validatePasswordConfirmation()
  }
}

const validatePasswordConfirmation = () => {
  if (form.value.password !== form.value.password_confirmation) {
    errors.value.password_confirmation = 'Les mots de passe ne correspondent pas'
  } else {
    delete errors.value.password_confirmation
  }
}

// Force du mot de passe
const passwordStrength = computed(() => {
  const password = form.value.password
  if (!password) return 0
  
  let strength = 0
  if (password.length >= 8) strength++
  if (/[A-Z]/.test(password)) strength++
  if (/[0-9]/.test(password)) strength++
  if (/[^A-Za-z0-9]/.test(password)) strength++
  
  return strength
})

const passwordStrengthClass = (index) => {
  const strength = passwordStrength.value
  if (index <= strength) {
    if (strength <= 1) return 'bg-red-400'
    if (strength <= 2) return 'bg-yellow-400'
    if (strength <= 3) return 'bg-blue-400'
    return 'bg-green-400'
  }
  return 'bg-charcoal-200'
}

const passwordStrengthText = computed(() => {
  const strength = passwordStrength.value
  if (strength <= 1) return 'Faible'
  if (strength <= 2) return 'Moyen'
  if (strength <= 3) return 'Bon'
  return 'Excellent'
})

const passwordStrengthTextClass = computed(() => {
  const strength = passwordStrength.value
  if (strength <= 1) return 'text-red-600'
  if (strength <= 2) return 'text-yellow-600'
  if (strength <= 3) return 'text-blue-600'
  return 'text-green-600'
})

const isFormValid = computed(() => {
  return form.value.name && 
         form.value.email && 
         form.value.password && 
         form.value.password_confirmation &&
         form.value.terms &&
         Object.keys(errors.value).length === 0
})

// Actions
const handleRegister = async () => {
  // Validation finale
  validateName()
  validateEmail()
  validatePassword()
  validatePasswordConfirmation()
  
  if (Object.keys(errors.value).length > 0) return
  
  loading.value = true
  globalError.value = ''

  try {
    const result = await register(form.value)
    
    if (result.success) {
      success('Compte créé avec succès ! Vérifiez votre email.')
      await navigateTo('/en-attente')
    } else {
      globalError.value = result.error || 'Erreur lors de la création du compte'
    }
  } catch (e) {
    globalError.value = 'Une erreur est survenue'
    notify('Erreur lors de l\'inscription', { type: 'error' })
  } finally {
    loading.value = false
  }
}

const registerWithGoogle = () => {
  window.location.href = `${config.public.apiBase}/auth/google`
}
</script>

<style scoped>
/* Réutilise les styles de connexion */
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

.form-error {
  @apply text-red-600 text-xs mt-1;
}

.auth-btn-primary {
  @apply px-6 py-4 bg-gradient-to-r from-gold-500 to-gold-700 text-white rounded-xl font-semibold text-lg transition-all duration-300 hover:shadow-xl hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 flex items-center justify-center;
}

.auth-btn-google {
  @apply px-6 py-4 bg-white border-2 border-charcoal-200 text-charcoal-700 rounded-xl font-semibold text-lg transition-all duration-300 hover:bg-charcoal-50 hover:border-charcoal-300 hover:shadow-md flex items-center justify-center;
}

/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-8px) rotate(-2deg); }
}

.animate-float {
  animation: float 4s ease-in-out infinite;
}

.animate-fade-in-up {
  animation: fade-in-up 0.6s ease-out;
}

.animate-shake {
  animation: shake 0.5s ease-in-out;
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

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}
</style>