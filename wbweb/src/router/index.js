import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuelidate from 'vuelidate'
import axios from 'axios'

import routes from './routes'

if(process.env.DEV){
  Vue.prototype.$pathAPI = '/wbcore/api';
  Vue.prototype.$pathWeb = '/wbcore';
}
else{
  Vue.prototype.$pathAPI = 'wbcore/public/api'
  Vue.prototype.$pathWeb = '/wbcore/public';
}

// Busca o CSRF Token para autenticação
axios.get('/wbcore/sanctum').then((res)=> {
  // console.log(res);
})

// Filtros
Vue.filter('converterBoolean', (valor) => {
  if(valor == true){
    return "Sim"
  }

  if(valor == false) {
    return "Não"
  }

})
// Filtros

Vue.use(Vuelidate)

Vue.use(VueRouter)

/*
 * If not building with SSR mode, you can
 * directly export the Router instantiation;
 *
 * The function below can be async too; either use
 * async/await or return a Promise which resolves
 * with the Router instance.
 */

export default function (/* { store, ssrContext } */) {
  const Router = new VueRouter({
    scrollBehavior: () => ({ x: 0, y: 0 }),
    routes,

    // Leave these as they are and change in quasar.conf.js instead!
    // quasar.conf.js -> build -> vueRouterMode
    // quasar.conf.js -> build -> publicPath
    mode: process.env.VUE_ROUTER_MODE,
    base: process.env.VUE_ROUTER_BASE
  })

  // Verificação de rotas
  // Router.beforeEach((to, from, next) => {
  //   if (to.matched.some(record => record.meta.requiresAuth)) {

  //     if(sessionStorage.getItem('autenticado')){
  //       next()
  //     }
  //     else
  //     {
  //       //next({ path: '/auth', query: { redirect: to.fullPath }});
  //       next({path:'/auth'})
  //     }
  //   }
  //   else {
  //     next();
  //   }
  // });

  return Router
}
