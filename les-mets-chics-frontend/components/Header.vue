<template>
  <!-- Header conditionnel selon l'état d'authentification -->
  <GuestHeader v-if="!isAuthenticated" />
  <AuthHeader v-else />
</template>

<script setup>
const { isAuthenticated } = useAuth()
</script>