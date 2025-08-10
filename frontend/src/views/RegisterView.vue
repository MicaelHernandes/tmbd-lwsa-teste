<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h2 class="text-3xl font-medium text-gray-900 mb-2">Crie sua conta</h2>
        <p class="text-gray-600">Junte-se a nós hoje! Por favor, preencha seus dados.</p>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <form @submit.prevent="handleRegister" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
              Nome completo
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
              :class="{ 'border-red-300': errors.name }"
              placeholder="Digite seu nome completo"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-600">
              {{ errors.name }}
            </p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
              Endereço de email
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
              :class="{ 'border-red-300': errors.email }"
              placeholder="Digite seu email"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">
              {{ errors.email }}
            </p>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
              Senha
            </label>
            <div class="relative">
              <input
                id="password"
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                :class="{ 'border-red-300': errors.password }"
                placeholder="Crie uma senha"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
              >
                <svg
                  v-if="!showPassword"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                  />
                </svg>
              </button>
            </div>
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">
              {{ errors.password }}
            </p>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
              Confirmar senha
            </label>
            <div class="relative">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                :type="showPasswordConfirmation ? 'text' : 'password'"
                required
                class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                :class="{ 'border-red-300': errors.password_confirmation || passwordMismatch }"
                placeholder="Confirme sua senha"
              />
              <button
                type="button"
                @click="showPasswordConfirmation = !showPasswordConfirmation"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
              >
                <svg
                  v-if="!showPasswordConfirmation"
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                  />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                  />
                </svg>
              </button>
            </div>
            <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">
              {{ errors.password_confirmation }}
            </p>
            <p v-else-if="passwordMismatch" class="mt-1 text-sm text-red-600">
              As senhas não coincidem
            </p>
          </div>

          <div
            v-if="errorMessage"
            class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm"
          >
            {{ errorMessage }}
          </div>

          <button
            type="submit"
            :disabled="isLoading || passwordMismatch"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            {{ isLoading ? 'Criando conta...' : 'Criar conta' }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <p class="text-sm text-gray-600">
            Já tem uma conta?
            <router-link to="/login" class="font-medium text-gray-900 hover:text-gray-700">
              Entrar
            </router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, onMounted, computed } from 'vue'
import api from '@/services/api.ts'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errorMessage = ref('')
const isLoading = ref(false)
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

const passwordMismatch = computed(() => {
  return form.password && form.password_confirmation && form.password !== form.password_confirmation
})

const handleRegister = async () => {
  Object.keys(errors).forEach((key) => {
    errors[key as keyof typeof errors] = ''
  })
  errorMessage.value = ''

  if (!form.name) {
    errors.name = 'Nome é obrigatório'
    return
  }

  if (!form.email) {
    errors.email = 'Email é obrigatório'
    return
  }

  if (!form.password) {
    errors.password = 'Senha é obrigatória'
    return
  }

  if (!form.password_confirmation) {
    errors.password_confirmation = 'Confirmação de senha é obrigatória'
    return
  }

  if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'As senhas não coincidem'
    return
  }

  isLoading.value = true

  try {
    const response = await api.post('/auth/register', form)
    if (response.status === 201) {
      alert('Usuário cadastrado com sucesso! Redirecionando para login...')
      router.push('/login')
    }
  } catch (error: any) {
    if (error.response?.status === 422 && error.response?.data?.errors) {
      const validationErrors = error.response.data.errors
      Object.keys(validationErrors).forEach((field) => {
        if (errors.hasOwnProperty(field)) {
          errors[field as keyof typeof errors] = Array.isArray(validationErrors[field])
            ? validationErrors[field][0]
            : validationErrors[field]
        }
      })
    } else if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else {
      errorMessage.value = 'Falha no registro. Tente novamente.'
    }
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  document.title = 'Cadastro - Movie App'
})
</script>
