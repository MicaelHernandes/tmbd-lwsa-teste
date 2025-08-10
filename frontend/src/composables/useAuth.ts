import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import type { User, AuthResponse } from '@/types/Auth'
import { AxiosError } from 'axios'

const isAuthenticated = ref<boolean>(false)
const isLoading = ref<boolean>(false)
const user = ref<User | null>(null)

export const useAuth = () => {
  const router = useRouter()

  const isLoggedIn = computed(() => isAuthenticated.value && user.value !== null)

  const checkAuthentication = async (): Promise<boolean> => {
    const token = localStorage.getItem('token')

    if (!token) {
      isAuthenticated.value = false
      user.value = null
      return false
    }

    isLoading.value = true

    try {
      const response = await api.get<AuthResponse>('/auth/me')

      if (response.data?.user) {
        isAuthenticated.value = true
        user.value = response.data.user
        return true
      } else {
        throw new Error('Resposta inválida do servidor')
      }
    } catch (error) {
      console.error('Erro ao verificar autenticação:', error)

      localStorage.removeItem('token')
      isAuthenticated.value = false
      user.value = null

      if (error instanceof AxiosError && error.response?.status === 401) {
        await router.push('/login')
      }

      return false
    } finally {
      isLoading.value = false
    }
  }

  const login = async (
    email: string,
    password: string,
  ): Promise<{ success: boolean; message?: string }> => {
    isLoading.value = true

    try {
      const response = await api.post<AuthResponse>('/auth/login', {
        email,
        password,
      })

      if (response.data?.token && response.data?.user) {
        // Salvar token
        localStorage.setItem('token', response.data.token)

        // Atualizar estado
        isAuthenticated.value = true
        user.value = response.data.user

        return { success: true }
      } else {
        throw new Error('Resposta inválida do servidor')
      }
    } catch (error) {
      console.error('Erro no login:', error)

      let message = 'Erro interno do servidor'

      if (error instanceof AxiosError) {
        if (error.response?.status === 401) {
          message = 'Credenciais inválidas'
        } else if (error.response?.data?.message) {
          message = error.response.data.message
        }
      }

      return { success: false, message }
    } finally {
      isLoading.value = false
    }
  }

  const register = async (
    name: string,
    email: string,
    password: string,
    passwordConfirmation: string,
  ): Promise<{ success: boolean; message?: string }> => {
    isLoading.value = true

    try {
      const response = await api.post<AuthResponse>('/auth/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      })

      if (response.data?.token && response.data?.user) {
        // Salvar token
        localStorage.setItem('token', response.data.token)

        // Atualizar estado
        isAuthenticated.value = true
        user.value = response.data.user

        return { success: true }
      } else {
        throw new Error('Resposta inválida do servidor')
      }
    } catch (error) {
      console.error('Erro no registro:', error)

      let message = 'Erro interno do servidor'

      if (error instanceof AxiosError && error.response?.data) {
        const errorData = error.response.data

        if (errorData.errors) {
          // Laravel validation errors
          const firstError = Object.values(errorData.errors)[0] as string[]
          message = firstError[0]
        } else if (errorData.message) {
          message = errorData.message
        }
      }

      return { success: false, message }
    } finally {
      isLoading.value = false
    }
  }

  const logout = async (): Promise<void> => {
    try {
      await api.post('/auth/logout')
    } catch (error) {
      console.error('Erro ao fazer logout no servidor:', error)
    } finally {
      // Limpar dados locais independente do resultado
      localStorage.removeItem('token')
      isAuthenticated.value = false
      user.value = null
      await router.push('/login')
    }
  }

  return {
    isAuthenticated: computed(() => isAuthenticated.value),
    isLoading: computed(() => isLoading.value),
    user: computed(() => user.value),
    isLoggedIn,

    checkAuthentication,
    login,
    register,
    logout,
  }
}
