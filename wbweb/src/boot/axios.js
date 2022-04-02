import Vue from 'vue'
import axios from 'axios'
Vue.prototype.$axios = axios


const api = axios.create({ 
  baseURL: 'http://localhost:1337/api/' 
})
export default ({ Vue }) => {
  Vue.prototype.$axios = api
}
export { api }