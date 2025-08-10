<template>
  <div
    class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200 group cursor-pointer"
  >
    <!-- Poster -->
    <div class="relative aspect-[2/3] overflow-hidden bg-gray-100">
      <img
        v-if="movie.poster_path"
        :src="movie.poster_path"
        :alt="movie.title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
      />
      <div v-else class="w-full h-full flex items-center justify-center">
        <div class="text-center text-gray-400">
          <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7a1 1 0 01-1-1V5a1 1 0 011-1h4zM9 4h6v10l-3-3-3 3V4z"
            />
          </svg>
          <span class="text-sm">No Image</span>
        </div>
      </div>

      <!-- Rating Badge -->
      <div
        class="absolute top-3 right-3 bg-white/90 text-gray-900 px-2 py-1 rounded-md flex items-center gap-1 shadow-sm"
      >
        <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
          />
        </svg>
        <span class="text-xs font-medium">{{
          movie.vote_average ? movie.vote_average.toFixed(1) : 0.0
        }}</span>
      </div>

      <button
        v-if="isLoggedIn && !isAddingToFavorites"
        @click.stop="addToFavorites"
        class="absolute top-3 left-3 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-md transition-colors"
        title="Adicionar aos favoritos"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path
            fill-rule="evenodd"
            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
            clip-rule="evenodd"
          />
        </svg>
      </button>

      <button
        v-if="isLoggedIn && isAddingToFavorites"
        disabled
        class="absolute top-3 left-3 bg-gray-400 text-white p-2 rounded-full shadow-md cursor-not-allowed"
        title="Adicionando..."
      >
        <div
          class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
        ></div>
      </button>
    </div>

    <!-- Content -->
    <div class="p-4">
      <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">
        {{ movie.title }}
      </h3>

      <div class="flex items-center gap-2 mb-3 text-sm text-gray-500">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
          />
        </svg>
        <span>{{ formatDate(movie.release_date) }}</span>
      </div>

      <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-3">
        {{ movie.overview || 'No description available.' }}
      </p>

      <!-- Genres -->
      <div v-if="movie.genres && Object.keys(movie.genres).length > 0" class="flex flex-wrap gap-1">
        <span
          v-for="(genre, id) in movie.genres"
          :key="id"
          class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md"
        >
          {{ genre }}
        </span>
      </div>

      <div v-if="errorMessage" class="mt-2 text-xs text-red-600 bg-red-50 px-2 py-1 rounded">
        {{ errorMessage }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { Movie } from '@/types/Movie'
import { useAuth } from '@/composables/useAuth'
import api from '@/services/api'

const props = defineProps<{ movie: Movie }>()

const { isLoggedIn } = useAuth()
const isAddingToFavorites = ref<boolean>(false)
const errorMessage = ref<string>('')

const addToFavorites = async () => {
  if (!isLoggedIn.value) return

  isAddingToFavorites.value = true
  errorMessage.value = ''

  try {
    await api.post('/favorite_movies', null, {
      params: { movie_id: props.movie.id },
    })
    alert('Filme adicionado aos favoritos com sucesso!')
  } catch (error: any) {
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else if (error.response?.data?.errors) {
      const errors = Object.values(error.response.data.errors).flat()
      errorMessage.value = errors[0] as string
    } else {
      errorMessage.value = 'Erro ao adicionar filme aos favoritos.'
    }

    setTimeout(() => {
      errorMessage.value = ''
    }, 5000)
  } finally {
    isAddingToFavorites.value = false
  }
}

function formatDate(dateString: string) {
  if (!dateString) return 'Data indisponível'
  const date = new Date(dateString)
  return date.toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'short',
  })
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
