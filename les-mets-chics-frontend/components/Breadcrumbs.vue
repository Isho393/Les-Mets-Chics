<template>
  <nav v-if="breadcrumbs.length > 1" class="bg-cream-50 border-b border-cream-200 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <ol class="flex items-center space-x-2 text-sm">
        <li v-for="(crumb, index) in breadcrumbs" :key="crumb.path" class="flex items-center">
          
          <!-- Séparateur -->
          <Icon 
            v-if="index > 0" 
            name="lucide:chevron-right" 
            class="text-charcoal-400 text-xs mx-2" 
          />
          
          <!-- Lien ou texte -->
          <NuxtLink 
            v-if="!crumb.isLast"
            :to="crumb.path"
            class="text-charcoal-600 hover:text-bordeaux-600 transition-colors font-medium"
          >
            {{ crumb.name }}
          </NuxtLink>
          <span 
            v-else
            class="text-bordeaux-700 font-semibold"
          >
            {{ crumb.name }}
          </span>
        </li>
      </ol>
    </div>
  </nav>
</template>

<script setup>
const { getBreadcrumbs } = useNavigation()

const breadcrumbs = computed(() => getBreadcrumbs())
</script>