// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import VueI18n from 'vue-i18n'
import localization from './localizaiton'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import * as Cookies from 'js-cookie'
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
Vue.use(Vuex);
Vue.use(VueI18n);
Vue.config.productionTip = false;

// Create VueI18n instance with options
const i18n = new VueI18n({
  locale: 'pl',
  messages: localization,
});

export const localStore = new Vuex.Store({
  state: {
    session: {
      token: null,
      expireAt: null,
    },
    user: {
      id: null,
      name: "Guest",
      lastName: "Guest",
      role: 'captain',
      ship: {
        id:	null,
        name:	null,
        hull:	null,
        engines:	null,
      },
      stats: {
        strength: 1,
        dexterity: 3,
        intelligence: 6,
        experience: 9,
        condition: 2,
      }
    },
  },
  mutations: {
    setCrewmate (state, payload) {
      state.user = payload;
    },
    setSession (state, payload) {
      state.session = payload;
    }
  },
  actions: {
    setCrewmate (context, crewmate) {
      context.commit('setCrewmate', crewmate)
    },
    setSession (context, session) {
      context.commit('setSession', session)
    }
  },
  plugins: [createPersistedState()],
});

let store = localStore;


/* eslint-disable no-new */
new Vue({
  el: '#app',
  i18n,
  router,
  store,
  components: { App },
  template: '<App/>'
});
