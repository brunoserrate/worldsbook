import Vue from 'vue'
import Vuex from 'vuex'
import dialog from "./modules/dialog";

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    dialog_historia: false,
    loading: false
  },
  mutations: {
    SET_DIALOG_HISTORIA(state, payload) {
      state.dialog_historia = payload
    },
  },
  actions: {
    openDialogHistory({ commit }, payload) {
      commit('SET_DIALOG_HISTORIA', payload);
    },
  },
  modules: {
    dialog,
  },
});

export default store;