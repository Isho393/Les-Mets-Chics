<template>
  <div 
    v-if="show"
    class="rounded-xl border border-green-200 bg-green-50 p-4 transition-all duration-300"
  >
    <div class="flex items-start gap-3">
      <!-- Icon -->
      <div class="flex-shrink-0">
        <Icon 
          name="lucide:check-circle" 
          class="w-5 h-5 text-green-500"
        />
      </div>
      
      <!-- Content -->
      <div class="flex-1 min-w-0">
        <h3 v-if="title" class="font-semibold text-green-800 mb-1">
          {{ title }}
        </h3>
        
        <div class="text-sm text-green-700">
          <slot>
            {{ message }}
          </slot>
        </div>
        
        <!-- Actions -->
        <div v-if="showAction" class="mt-3">
          <ui-button 
            variant="outline"
            size="sm"
            @click="$emit('action')"
          >
            {{ actionText }}
          </ui-button>
        </div>
      </div>
      
      <!-- Close button -->
      <button 
        v-if="dismissible"
        @click="$emit('close')"
        class="flex-shrink-0 p-1 rounded-lg text-green-400 hover:text-green-600 hover:bg-green-100 transition-colors duration-200"
      >
        <Icon name="lucide:x" class="w-4 h-4" />
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: ''
  },
  message: {
    type: String,
    default: ''
  },
  show: {
    type: Boolean,
    default: true
  },
  dismissible: {
    type: Boolean,
    default: true
  },
  showAction: {
    type: Boolean,
    default: false
  },
  actionText: {
    type: String,
    default: 'Action'
  }
})

defineEmits(['close', 'action'])
</script>