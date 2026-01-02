export default defineNuxtPlugin(() => {
  const authStore = useAuthStore()
  
  // Initialiser l'authentification côté client uniquement
  if (process.client) {
    authStore.initAuth()
  }
})