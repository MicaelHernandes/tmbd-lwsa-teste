import { createRouter, createWebHistory } from 'vue-router'
import Layout from '@/layouts/Layout.vue'
import MoviesView from '@/views/MoviesView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import FavoriteMoviesView from '@/views/FavoriteMoviesView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: Layout,
      children: [
        {
          path: '',
          name: 'films',
          component: MoviesView,
        },
        {
          path: '/favorite',
          name: 'favorite-movies',
          component: FavoriteMoviesView,
        },
      ],
    },
    {
      path: '/auth',
      children: [
        {
          path: '/login',
          name: 'login',
          component: LoginView,
        },
        {
          path: '/register',
          name: 'register',
          component: RegisterView,
        },
      ],
    },
  ],
})

export default router
