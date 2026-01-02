<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-[9999] space-y-3">
      <TransitionGroup
        name="notification"
        tag="div"
        class="space-y-3"
      >
        <div
          v-for="notification in notifications"
          :key="notification.id"
          :class="notificationClasses(notification)"
          @click="removeNotification(notification.id)"
        >
          <!-- Icon -->
          <div class="flex-shrink-0">
            <Icon 
              :name="getIcon(notification.type)" 
              :class="iconClasses(notification.type)"
            />
          </div>
          
          <!-- Content -->
          <div class="flex-1 min-w-0">
            <h4 v-if="notification.title" class="font-semibold text-sm mb-1">
              {{ notification.title }}
            </h4>
            <p class="text-sm opacity-90">
              {{ notification.message }}
            </p>
          </div>
          
          <!-- Close Button -->
          <button 
            @click.stop="removeNotification(notification.id)"
            class="flex-shrink-0 p-1 rounded-full hover:bg-black/10 transition-colors"
          >
            <Icon name="lucide:x" class="w-4 h-4" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
const { notifications, removeNotification } = useNotifications()

const notificationClasses = (notification) => {
  const baseClasses = [
    'flex items-start gap-3 p-4 rounded-xl shadow-lg backdrop-blur-sm',
    'border cursor-pointer transition-all duration-300',
    'hover:scale-105 hover:shadow-xl max-w-sm'
  ]
  
  const typeClasses = {
    success: 'bg-green-50/90 border-green-200 text-green-800',
    error: 'bg-red-50/90 border-red-200 text-red-800',
    warning: 'bg-yellow-50/90 border-yellow-200 text-yellow-800',
    info: 'bg-blue-50/90 border-blue-200 text-blue-800',
    premium: 'bg-gradient-to-r from-gold-50/90 to-bordeaux-50/90 border-gold-200 text-bordeaux-800'
  }
  
  return [...baseClasses, typeClasses[notification.type] || typeClasses.info].join(' ')
}

const iconClasses = (type) => {
  const classes = 'w-5 h-5'
  
  const typeClasses = {
    success: 'text-green-600',
    error: 'text-red-600',
    warning: 'text-yellow-600',
    info: 'text-blue-600',
    premium: 'text-gold-600'
  }
  
  return `${classes} ${typeClasses[type] || typeClasses.info}`
}

const getIcon = (type) => {
  const icons = {
    success: 'lucide:check-circle',
    error: 'lucide:x-circle',
    warning: 'lucide:alert-triangle',
    info: 'lucide:info',
    premium: 'lucide:crown'
  }
  
  return icons[type] || icons.info
}
</script>

<style scoped>
.notification-enter-active {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.notification-leave-active {
  transition: all 0.3s ease-in;
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(100%) scale(0.8);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(100%) scale(0.8);
}

.notification-move {
  transition: transform 0.3s ease;
}
</style>