import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/Auth/Login.vue'
import Register from '@/components/Auth/Register.vue'
import Forgot from '@/components/Auth/Forgot.vue'
import Reset from '@/components/Auth/Reset.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Login
    },
    {
      path:'/register',
      name: 'register',
      component: Register
    },
    {
      path:'/forgot-password',
      name: 'forgot',
      component: Forgot
    },
    {
      path:'/reset-password/:email/:token',
      name: 'reset',
      component: Reset
    },
    
  ]
})

export default router
