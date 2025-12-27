<template>
  <div class="min-h-screen bg-cream-100 font-body">
    <Header />
    <!-- <GuestBanner /> -->
    <main class="relative">
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

<style>
/* Smooth scroll global */
html {
  scroll-behavior: smooth;
}

/* Typographie globale */
body {
  font-family: 'Inter', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Titres avec Playfair Display */
h1, h2, h3, h4, h5, h6 {
  font-family: 'Playfair Display', serif;
}

/* Animations globales */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out;
}
</style>