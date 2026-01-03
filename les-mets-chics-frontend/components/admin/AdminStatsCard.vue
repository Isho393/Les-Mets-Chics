<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-600">{{ title }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ formattedValue }}</p>
        <p v-if="change" class="text-sm mt-2" :class="changeClass">
          <Icon :name="changeIcon" class="w-4 h-4 inline mr-1" />
          {{ change }}
        </p>
      </div>
      
      <div class="p-3 rounded-full" :class="iconBgClass">
        <Icon :name="icon" class="w-6 h-6" :class="iconClass" />
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  value: [Number, String],
  icon: String,
  color: {
    type: String,
    default: 'blue'
  },
  change: String,
  trend: {
    type: String,
    default: 'up' // up, down, neutral
  }
})

const formattedValue = computed(() => {
  if (typeof props.value === 'number') {
    return props.value.toLocaleString('fr-FR')
  }
  return props.value
})

const colorClasses = {
  blue: {
    bg: 'bg-blue-100',
    icon: 'text-blue-600'
  },
  green: {
    bg: 'bg-green-100',
    icon: 'text-green-600'
  },
  yellow: {
    bg: 'bg-yellow-100',
    icon: 'text-yellow-600'
  },
  red: {
    bg: 'bg-red-100',
    icon: 'text-red-600'
  }
}

const iconBgClass = computed(() => colorClasses[props.color]?.bg || colorClasses.blue.bg)
const iconClass = computed(() => colorClasses[props.color]?.icon || colorClasses.blue.icon)

const changeClass = computed(() => {
  if (props.trend === 'up') return 'text-green-600'
  if (props.trend === 'down') return 'text-red-600'
  return 'text-gray-600'
})

const changeIcon = computed(() => {
  if (props.trend === 'up') return 'lucide:trending-up'
  if (props.trend === 'down') return 'lucide:trending-down'
  return 'lucide:minus'
})
</script>