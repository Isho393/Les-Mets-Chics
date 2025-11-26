<template>
  <Transition
    enter-active-class="transition-all duration-500 ease-out"
    enter-from-class="transform -translate-y-full opacity-0"
    enter-to-class="transform translate-y-0 opacity-100"
    leave-active-class="transition-all duration-300 ease-in"
    leave-from-class="transform translate-y-0 opacity-100"
    leave-to-class="transform -translate-y-full opacity-0"
  >
    <div v-if="showBanner && !authStore.isAuthenticated" class="bg-[#333333] text-white relative">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center space-x-3">
            <Icon name="mdi:information" class="text-2xl" />
            <p class="text-sm sm:text-base">
              <span class="font-semibold">Bienvenue !</span> 
              Connectez-vous pour liker et commenter les recettes.
            </p>
          </div>
          <div class="flex items-center space-x-3">
            <NuxtLink to="/connexion" class="btn-banner">
              Connexion
            </NuxtLink>
            <NuxtLink to="/inscription" class="btn-banner-primary">
              Inscription gratuite
            </NuxtLink>
            <button @click="closeBanner" class="ml-2 text-white/70 hover:text-white transition-colors">
              <Icon name="mdi:close" class="text-xl" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
const authStore = useAuthStore()
const showBanner = ref(true)

const closeBanner = () => {
  showBanner.value = false
}

// Auto-fermeture après 5 secondes
onMounted(() => {
  setTimeout(() => {
    showBanner.value = false
  }, 5000)
})
</script>

<style scoped>
.btn-banner {
  @apply px-4 py-2 border-2 border-white rounded-full text-sm font-medium hover:bg-white/10 transition-colors;
}

.btn-banner-primary {
  @apply px-4 py-2 bg-white text-primary-900 rounded-full text-sm font-medium hover:bg-primary-50 transition-colors;
}
</style>
