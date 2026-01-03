<template>
  <div>
    <AdminHeader title="Gestion des Catégories">
      <button @click="showCreateModal = true" class="btn-primary">
        <Icon name="lucide:plus" class="mr-2" />
        Nouvelle Catégorie
      </button>
    </AdminHeader>

    <AdminDataTable 
      :columns="categoryColumns"
      :data="categories"
      :loading="loading"
      @edit="editCategory"
      @delete="deleteCategory"
    />

    <AdminCategoryModal 
      v-model="showCreateModal"
      :category="selectedCategory"
      @saved="refreshCategories"
    />
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { getCategories, deleteCategory: apiDeleteCategory } = useAdminApi()

const categories = ref([])
const loading = ref(true)
const showCreateModal = ref(false)
const selectedCategory = ref(null)

const categoryColumns = [
  { key: 'name', label: 'Nom' },
  { key: 'slug', label: 'Slug' },
  { key: 'recipes_count', label: 'Recettes' },
  { key: 'created_at', label: 'Créée le' }
]

const loadCategories = async () => {
  loading.value = true
  categories.value = await getCategories()
  loading.value = false
}

const editCategory = (category) => {
  selectedCategory.value = category
  showCreateModal.value = true
}

const deleteCategory = async (category) => {
  if (confirm('Supprimer cette catégorie ?')) {
    await apiDeleteCategory(category.id)
    await loadCategories()
  }
}

const refreshCategories = () => {
  showCreateModal.value = false
  selectedCategory.value = null
  loadCategories()
}

onMounted(loadCategories)

useHead({
  title: 'Catégories - Admin - Les Mets Chics'
})
</script>