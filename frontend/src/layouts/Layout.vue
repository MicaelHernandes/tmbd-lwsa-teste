<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <AppHeader />

    <main class="flex-1 p-8 max-w-6xl mx-auto w-full">
      <RouterView />
    </main>

    <footer class="bg-gray-800 text-white text-center p-4 mt-auto">
      <p>&copy; 2025 Movies App. Todos os direitos reservados.</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue'
</script>

<style scoped>
</style>
