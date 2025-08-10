<template>
  <div class="min-h-screen bg-gray-50">
    <div class="w-full px-6 lg:px-12 py-12">
      <!-- Header -->
      <div class="text-center mb-16">
        <h1 class="text-4xl font-medium text-gray-900 mb-2">Movie Catalog</h1>
        <p class="text-gray-600">Discover your next favorite film</p>
      </div>

      <!-- Search Section -->
      <div class="max-w-2xl mx-auto mb-16">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-1">
          <div class="flex gap-2">
            <input
              v-model="search"
              placeholder="Search movies..."
              @keyup.enter="searchMovies(1)"
              class="flex-1 px-4 py-3 bg-transparent text-gray-900 placeholder-gray-500 focus:outline-none"
            />

            <button
              @click="searchMovies(1)"
              :disabled="isLoading"
              class="px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              {{ isLoading ? 'Searching...' : 'Search' }}
            </button>

            <button
              v-if="search"
              @click="clearSearch"
              class="px-4 py-3 text-gray-500 hover:text-gray-700 transition-colors"
            >
              Clear
            </button>
          </div>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="max-w-2xl mx-auto mb-12">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center">
          {{ errorMessage }}
        </div>
      </div>

      <!-- Loading -->
      <div v-if="isLoading" class="text-center py-24">
        <div
          class="w-8 h-8 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin mx-auto"
        ></div>
        <p class="mt-4 text-gray-600">Loading movies...</p>
      </div>

      <!-- Results Info -->
      <div v-if="!isLoading && movies.length > 0" class="text-center mb-12">
        <p class="text-gray-600">
          Showing {{ movies.length }} of {{ totalResults }} results
          <span v-if="search" class="font-medium"> for "{{ search }}"</span>
        </p>
      </div>

      <!-- Movies Grid -->
      <div v-if="!isLoading && movies.length > 0" class="mb-16">
        <div
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6"
        >
          <MovieCard v-for="movie in movies" :key="movie.id" :movie="movie" />
        </div>
      </div>

      <!-- No Results -->
      <div v-if="!isLoading && movies.length === 0 && !errorMessage" class="text-center py-24">
        <div class="text-6xl mb-6 opacity-30">🎬</div>
        <h3 class="text-xl font-medium text-gray-900 mb-2">No movies found</h3>
        <p class="text-gray-600">Try searching with different terms</p>
      </div>

      <!-- Pagination -->
      <div v-if="!isLoading && totalPages > 1" class="flex justify-center items-center gap-2 pb-12">
        <button
          @click="goToPage(1)"
          :disabled="currentPage === 1"
          class="px-3 py-2 text-gray-700 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        >
          First
        </button>
        <button
          @click="goToPage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="px-3 py-2 text-gray-700 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        >
          Prev
        </button>

        <div class="flex gap-1">
          <template v-for="page in getVisiblePages()" :key="page">
            <button
              v-if="page !== '...'"
              @click="goToPage(page as number)"
              :class="[
                'px-3 py-2 rounded-md transition-colors',
                currentPage === page
                  ? 'bg-gray-900 text-white'
                  : 'text-gray-700 hover:text-gray-900 hover:bg-gray-100',
              ]"
            >
              {{ page }}
            </button>
            <span v-else class="px-3 py-2 text-gray-400">...</span>
          </template>
        </div>

        <button
          @click="goToPage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="px-3 py-2 text-gray-700 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        >
          Next
        </button>
        <button
          @click="goToPage(totalPages)"
          :disabled="currentPage === totalPages"
          class="px-3 py-2 text-gray-700 hover:text-gray-900 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"
        >
          Last
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/services/api.ts'
import type { Movie, MovieResponse } from '@/types/Movie'
import MovieCard from '@/components/MovieCard.vue'

const search = ref<string>('')
const movies = ref<Movie[]>([])
const errorMessage = ref<string>('')
const currentPage = ref<number>(1)
const totalPages = ref<number>(1)
const totalResults = ref<number>(0)
const isLoading = ref<boolean>(false)

const fetchMovies = async (page: number = 1) => {
  errorMessage.value = ''
  isLoading.value = true
  try {
    const response = await api.get<{ data: MovieResponse }>('/movies', {
      params: { page },
    })
    movies.value = response.data.data.results
    currentPage.value = response.data.data.page
    totalPages.value = response.data.data.total_pages
    totalResults.value = response.data.data.total_results
  } catch (error: any) {
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Error fetching movies.'
    }
  } finally {
    isLoading.value = false
  }
}

const searchMovies = async (page: number = 1) => {
  errorMessage.value = ''
  isLoading.value = true
  try {
    const response = await api.get<{ data: MovieResponse }>('/movies/search', {
      params: {
        query: search.value,
        page,
      },
    })
    movies.value = response.data.data.results
    currentPage.value = response.data.data.page
    totalPages.value = response.data.data.total_pages
    totalResults.value = response.data.data.total_results
  } catch (error: any) {
    if (error.response && error.response.data && error.response.data.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Error searching movies.'
    }
  } finally {
    isLoading.value = false
  }
}

const goToPage = (page: number) => {
  if (search.value) {
    searchMovies(page)
  } else {
    fetchMovies(page)
  }
}

const clearSearch = () => {
  search.value = ''
  fetchMovies(1)
}

const getVisiblePages = () => {
  const pages = []
  const current = currentPage.value
  const total = totalPages.value
  if (total <= 7) {
    for (let i = 1; i <= total; i++) {
      pages.push(i)
    }
  } else {
    pages.push(1)
    if (current > 4) {
      pages.push('...')
    }
    const start = Math.max(2, current - 2)
    const end = Math.min(total - 1, current + 2)
    for (let i = start; i <= end; i++) {
      pages.push(i)
    }
    if (current < total - 3) {
      pages.push('...')
    }
    pages.push(total)
  }
  return pages
}

onMounted(() => {
  document.title = 'Filmes - Movie App'
  fetchMovies()
})
</script>
