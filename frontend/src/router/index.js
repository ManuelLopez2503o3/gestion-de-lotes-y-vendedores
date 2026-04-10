import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  { 
    path: '/', 
    name: 'login',
    // Vista principal de acceso
    component: () => import('../views/Login.vue') 
  },
  { 
    path: '/register', 
    name: 'register',
    // Esta es la ruta que reactivamos para que no te dé error 404
    component: () => import('../views/Register.vue') 
  },
  { 
    path: '/lotes', 
    name: 'lotes',
    component: () => import('../views/Lotes.vue'),
    // Segurid2ad: Si intentan entrar por URL sin loguearse, los rebota al login
    beforeEnter: (to, from, next) => {
      const token = localStorage.getItem('token');
      if (!token) {
        next('/');
      } else {
        next();
      }
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;