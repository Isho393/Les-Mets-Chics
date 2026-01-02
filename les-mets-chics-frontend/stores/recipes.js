import { defineStore } from 'pinia'

export const useRecipesStore = defineStore('recipes', {
  state: () => ({
    // Cache des recettes
    publicRecipes: [],
    categories: [],
    currentRecipe: null,
    
    // États de chargement
    loading: {
      recipes: false,
      categories: false,
      recipe: false
    },
    
    // Erreurs
    error: null,
    
    // Métadonnées
    lastFetch: {
      recipes: null,
      categories: null
    },
    
    // Cache TTL (5 minutes)
    cacheTTL: 5 * 60 * 1000
  }),

  getters: {
    // Recettes par catégorie
    recipesByCategory: (state) => (categorySlug) => {
      return state.publicRecipes.filter(recipe => 
        recipe.category?.slug === categorySlug
      )
    },

    // Recette par slug
    recipeBySlug: (state) => (slug) => {
      return state.publicRecipes.find(recipe => recipe.slug === slug)
    },

    // Catégorie par slug
    categoryBySlug: (state) => (slug) => {
      return state.categories.find(category => category.slug === slug)
    },

    // Vérification si le cache est valide
    isCacheValid: (state) => (type) => {
      const lastFetch = state.lastFetch[type]
      if (!lastFetch) return false
      return (Date.now() - lastFetch) < state.cacheTTL
    },

    // Recettes récentes (pour l'accueil)
    featuredRecipes: (state) => {
      return state.publicRecipes.slice(0, 6)
    },

    // Statistiques
    recipesCount: (state) => state.publicRecipes.length,
    categoriesCount: (state) => state.categories.length
  },

  actions: {
    // Gestion des erreurs
    setError(error) {
      this.error = error
    },

    clearError() {
      this.error = null
    },

    // Gestion du loading
    setLoading(type, loading) {
      this.loading[type] = loading
    },

    // Récupération des recettes publiques
    async fetchPublicRecipes(force = false) {
      // Vérifier le cache
      if (!force && this.isCacheValid('recipes') && this.publicRecipes.length > 0) {
        return this.publicRecipes
      }

      this.setLoading('recipes', true)
      this.clearError()

      try {
        const { public: publicApi } = useApi()
        const response = await publicApi.getRecipes()
        
        this.publicRecipes = response.recipes || []
        this.lastFetch.recipes = Date.now()
        
        return this.publicRecipes
      } catch (error) {
        this.setError('Erreur lors du chargement des recettes')
        console.error('Fetch public recipes error:', error)
        return []
      } finally {
        this.setLoading('recipes', false)
      }
    },

    // Récupération des catégories
    async fetchCategories(force = false) {
      // Vérifier le cache
      if (!force && this.isCacheValid('categories') && this.categories.length > 0) {
        return this.categories
      }

      this.setLoading('categories', true)
      this.clearError()

      try {
        const { public: publicApi } = useApi()
        const response = await publicApi.getCategories()
        
        this.categories = response.categories || []
        this.lastFetch.categories = Date.now()
        
        return this.categories
      } catch (error) {
        this.setError('Erreur lors du chargement des catégories')
        console.error('Fetch categories error:', error)
        return []
      } finally {
        this.setLoading('categories', false)
      }
    },

    // Récupération d'une recette spécifique
    async fetchRecipe(slug, force = false) {
      // Vérifier si déjà en cache
      if (!force && this.currentRecipe?.slug === slug) {
        return this.currentRecipe
      }

      this.setLoading('recipe', true)
      this.clearError()

      try {
        const { public: publicApi } = useApi()
        const response = await publicApi.getRecipe(slug)
        
        this.currentRecipe = response.recipe
        return this.currentRecipe
      } catch (error) {
        this.setError('Recette non trouvée')
        console.error('Fetch recipe error:', error)
        return null
      } finally {
        this.setLoading('recipe', false)
      }
    },

    // Mise à jour d'une recette dans le cache (après like par exemple)
    updateRecipeInCache(slug, updates) {
      // Mettre à jour dans la liste publique
      const recipeIndex = this.publicRecipes.findIndex(r => r.slug === slug)
      if (recipeIndex !== -1) {
        this.publicRecipes[recipeIndex] = {
          ...this.publicRecipes[recipeIndex],
          ...updates
        }
      }

      // Mettre à jour la recette courante
      if (this.currentRecipe?.slug === slug) {
        this.currentRecipe = {
          ...this.currentRecipe,
          ...updates
        }
      }
    },

    // Nettoyage du cache
    clearCache() {
      this.publicRecipes = []
      this.categories = []
      this.currentRecipe = null
      this.lastFetch.recipes = null
      this.lastFetch.categories = null
      this.clearError()
    },

    // Préchargement des données essentielles
    async preloadEssentialData() {
      await Promise.all([
        this.fetchPublicRecipes(),
        this.fetchCategories()
      ])
    }
  }
})