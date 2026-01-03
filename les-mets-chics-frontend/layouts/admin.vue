<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <AdminSidebar />
    
    <!-- Main Content -->
    <div class="lg:pl-64">
      <!-- Top Bar -->
      <AdminTopBar />
      
      <!-- Page Content -->
      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
// Vérification des permissions admin au niveau layout
const { user, isAdmin } = useAuth()

if (!isAdmin.value) {
  throw createError({
    statusCode: 403,
    statusMessage: 'Accès refusé'
  })
}
</script>