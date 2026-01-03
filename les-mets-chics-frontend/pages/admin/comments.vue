<template>
  <div>
    <AdminHeader title="Modération des Commentaires">
      <div class="flex gap-2">
        <button @click="approveSelected" class="btn-success" :disabled="!selectedComments.length">
          <Icon name="lucide:check" class="mr-2" />
          Approuver ({{ selectedComments.length }})
        </button>
        <button @click="rejectSelected" class="btn-danger" :disabled="!selectedComments.length">
          <Icon name="lucide:x" class="mr-2" />
          Rejeter ({{ selectedComments.length }})
        </button>
      </div>
    </AdminHeader>

    <div class="mb-6 flex gap-4">
      <select v-model="filters.status" @change="loadComments" class="form-select">
        <option value="">Tous</option>
        <option value="pending">En attente</option>
        <option value="approved">Approuvés</option>
        <option value="rejected">Rejetés</option>
      </select>
    </div>

    <div class="space-y-4">
      <div v-for="comment in comments" :key="comment.id" 
           class="bg-white rounded-lg p-6 shadow-sm border">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center">
            <input 
              type="checkbox" 
              :value="comment.id"
              v-model="selectedComments"
              class="mr-3"
            />
            <div>
              <h4 class="font-semibold">{{ comment.user.name }}</h4>
              <p class="text-sm text-gray-600">{{ comment.recipe.title }}</p>
            </div>
          </div>
          <AdminCommentStatus :status="comment.status" />
        </div>
        
        <p class="text-gray-800 mb-4">{{ comment.body }}</p>
        
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-500">{{ formatDate(comment.created_at) }}</span>
          <div class="flex gap-2">
            <button @click="approveComment(comment)" class="btn-sm btn-success">
              Approuver
            </button>
            <button @click="rejectComment(comment)" class="btn-sm btn-danger">
              Rejeter
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { getComments, approveComment: apiApprove, rejectComment: apiReject } = useAdminApi()

const comments = ref([])
const selectedComments = ref([])
const loading = ref(true)
const filters = ref({ status: 'pending' })

const loadComments = async () => {
  loading.value = true
  comments.value = await getComments(filters.value)
  loading.value = false
}

const approveComment = async (comment) => {
  await apiApprove(comment.id)
  await loadComments()
}

const rejectComment = async (comment) => {
  await apiReject(comment.id)
  await loadComments()
}

const approveSelected = async () => {
  for (const id of selectedComments.value) {
    await apiApprove(id)
  }
  selectedComments.value = []
  await loadComments()
}

const rejectSelected = async () => {
  for (const id of selectedComments.value) {
    await apiReject(id)
  }
  selectedComments.value = []
  await loadComments()
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

onMounted(loadComments)

useHead({
  title: 'Commentaires - Admin - Les Mets Chics'
})
</script>