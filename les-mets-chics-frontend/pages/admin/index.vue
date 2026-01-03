<template>
  <div>
    <AdminHeader title="Dashboard" />
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <AdminStatsCard 
        title="Utilisateurs" 
        :value="stats.users" 
        icon="lucide:users" 
        color="blue" 
      />
      <AdminStatsCard 
        title="Recettes" 
        :value="stats.recipes" 
        icon="lucide:chef-hat" 
        color="green" 
      />
      <AdminStatsCard 
        title="Commentaires" 
        :value="stats.comments" 
        icon="lucide:message-circle" 
        color="yellow" 
      />
      <AdminStatsCard 
        title="En attente" 
        :value="stats.pending" 
        icon="lucide:clock" 
        color="red" 
      />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <AdminRecentUsers />
      <AdminRecentComments />
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { getAdminStats } = useAdminApi()
const stats = await getAdminStats()

useHead({
  title: 'Dashboard Admin - Les Mets Chics'
})
</script>