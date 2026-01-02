<template>
  <div v-if="actions.length > 0" class="fixed bottom-6 right-6 z-40">
    <div class="flex flex-col space-y-3">
      
      <!-- Actions principales -->
      <template v-for="action in visibleActions" :key="action.name">
        <button
          v-if="!action.requiresAuth || canPerformAction"
          @click="handleAction(action)"
          :class="getActionClass(action)"
          :title="action.name"
          class="action-button"
        >
          <Icon :name="action.icon" class="text-xl" />
          <span class="sr-only">{{ action.name }}</span>
        </button>
      </template>

      <!-- Menu d'actions supplémentaires -->
      <div v-if="hasMoreActions" class="relative">
        <button
          @click="toggleMoreActions"
          class="action-button bg-charcoal-600 hover:bg-charcoal-700 text-white"
          title="Plus d'actions"
        >
          <Icon name="lucide:more-horizontal" class="text-xl" />
        </button>
        
        <!-- Menu déroulant -->
        <div 
          v-if="moreActionsOpen"
          class="absolute bottom-full right-0 mb-2 bg-white rounded-lg shadow-lg border border-cream-200 py-2 min-w-[160px]"
        >
          <template v-for="action in hiddenActions" :key="action.name">
            <button
              v-if="!action.requiresAuth || canPerformAction"
              @click="handleAction(action)"
              class="w-full flex items-center space-x-3 px-4 py-2 text-left text-charcoal-700 hover:bg-cream-50 transition-colors"
            >
              <Icon :name="action.icon" class="text-lg" />
              <span>{{ action.name }}</span>
            </button>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { getPageActions } = useNavigation()
const { isAuthenticated, isActive } = useAuth()

// États
const moreActionsOpen = ref(false)
const maxVisibleActions = 3

// Actions de la page
const actions = computed(() => getPageActions())
const visibleActions = computed(() => actions.value.slice(0, maxVisibleActions))
const hiddenActions = computed(() => actions.value.slice(maxVisibleActions))
const hasMoreActions = computed(() => hiddenActions.value.length > 0)

// Permissions
const canPerformAction = computed(() => isAuthenticated.value && isActive.value)

// Méthodes
const toggleMoreActions = () => {
  moreActionsOpen.value = !moreActionsOpen.value
}

const handleAction = (action) => {
  moreActionsOpen.value = false
  
  switch (action.action) {
    case 'like':
      handleLike()
      break
    case 'comment':
      handleComment()
      break
    case 'share':
      handleShare()
      break
    case 'filter':
      handleFilter()
      break
    case 'sort':
      handleSort()
      break
    default:
      console.log('Action:', action.action)
  }
}

const getActionClass = (action) => {
  const baseClass = 'action-button'
  
  switch (action.action) {
    case 'like':
      return `${baseClass} bg-red-500 hover:bg-red-600 text-white`
    case 'comment':
      return `${baseClass} bg-blue-500 hover:bg-blue-600 text-white`
    case 'share':
      return `${baseClass} bg-green-500 hover:bg-green-600 text-white`
    case 'filter':
    case 'sort':
      return `${baseClass} bg-bordeaux-500 hover:bg-bordeaux-600 text-white`
    default:
      return `${baseClass} bg-charcoal-500 hover:bg-charcoal-600 text-white`
  }
}

// Actions spécifiques
const handleLike = () => {
  // Logique de like
  console.log('Like action')
}

const handleComment = () => {
  // Scroll vers les commentaires ou ouvrir modal
  const commentsSection = document.getElementById('comments')
  if (commentsSection) {
    commentsSection.scrollIntoView({ behavior: 'smooth' })
  }
}

const handleShare = () => {
  if (navigator.share) {
    navigator.share({
      title: document.title,
      url: window.location.href
    })
  } else {
    // Fallback: copier l'URL
    navigator.clipboard.writeText(window.location.href)
  }
}

const handleFilter = () => {
  // Ouvrir panneau de filtres
  console.log('Filter action')
}

const handleSort = () => {
  // Ouvrir menu de tri
  console.log('Sort action')
}

// Fermer le menu au clic extérieur
onClickOutside(moreActionsOpen, () => {
  moreActionsOpen.value = false
})
</script>

<style scoped>
.action-button {
  @apply w-12 h-12 rounded-full shadow-lg flex items-center justify-center transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bordeaux-500;
}
</style>