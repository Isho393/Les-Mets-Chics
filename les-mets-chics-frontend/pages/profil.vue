<template>
  <NuxtLayout name="default">
    <div class="max-w-4xl mx-auto px-4 py-12">
      <div class="bg-white rounded-3xl shadow-2xl p-8">
        <div class="flex items-center space-x-6 mb-8 pb-8 border-b border-primary-100">
          <div class="w-24 h-24 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center">
            <Icon name="mdi:account" class="text-white text-5xl" />
          </div>
          <div>
            <h1 class="text-3xl font-serif font-bold text-primary-900">{{ authStore.user?.name }}</h1>
            <p class="text-primary-600">{{ authStore.user?.email }}</p>
            <div class="flex items-center space-x-4 mt-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                <Icon name="mdi:shield-account" class="mr-1" />
                {{ authStore.user?.role }}
              </span>
              <span :class="statusClass">
                <Icon name="mdi:circle" class="mr-1 text-xs" />
                {{ statusText }}
              </span>
            </div>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
          <div class="bg-primary-50 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-primary-900">Mes Recettes</h3>
              <Icon name="mdi:book-open-variant" class="text-2xl text-primary-500" />
            </div>
            <p class="text-3xl font-bold text-primary-900">0</p>
            <p class="text-sm text-primary-600">recettes publiées</p>
          </div>

          <div class="bg-accent-50 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-primary-900">Mes Favoris</h3>
              <Icon name="mdi:heart" class="text-2xl text-red-500" />
            </div>
            <p class="text-3xl font-bold text-primary-900">0</p>
            <p class="text-sm text-primary-600">recettes aimées</p>
          </div>
        </div>

        <div class="mt-8 pt-8 border-t border-primary-100">
          <h2 class="text-2xl font-serif font-bold text-primary-900 mb-4">Paramètres du compte</h2>
          <div class="space-y-4">
            <button class="w-full text-left px-6 py-4 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors flex items-center justify-between">
              <span class="flex items-center">
                <Icon name="mdi:account-edit" class="mr-3 text-xl text-primary-600" />
                Modifier mon profil
              </span>
              <Icon name="mdi:chevron-right" class="text-primary-400" />
            </button>
            <button class="w-full text-left px-6 py-4 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors flex items-center justify-between">
              <span class="flex items-center">
                <Icon name="mdi:lock-reset" class="mr-3 text-xl text-primary-600" />
                Changer mon mot de passe
              </span>
              <Icon name="mdi:chevron-right" class="text-primary-400" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </NuxtLayout>
</template>

<script setup>
const authStore = useAuthStore()

definePageMeta({
  middleware: 'auth'
})

const statusText = computed(() => {
  const status = authStore.user?.status
  return status === 'active' ? 'Actif' : status === 'pending' ? 'En attente' : 'Suspendu'
})

const statusClass = computed(() => {
  const status = authStore.user?.status
  return `inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${
    status === 'active' ? 'bg-green-100 text-green-800' :
    status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
    'bg-red-100 text-red-800'
  }`
})
</script>
