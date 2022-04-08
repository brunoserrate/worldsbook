import Vue from 'vue'
import axios from 'axios'

axios.defaults.withCredentials = true;

export default ({ Vue }) => {
  Vue.prototype.$axios = axios
}