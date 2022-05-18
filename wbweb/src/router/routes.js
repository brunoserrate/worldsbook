
const routes = [
  {
    path: '/',
    component: () => import('layouts/Default.vue'),
    children: [
      { path: '', component: () => import('pages/Index.vue'), meta: { public: true } },
      { path: 'redefinir-senha/:token', component: () => import('pages/ResetPassword.vue'), meta: { public: true } },
      // tela inicial para usuário cadastrado
      { path: 'iniciar_leitura', component: () => import('pages/iniciar_leitura.vue'), meta: { public: true } },
      { path: 'historia/:pesquisa', component: () => import('pages/pesquisa_livros.vue'), meta: { public: true } },
      { path: 'perfil/:perfil_id?', component: () => import('pages/perfil.vue'), meta: { public: true } },
      { path: 'editar_perfil/:usuario_id', component: () => import('pages/editar_perfil.vue'), meta: { public: false } },
      { path: 'criar_historia', component: () => import('pages/criar_historia.vue'), meta: { public: false } },
      { path: 'categorias', component: () => import('pages/categorias.vue'), meta: { public: true } },
      { path: 'livro_categorias/:categoria_id/:categoria_nome', component: () => import('pages/livro_categorias.vue'), meta: { public: true } },
      { path: 'criar_historia/:historia_id', component: () => import('pages/criar_capitulo.vue'), meta: { public: false } },
      { path: 'livro/:livro_id', component: () => import('pages/livro.vue'), meta: { public: true } },
      { path: 'editar_livro/:livro_id', component: () => import('pages/editar_historia.vue'), meta: { public: false } },
      { path: 'livro/capitulo/:capitulo_id', component: () => import('pages/capitulo.vue'), meta: { public: true } },
      { path: 'editar_capitulo/:capitulo_id', component: () => import('pages/editar_capitulo.vue'), meta: { public: false } },
      { path: 'sobre_empresa', component: () => import('pages/sobre_empresa.vue'), meta: { public: true } },
      { path: 'contato', component: () => import('pages/contato.vue'), meta: { public: true } },
      { path: 'sobre_empresa', component: () => import('pages/sobre_empresa.vue'), meta: { public: true } },
      { path: 'faq', component: () => import('pages/faq.vue'), meta: { public: true } },
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
