<template>
  <header class="bg-slate-800 text-white py-4 shadow-lg">
    <nav class="max-w-6xl mx-auto px-8 flex justify-between items-center">
      <div class="nav-brand">
        <RouterLink
          to="/"
          class="text-2xl font-bold text-white hover:text-gray-200 transition-colors"
        >
          Movies App
        </RouterLink>
      </div>
      <div v-if="!isAuthenticated && !isLoading" class="flex gap-8">
        <RouterLink
          to="/login"
          class="text-white hover:bg-slate-700 px-4 py-2 rounded transition-colors"
        >
          Login
        </RouterLink>
        <RouterLink
          to="/register"
          class="text-white hover:bg-slate-700 px-4 py-2 rounded transition-colors"
        >
          Registro
        </RouterLink>
      </div>
      <div v-else-if="isAuthenticated && user" class="flex gap-4 items-center">
        <RouterLink
          to="/favorite"
          class="text-white hover:bg-slate-700 px-4 py-2 rounded transition-colors flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path
              fill-rule="evenodd"
              d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
              clip-rule="evenodd"
            />
          </svg>
          Favoritos
        </RouterLink>
        <span class="text-gray-300 mr-4">Olá, {{ user.name }}!</span>
        <button
          @click="logout"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors"
        >
          Sair
        </button>
      </div>
      <div v-else-if="isLoading" class="flex items-center">
        <span class="text-gray-300">Carregando...</span>
      </div>
    </nav>
  </header>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuth } from '@/composables/useAuth'

const { isAuthenticated, isLoading, user, checkAuthentication, logout } = useAuth()

onMounted(async () => {
  await checkAuthentication()
})
</script>

<style scoped>
/* Removido CSS customizado, usando apenas Tailwind */
</style>
