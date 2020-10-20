// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import VueI18n from 'vue-i18n'
import localization from './localizaiton'
import Vuex from 'vuex'

Vue.use(Vuex);
Vue.use(VueI18n);
Vue.config.productionTip = false;

// Create VueI18n instance with options
const i18n = new VueI18n({
  locale: 'pl',
  localization,
});

const store = new Vuex.Store({
  state: {
    user: {
      name: "Guest",
      lastName: "Guest",
      role: 'captain',
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
  }
});



/* eslint-disable no-new */
new Vue({
  el: '#app',
  i18n,
  router,
  store,
  components: { App },
  template: '<App/>'
});
