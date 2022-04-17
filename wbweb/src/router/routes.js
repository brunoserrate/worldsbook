
const routes = [
  {
    path: '/',
    component: () => import('layouts/Default.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue') },
      { path: 'redefinir-senha/:token', component: () => import('pages/ResetPassword.vue') },
      { path: 'iniciar_leitura', component: () => import('pages/iniciar_leitura.vue') },
      { path: 'livro/:livro_id', component: () => import('pages/livro.vue') },
    ]
  },
  {
    path: '/auth',
    component: () => import('layouts/Auth.vue'),
    children: [
      { path: 'login', component: () => import('pages/auth/login.vue') },
    ]
  },

  {
    path: '*',
    component: () => import('pages/Error404.vue')
  }
]

export default routes
