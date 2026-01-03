<template>
  <div>
    <AdminHeader title="Gestion des Recettes">
      <NuxtLink to="/admin/recipes/create" class="btn-primary">
        <Icon name="lucide:plus" class="mr-2" />
        Nouvelle Recette
      </NuxtLink>
    </AdminHeader>

    <div class="mb-6 flex gap-4">
      <select v-model="filters.status" @change="loadRecipes" class="form-select">
        <option value="">Tous les statuts</option>
        <option value="published">Publiées</option>
        <option value="draft">Brouillons</option>
      </select>
      
      <select v-model="filters.category" @change="loadRecipes" class="form-select">
        <option value="">Toutes les catégories</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>
    </div>

    <AdminDataTable 
      :columns="recipeColumns"
      :data="recipes"
      :loading="loading"
      @edit="editRecipe"
      @delete="deleteRecipe"
    />
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { getRecipes, getCategories, deleteRecipe: apiDeleteRecipe } = useAdminApi()

const recipes = ref([])
const categories = ref([])
const loading = ref(true)
const filters = ref({ status: '', category: '' })

const recipeColumns = [
  { key: 'title', label: 'Titre' },
  { key: 'category', label: 'Catégorie' },
  { key: 'author', label: 'Auteur' },
  { key: 'status', label: 'Statut' },
  { key: 'created_at', label: 'Créée le' }
]

const loadRecipes = async () => {
  loading.value = true
  recipes.value = await getRecipes(filters.value)
  loading.value = false
}

const editRecipe = (recipe) => {
  navigateTo(`/admin/recipes/${recipe.id}/edit`)
}

const deleteRecipe = async (recipe) => {
  if (confirm('Supprimer cette recette ?')) {
    await apiDeleteRecipe(recipe.id)
    await loadRecipes()
  }
}

onMounted(async () => {
  categories.value = await getCategories()
  await loadRecipes()
})

useHead({
  title: 'Recettes - Admin - Les Mets Chics'
})
</script>