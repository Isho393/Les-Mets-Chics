<template>
  <div v-if="!authStore.isAuthenticated" class="bg-gradient-to-r from-primary-600 to-accent-600 text-white">
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
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const authStore = useAuthStore()
</script>

<style scoped>
.btn-banner {
  @apply px-4 py-2 border-2 border-white rounded-full text-sm font-medium hover:bg-white/10 transition-colors;
}

.btn-banner-primary {
  @apply px-4 py-2 bg-white text-primary-900 rounded-full text-sm font-medium hover:bg-primary-50 transition-colors;
}
</style>
