<template>
  <div>
    <AdminHeader title="Gestion des Utilisateurs">
      <button @click="showCreateModal = true" class="btn-primary">
        <Icon name="lucide:plus" class="mr-2" />
        Nouvel Utilisateur
      </button>
    </AdminHeader>

    <AdminDataTable 
      :columns="userColumns"
      :data="users"
      :loading="loading"
      @edit="editUser"
      @delete="deleteUser"
    />

    <AdminUserModal 
      v-model="showCreateModal"
      @saved="refreshUsers"
    />
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { getUsers, deleteUser: apiDeleteUser } = useAdminApi()

const users = ref([])
const loading = ref(true)
const showCreateModal = ref(false)

const userColumns = [
  { key: 'name', label: 'Nom' },
  { key: 'email', label: 'Email' },
  { key: 'role', label: 'Rôle' },
  { key: 'status', label: 'Statut' },
  { key: 'created_at', label: 'Inscription' }
]

const loadUsers = async () => {
  loading.value = true
  users.value = await getUsers()
  loading.value = false
}

const editUser = (user) => {
  // Logique d'édition
}

const deleteUser = async (user) => {
  if (confirm('Supprimer cet utilisateur ?')) {
    await apiDeleteUser(user.id)
    await loadUsers()
  }
}

const refreshUsers = () => {
  showCreateModal.value = false
  loadUsers()
}

onMounted(loadUsers)

useHead({
  title: 'Utilisateurs - Admin - Les Mets Chics'
})
</script>