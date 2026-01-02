export const useRecipes = () => {
  const { public: publicApi, private: privateApi } = useApi()
  
  // États réactifs
  const recipes = ref([])
  const currentRecipe = ref(null)
  const categories = ref([])
  const loading = ref(false)
  const error = ref(null)

  // Cache simple
  const cache = reactive({
    recipes: null,
    categories: null,
    recipeDetails: new Map()
  })

  // Récupération des recettes publiques (pour visiteurs)
  const fetchPublicRecipes = async (force = false) => {
    if (cache.recipes && !force) {
      recipes.value = cache.recipes
      return recipes.value
    }

    loading.value = true
    error.value = null

    try {
      const response = await publicApi.getRecipes()
      recipes.value = response.recipes || []
      cache.recipes = recipes.value
      return recipes.value
    } catch (err) {
      error.value = 'Erreur lors du chargement des recettes'
      console.error('Fetch recipes error:', err)
      return []
    } finally {
      loading.value = false
    }
  }

  // Récupération des catégories
  const fetchCategories = async (force = false) => {
    if (cache.categories && !force) {
      categories.value = cache.categories
      return categories.value
    }

    try {
      const response = await publicApi.getCategories()
      categories.value = response.categories || []
      cache.categories = categories.value
      return categories.value
    } catch (err) {
      error.value = 'Erreur lors du chargement des catégories'
      console.error('Fetch categories error:', err)
      return []
    }
  }

  // Récupération d'une recette par slug
  const fetchRecipe = async (slug, force = false) => {
    if (cache.recipeDetails.has(slug) && !force) {
      currentRecipe.value = cache.recipeDetails.get(slug)
      return currentRecipe.value
    }

    loading.value = true
    error.value = null

    try {
      const response = await publicApi.getRecipe(slug)
      currentRecipe.value = response.recipe
      cache.recipeDetails.set(slug, currentRecipe.value)
      return currentRecipe.value
    } catch (err) {
      error.value = 'Recette non trouvée'
      console.error('Fetch recipe error:', err)
      return null
    } finally {
      loading.value = false
    }
  }

  // Récupération des recettes par catégorie
  const fetchRecipesByCategory = async (categorySlug) => {
    loading.value = true
    error.value = null

    try {
      const response = await publicApi.getCategoryRecipes(categorySlug)
      return response.recipes || []
    } catch (err) {
      error.value = 'Erreur lors du chargement des recettes de cette catégorie'
      console.error('Fetch category recipes error:', err)
      return []
    } finally {
      loading.value = false
    }
  }

  // Actions pour utilisateurs connectés
  const toggleLike = async (slug) => {
    try {
      const response = await privateApi.toggleLike(slug)
      
      // Mettre à jour le cache si la recette est chargée
      if (currentRecipe.value && currentRecipe.value.slug === slug) {
        currentRecipe.value.liked = response.liked
        currentRecipe.value.likes_count = response.likes_count
      }
      
      return response
    } catch (err) {
      console.error('Toggle like error:', err)
      throw err
    }
  }

  const addComment = async (slug, body) => {
    try {
      const response = await privateApi.addComment(slug, body)
      
      // Optionnel : recharger les commentaires
      // await fetchRecipeComments(slug, true)
      
      return response
    } catch (err) {
      console.error('Add comment error:', err)
      throw err
    }
  }

  // Utilitaires
  const clearCache = () => {
    cache.recipes = null
    cache.categories = null
    cache.recipeDetails.clear()
  }

  const getRecipeBySlug = (slug) => {
    return recipes.value.find(recipe => recipe.slug === slug)
  }

  const getCategoryBySlug = (slug) => {
    return categories.value.find(category => category.slug === slug)
  }

  return {
    // États
    recipes: readonly(recipes),
    currentRecipe: readonly(currentRecipe),
    categories: readonly(categories),
    loading: readonly(loading),
    error: readonly(error),

    // Actions publiques
    fetchPublicRecipes,
    fetchCategories,
    fetchRecipe,
    fetchRecipesByCategory,

    // Actions privées (auth requise)
    toggleLike,
    addComment,

    // Utilitaires
    clearCache,
    getRecipeBySlug,
    getCategoryBySlug
  }
}