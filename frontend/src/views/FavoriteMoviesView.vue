<template>
  <div class="min-h-screen bg-gray-50">
    <div class="w-full px-6 lg:px-12 py-12">
      <div class="text-center mb-16">
        <h1 class="text-4xl font-medium text-gray-900 mb-2">Filmes Favoritos</h1>
        <p class="text-gray-600">Sua coleção de filmes favoritos</p>
      </div>

      <div class="max-w-md mx-auto mb-16">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-1">
          <select
            v-model="selectedGenre"
            @change="filterByGenre"
            class="w-full px-4 py-3 bg-transparent text-gray-900 focus:outline-none appearance-none cursor-pointer"
          >
            <option value="">Todos os Gêneros</option>
            <option v-for="genre in availableGenres" :key="genre.id" :value="genre.id">
              {{ genre.name }}
            </option>
          </select>
        </div>
      </div>

      <div v-if="errorMessage" class="max-w-2xl mx-auto mb-12">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center">
          {{ errorMessage }}
        </div>
      </div>

      <div v-if="isLoading" class="text-center py-24">
        <div
          class="w-8 h-8 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin mx-auto"
        ></div>
        <p class="mt-4 text-gray-600">Carregando filmes favoritos...</p>
      </div>

      <div v-if="!isLoading && filteredMovies.length > 0" class="text-center mb-12">
        <p class="text-gray-600">
          Mostrando {{ filteredMovies.length }} filmes favoritos
          <span v-if="selectedGenre" class="font-medium">
            em {{ availableGenres.find((g) => g.id === selectedGenre)?.name }}
          </span>
        </p>
      </div>

      <div v-if="!isLoading && filteredMovies.length > 0" class="mb-16">
        <div
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6"
        >
          <div
            v-for="movie in filteredMovies"
            :key="movie.id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow relative"
          >
            <button
              @click="removeFromFavorites(movie.id)"
              class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-md transition-colors z-10"
              title="Remover dos favoritos"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                ></path>
              </svg>
            </button>

            <div class="aspect-[2/3] bg-gray-100">
              <img
                v-if="movie.poster_path"
                :src="movie.poster_path"
                :alt="movie.title"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M7 4V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2h4a1 1 0 0 1 0 2h-1v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6H3a1 1 0 0 1 0-2h4zM6 6v14h12V6H6zm3 3a1 1 0 0 1 2 0v8a1 1 0 0 1-2 0V9zm4 0a1 1 0 0 1 2 0v8a1 1 0 0 1-2 0V9z"
                  ></path>
                </svg>
              </div>
            </div>

            <div class="p-4">
              <h3 class="font-medium text-gray-900 mb-2 line-clamp-2">{{ movie.title }}</h3>
              <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>{{ new Date(movie.release_date).getFullYear() }}</span>
                <div class="flex items-center">
                  <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    ></path>
                  </svg>
                  <span>{{ movie.vote_average.toFixed(1) }}</span>
                </div>
              </div>
              <div class="flex flex-wrap gap-1">
                <span
                  v-for="genre in movie.genres.slice(0, 2)"
                  :key="genre.id"
                  class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full"
                >
                  {{ genre.name }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="!isLoading && filteredMovies.length === 0 && !errorMessage"
        class="text-center py-24"
      >
        <div class="text-6xl mb-6 opacity-30">��️</div>
        <h3 class="text-xl font-medium text-gray-900 mb-2">
          {{ selectedGenre ? 'Nenhum filme favorito neste gênero' : 'Nenhum filme favorito ainda' }}
        </h3>
        <p class="text-gray-600">
          {{
            selectedGenre
              ? 'Tente selecionar um gênero diferente'
              : 'Comece adicionando filmes aos seus favoritos'
          }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import api from '@/services/api.ts'
import type { Movie, Genre } from '@/types/Movie'

const movies = ref<Movie[]>([])
const errorMessage = ref<string>('')
const isLoading = ref<boolean>(false)
const selectedGenre = ref<number | string>('')

const availableGenres = computed<Genre[]>(() => {
  const genresMap = new Map<number, Genre>()
  movies.value.forEach((movie) => {
    movie.genres.forEach((genre) => {
      genresMap.set(genre.id, genre)
    })
  })
  return Array.from(genresMap.values()).sort((a, b) => a.name.localeCompare(b.name))
})

const filteredMovies = computed<Movie[]>(() => {
  if (!selectedGenre.value) return movies.value
  return movies.value.filter((movie) =>
    movie.genres.some((genre) => genre.id === selectedGenre.value),
  )
})

const fetchFavoriteMovies = async () => {
  errorMessage.value = ''
  isLoading.value = true
  try {
    const response = await api.get<Movie[]>('/favorite_movies')
    movies.value = response.data
  } catch (error: any) {
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Erro ao buscar filmes favoritos.'
    }
  } finally {
    isLoading.value = false
  }
}

const removeFromFavorites = async (movieId: number) => {
  try {
    await api.delete(`/favorite_movies`, {
      params: {
        movie_id: movieId,
      },
    })
    movies.value = movies.value.filter((movie) => movie.id !== movieId)
  } catch (error: any) {
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Erro ao remover filme dos favoritos.'
    }
  }
}

const filterByGenre = () => {}

onMounted(() => {
  document.title = 'Filmes Favoritos - Movie App'
  fetchFavoriteMovies()
})
</script>
