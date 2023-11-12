import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

import AuthLayout from '../layouts/AuthLayout.vue'
import AppAdmLayout from '../layouts/AppAdmLayout.vue'
import AppUserLayout from '../layouts/AppUserLayout.vue'

import RouteViewComponent from '../layouts/RouterBypass.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/:catchAll(.*)',
    redirect: { name: 'login' },
  },
  {
    name: 'admin',
    path: '/admin',
    component: AppAdmLayout,
    children: [
      {
        name: 'home-admin',
        path: 'home-admin',
        component: RouteViewComponent,
        children: [
          {
            name: 'page-1',
            path: 'page-1',
            component: () => import('../pages/admin/HomeAdmin.vue'),
          },
        ],
      },
      {
        name: 'calendar',
        path: 'calendar',
        component: () => import('../pages/admin/CalendarView.vue'),
      },
    ],
  },
  {
    path: '/user',
    component: AppUserLayout,
    children: [
      {
        name: 'home-user',
        path: 'home-user',
        component: () => import('../pages/user/HomeUser.vue'),
      },
    ],
  },
  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        name: 'login',
        path: 'login',
        component: () => import('../pages/auth/login/Login.vue'),
      },
      {
        name: 'signup',
        path: 'signup',
        component: () => import('../pages/auth/signup/Signup.vue'),
      },
      {
        name: 'recover-password',
        path: 'recover-password',
        component: () => import('../pages/auth/recover-password/RecoverPassword.vue'),
      },
      {
        name: 'landing-page',
        path: 'landing-page',
        component: () => import('../pages/landing-page/LandingPage.vue'),
      },
      {
        path: '',
        redirect: { name: 'login' },
      },
    ],
  },
]

const router = createRouter({
  // history: createWebHistory(import.meta.env.BASE_URL),
  history: createWebHistory('/rosemeri_beauty/frontend/dist/'),

  // mode: process.env.VUE_APP_ROUTER_MODE_HISTORY === 'true' ? 'history' : 'hash',
  routes,
})

export default router
