<template>
  <div class="min-h-screen">
    <Header />
    <GuestBanner />
    <main>
      <slot />
    </main>
    <Footer />
  </div>
</template>

<script setup>
const authStore = useAuthStore()

onMounted(() => {
  authStore.initAuth()
})
</script>
