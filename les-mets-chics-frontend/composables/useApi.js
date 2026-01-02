export const useApi = () => {
  const config = useRuntimeConfig()
  const uiStore = useUiStore()
  
  // Fonction pour créer les headers avec ou sans auth
  const getHeaders = (includeAuth = true, customHeaders = {}) => {
    const headers = {
      'Content-Type': 'application/json',
      ...customHeaders
    }

    if (includeAuth && process.client) {
      const token = localStorage.getItem('token')
      if (token) {
        headers['Authorization'] = `Bearer ${token}`
      }
    }

    return headers
  }

  // Fonction de base pour les appels API avec gestion d'erreurs améliorée
  const apiFetch = async (endpoint, options = {}) => {
    const includeAuth = options.requireAuth !== false
    const headers = getHeaders(includeAuth, options.headers)

    try {
      return await $fetch(`${config.public.apiBase}${endpoint}`, {
        ...options,
        headers,
        // Timeout de 30 secondes
        timeout: 30000
      })
    } catch (error) {
      // Gestion centralisée des erreurs
      if (error.status === 401 && includeAuth) {
        // Token expiré, déconnecter l'utilisateur
        const authStore = useAuthStore()
        authStore.logout()
        
        // Afficher notification
        uiStore.showWarning(
          'Votre session a expiré. Veuillez vous reconnecter.',
          'Session expirée'
        )
        
        await navigateTo('/connexion')
      } else if (error.status === 403) {
        uiStore.showError(
          'Vous n\'avez pas les permissions nécessaires.',
          'Accès refusé'
        )
      } else if (error.status === 429) {
        uiStore.showWarning(
          'Trop de requêtes. Veuillez patienter.',
          'Limite atteinte'
        )
      } else if (error.status >= 500) {
        uiStore.showError(
          'Erreur serveur. Veuillez réessayer plus tard.',
          'Erreur technique'
        )
      } else if (!navigator.onLine) {
        uiStore.showError(
          'Vérifiez votre connexion internet.',
          'Hors ligne'
        )
      }
      
      throw error
    }
  }

  // API publique (pas d'authentification requise)
  const publicApi = {
    // Recipes
    getRecipes: () => apiFetch('/recipes', { requireAuth: false }),
    getRecipe: (slug) => apiFetch(`/recipes/${slug}`, { requireAuth: false }),
    
    // Categories
    getCategories: () => apiFetch('/categories', { requireAuth: false }),
    getCategoryRecipes: (slug) => apiFetch(`/categories/${slug}/recipes`, { requireAuth: false }),
    
    // Comments (lecture)
    getComments: (slug) => apiFetch(`/recipes/${slug}/comments`, { requireAuth: false })
  }

  // API privée (authentification requise)
  const privateApi = {
    // Auth
    getUser: () => apiFetch('/user'),
    updateProfile: (formData) => apiFetch('/user/profile', { method: 'POST', body: formData }),
    updatePassword: (passwords) => apiFetch('/user/password', { method: 'PUT', body: passwords }),
    
    // Likes
    toggleLike: (slug) => apiFetch(`/recipes/${slug}/like`, { method: 'POST' }),
    checkLike: (slug) => apiFetch(`/recipes/${slug}/like/check`),
    
    // Comments (écriture)
    addComment: (slug, body) => apiFetch(`/recipes/${slug}/comments`, {
      method: 'POST',
      body: { body }
    }),
    
    // Uploads
    uploadImage: (formData) => apiFetch('/upload/image', {
      method: 'POST',
      body: formData,
      headers: {}
    }),
    uploadVideo: (formData) => apiFetch('/upload/video', {
      method: 'POST',
      body: formData,
      headers: {}
    })
  }

  return {
    // Rétrocompatibilité - méthodes existantes
    getRecipes: publicApi.getRecipes,
    getRecipe: publicApi.getRecipe,
    getCategories: publicApi.getCategories,
    getCategoryRecipes: publicApi.getCategoryRecipes,
    getComments: publicApi.getComments,
    toggleLike: privateApi.toggleLike,
    checkLike: privateApi.checkLike,
    addComment: privateApi.addComment,
    uploadImage: privateApi.uploadImage,
    uploadVideo: privateApi.uploadVideo,
    
    // Nouvelles API organisées
    public: publicApi,
    private: privateApi,
    
    // Utilitaires
    apiFetch
  }
}
