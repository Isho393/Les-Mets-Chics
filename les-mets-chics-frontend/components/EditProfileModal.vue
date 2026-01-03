<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="close">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-serif font-bold text-primary-900">Modifier mon profil</h2>
        <button @click="close" class="text-primary-400 hover:text-primary-600 transition-colors">
          <Icon name="mdi:close" class="text-2xl" />
        </button>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
        <!-- Avatar Preview -->
        <div class="flex flex-col items-center space-y-4">
          <div class="relative">
            <div v-if="previewUrl || authStore.user?.avatar" class="w-24 h-24 rounded-full overflow-hidden border-4 border-primary-200">
              <img :src="previewUrl || authStore.user?.avatar" alt="Avatar" class="w-full h-full object-cover" />
            </div>
            <div v-else class="w-24 h-24 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center">
              <Icon name="mdi:account" class="text-white text-5xl" />
            </div>
            <label class="absolute bottom-0 right-0 bg-primary-500 hover:bg-primary-600 text-white rounded-full p-2 cursor-pointer transition-colors">
              <Icon name="mdi:camera" class="text-xl" />
              <input type="file" accept="image/*" @change="handleFileChange" class="hidden" />
            </label>
          </div>
          <p class="text-sm text-primary-600">Cliquez sur l'icône pour changer votre photo</p>
        </div>

        <!-- Name Input -->
        <div>
          <label class="block text-sm font-medium text-primary-700 mb-2">Nom</label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-3 border border-primary-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
            placeholder="Votre nom"
          />
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
              Enregistrement...
            </span>
            <span v-else>Enregistrer</span>
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
  name: authStore.user?.name || '',
  avatar: null
})
const previewUrl = ref(null)
const loading = ref(false)
const error = ref(null)
const success = ref(null)

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    form.value.name = authStore.user?.name || ''
    previewUrl.value = null
    error.value = null
    success.value = null
  }
})

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.avatar = file
    previewUrl.value = URL.createObjectURL(file)
  }
}

const handleSubmit = async () => {
  loading.value = true
  error.value = null
  success.value = null

  try {
    const formData = new FormData()
    formData.append('name', form.value.name)
    if (form.value.avatar) {
      formData.append('avatar', form.value.avatar)
    }

    await authStore.updateProfile(formData)
    success.value = 'Profil mis à jour avec succès !'
    
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
