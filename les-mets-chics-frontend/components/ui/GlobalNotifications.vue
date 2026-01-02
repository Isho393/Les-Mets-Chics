<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-50 space-y-3 max-w-sm">
      <TransitionGroup
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-x-full"
        enter-to-class="opacity-100 translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-x-0"
        leave-to-class="opacity-0 translate-x-full"
      >
        <div
          v-for="notification in notifications"
          :key="notification.id"
          class="bg-white rounded-xl shadow-premium border-l-4 p-4 max-w-sm"
          :class="notificationClasses(notification.type)"
        >
          <div class="flex items-start gap-3">
            <!-- Icon -->
            <div class="flex-shrink-0">
              <Icon 
                :name="getIcon(notification.type)" 
                class="w-5 h-5"
                :class="iconClasses(notification.type)"
              />
            </div>
            
            <!-- Content -->
            <div class="flex-1 min-w-0">
              <h4 v-if="notification.title" class="font-semibold mb-1" :class="titleClasses(notification.type)">
                {{ notification.title }}
              </h4>
              <p class="text-sm" :class="messageClasses(notification.type)">
                {{ notification.message }}
              </p>
            </div>
            
            <!-- Close Button -->
            <button 
              @click="removeNotification(notification.id)"
              class="flex-shrink-0 p-1 rounded-lg transition-colors duration-200"
              :class="closeButtonClasses(notification.type)"
            >
              <Icon name="lucide:x" class="w-4 h-4" />
            </button>
          </div>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
const uiStore = useUiStore()

// Computed
const notifications = computed(() => uiStore.notifications)

// Methods
const removeNotification = (id) => {
  uiStore.removeNotification(id)
}

const getIcon = (type) => {
  const icons = {
    success: 'lucide:check-circle',
    error: 'lucide:alert-circle',
    warning: 'lucide:alert-triangle',
    info: 'lucide:info'
  }
  return icons[type] || icons.info
}

const notificationClasses = (type) => {
  const classes = {
    success: 'border-green-500',
    error: 'border-red-500',
    warning: 'border-yellow-500',
    info: 'border-blue-500'
  }
  return classes[type] || classes.info
}

const iconClasses = (type) => {
  const classes = {
    success: 'text-green-500',
    error: 'text-red-500',
    warning: 'text-yellow-500',
    info: 'text-blue-500'
  }
  return classes[type] || classes.info
}

const titleClasses = (type) => {
  const classes = {
    success: 'text-green-800',
    error: 'text-red-800',
    warning: 'text-yellow-800',
    info: 'text-blue-800'
  }
  return classes[type] || classes.info
}

const messageClasses = (type) => {
  const classes = {
    success: 'text-green-700',
    error: 'text-red-700',
    warning: 'text-yellow-700',
    info: 'text-blue-700'
  }
  return classes[type] || classes.info
}

const closeButtonClasses = (type) => {
  const classes = {
    success: 'text-green-400 hover:text-green-600 hover:bg-green-50',
    error: 'text-red-400 hover:text-red-600 hover:bg-red-50',
    warning: 'text-yellow-400 hover:text-yellow-600 hover:bg-yellow-50',
    info: 'text-blue-400 hover:text-blue-600 hover:bg-blue-50'
  }
  return classes[type] || classes.info
}
</script>