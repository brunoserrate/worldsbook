import { boot } from 'quasar/wrappers'
import axios from 'axios'
import { environment } from 'src/helpers/environment'
// import app from './api'

const api = axios.create({ baseURL: environment.host })

export default boot(({ Vue, router, app }) => {
  api.interceptors.request.use(config => {
    let token = ''
    const storedToken = sessionStorage.getItem('auth')
    if (storedToken) {
      const cleanedToken = storedToken.startsWith('__q_objt|') ? storedToken.split('|')[1] : storedToken
      const tokenObject = JSON.parse(cleanedToken)
      token = tokenObject.access_token
    }

    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`
    }
    return config
  }, error => {
    return Promise.reject(error)
  })

  api.interceptors.response.use(null, function (error) {
    if (error.response.status === 401) {
      localStorage.removeItem("auth")
      router.push('/login')
    }
  })

  Vue.prototype.$axios = axios
  Vue.prototype.$api = api
})

export { axios, api }