export const useApi = () => {
  const config = useRuntimeConfig()
  
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

  const apiFetch = async (endpoint, options = {}) => {
    const includeAuth = options.requireAuth !== false
    const headers = getHeaders(includeAuth, options.headers)

    return await $fetch(`${config.public.apiBase}${endpoint}`, {
      ...options,
      headers
    })
  }

  return {
    // Recipes (public)
    getRecipes: () => apiFetch('/recipes', { requireAuth: false }),
    getRecipe: (slug) => apiFetch(`/recipes/${slug}`, { requireAuth: false }),
    
    // Categories (public)
    getCategories: () => apiFetch('/categories', { requireAuth: false }),
    getCategoryRecipes: (slug) => apiFetch(`/categories/${slug}/recipes`, { requireAuth: false }),
    
    // Likes (require auth)
    toggleLike: (slug) => apiFetch(`/recipes/${slug}/like`, { method: 'POST' }),
    checkLike: (slug) => apiFetch(`/recipes/${slug}/like/check`),
    
    // Comments
    getComments: (slug) => apiFetch(`/recipes/${slug}/comments`, { requireAuth: false }),
    addComment: (slug, body) => apiFetch(`/recipes/${slug}/comments`, {
      method: 'POST',
      body: { body }
    }),
    
    // Uploads (require auth)
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
}
