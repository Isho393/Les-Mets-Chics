<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="close">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-serif font-bold text-primary-900">Changer mon mot de passe</h2>
        <button @click="close" class="text-primary-400 hover:text-primary-600 transition-colors">
          <Icon name="mdi:close" class="text-2xl" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Current Password -->
        <div>
          <label class="block text-sm font-medium text-primary-700 mb-2">Mot de passe actuel</label>
          <div class="relative">
            <input
              v-model="form.current_password"
              :type="showCurrentPassword ? 'text' : 'password'"
              required
              class="w-full px-4 py-3 pr-12 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
              placeholder="••••••••"
            />
            <button
              type="button"
              @click="showCurrentPassword = !showCurrentPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600"
            >
              <Icon :name="showCurrentPassword ? 'mdi:eye-off' : 'mdi:eye'" class="text-xl" />
            </button>
          </div>
        </div>

        <!-- New Password -->
        <div>
          <label class="block text-sm font-medium text-primary-700 mb-2">Nouveau mot de passe</label>
          <div class="relative">
            <input
              v-model="form.password"
              :type="showNewPassword ? 'text' : 'password'"
              required
              minlength="8"
              class="w-full px-4 py-3 pr-12 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
              placeholder="••••••••"
            />
            <button
              type="button"
              @click="showNewPassword = !showNewPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-400 hover:text-primary-600"
            >
              <Icon :name="showNewPassword ? 'mdi:eye-off' : 'mdi:eye'" class="text-xl" />
            </button>
          </div>
          <p class="text-xs text-primary-600 mt-1">Minimum 8 caractères</p>
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-sm font-medium text-primary-700 mb-2">Confirmer le mot de passe</label>
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

        <!-- Error Message -->
        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-start">
          <Icon name="mdi:alert-circle" class="text-xl mr-2 flex-shrink-0 mt-0.5" />
          <span class="text-sm">{{ error }}</span>
        </div>

        <!-- Success Message -->
        <div v-if="success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-start">
          <Icon name="mdi:check-circle" class="text-xl mr-2 flex-shrink-0 mt-0.5" />
          <span class="text-sm">{{ success }}</span>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
          <button
            type="button"
            @click="close"
            class="flex-1 px-6 py-3 border-2 border-primary-500 text-primary-700 rounded-full font-medium hover:bg-primary-50 transition-colors"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="flex-1 px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-full font-medium hover:shadow-lg transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="loading" class="flex items-center justify-center">
              <Icon name="mdi:loading" class="animate-spin mr-2" />
              Changement...
            </span>
            <span v-else>Changer</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  isOpen: Boolean
})

const emit = defineEmits(['close', 'updated'])

const authStore = useAuthStore()
const form = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
})
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)
const loading = ref(false)
const error = ref(null)
const success = ref(null)

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    form.value = {
      current_password: '',
      password: '',
      password_confirmation: ''
    }
    showCurrentPassword.value = false
    showNewPassword.value = false
    showConfirmPassword.value = false
    error.value = null
    success.value = null
  }
})

const handleSubmit = async () => {
  loading.value = true
  error.value = null
  success.value = null

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
    await authStore.updatePassword(form.value)
    success.value = 'Mot de passe changé avec succès !'
    
    setTimeout(() => {
      emit('updated')
      close()
    }, 1500)
  } catch (err) {
    error.value = err.data?.message || 'Une erreur est survenue'
  } finally {
    loading.value = false
  }
}

const close = () => {
  if (!loading.value) {
    emit('close')
  }
}
</script>
