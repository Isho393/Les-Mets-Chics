<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    
    <!-- Loading state -->
    <div v-if="loading" class="p-8 text-center">
      <Icon name="lucide:loader-2" class="w-8 h-8 animate-spin mx-auto text-gray-400" />
      <p class="mt-2 text-gray-600">Chargement...</p>
    </div>

    <!-- Table -->
    <div v-else-if="data.length" class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th v-for="column in columns" :key="column.key" 
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              {{ column.label }}
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in data" :key="item.id" class="hover:bg-gray-50">
            <td v-for="column in columns" :key="column.key" 
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              <slot :name="`cell-${column.key}`" :item="item" :value="getNestedValue(item, column.key)">
                {{ formatCellValue(item, column) }}
              </slot>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex justify-end space-x-2">
                <button @click="$emit('edit', item)" 
                        class="text-bordeaux-600 hover:text-bordeaux-900">
                  <Icon name="lucide:edit" class="w-4 h-4" />
                </button>
                <button @click="$emit('delete', item)" 
                        class="text-red-600 hover:text-red-900">
                  <Icon name="lucide:trash-2" class="w-4 h-4" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Empty state -->
    <div v-else class="p-8 text-center">
      <Icon name="lucide:inbox" class="w-12 h-12 mx-auto text-gray-400" />
      <p class="mt-2 text-gray-600">Aucune donnée disponible</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  columns: {
    type: Array,
    required: true
  },
  data: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

defineEmits(['edit', 'delete'])

const getNestedValue = (obj, path) => {
  return path.split('.').reduce((current, key) => current?.[key], obj)
}

const formatCellValue = (item, column) => {
  const value = getNestedValue(item, column.key)
  
  // Format dates
  if (column.key.includes('_at') && value) {
    return new Date(value).toLocaleDateString('fr-FR')
  }
  
  // Format status
  if (column.key === 'status') {
    const statusLabels = {
      active: 'Actif',
      pending: 'En attente',
      suspended: 'Suspendu',
      published: 'Publié',
      draft: 'Brouillon',
      approved: 'Approuvé',
      rejected: 'Rejeté'
    }
    return statusLabels[value] || value
  }
  
  return value
}
</script>