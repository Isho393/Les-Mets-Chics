export const useAdminApi = () => {
  const { $api } = useNuxtApp()

  // Stats Dashboard
  const getAdminStats = async () => {
    return await $api('/admin/stats')
  }

  // Gestion Utilisateurs
  const getUsers = async (filters = {}) => {
    return await $api('/admin/users', { query: filters })
  }

  const createUser = async (userData) => {
    return await $api('/admin/users', { method: 'POST', body: userData })
  }

  const updateUser = async (id, userData) => {
    return await $api(`/admin/users/${id}`, { method: 'PUT', body: userData })
  }

  const deleteUser = async (id) => {
    return await $api(`/admin/users/${id}`, { method: 'DELETE' })
  }

  // Gestion Recettes
  const getRecipes = async (filters = {}) => {
    return await $api('/admin/recipes', { query: filters })
  }

  const deleteRecipe = async (id) => {
    return await $api(`/admin/recipes/${id}`, { method: 'DELETE' })
  }

  // Gestion Commentaires
  const getComments = async (filters = {}) => {
    return await $api('/admin/comments', { query: filters })
  }

  const approveComment = async (id) => {
    return await $api(`/admin/comments/${id}/approve`, { method: 'POST' })
  }

  const rejectComment = async (id) => {
    return await $api(`/admin/comments/${id}/reject`, { method: 'POST' })
  }

  // Gestion Catégories
  const getCategories = async () => {
    return await $api('/admin/categories')
  }

  const createCategory = async (categoryData) => {
    return await $api('/admin/categories', { method: 'POST', body: categoryData })
  }

  const updateCategory = async (id, categoryData) => {
    return await $api(`/admin/categories/${id}`, { method: 'PUT', body: categoryData })
  }

  const deleteCategory = async (id) => {
    return await $api(`/admin/categories/${id}`, { method: 'DELETE' })
  }

  return {
    getAdminStats,
    getUsers,
    createUser,
    updateUser,
    deleteUser,
    getRecipes,
    deleteRecipe,
    getComments,
    approveComment,
    rejectComment,
    getCategories,
    createCategory,
    updateCategory,
    deleteCategory
  }
}