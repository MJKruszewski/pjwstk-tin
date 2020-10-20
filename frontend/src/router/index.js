import Vue from 'vue'
import Router from 'vue-router'
import LoginPage from "../components/LoginPage";
import SpaceshipPage from "../components/SpaceshipPage";
import MainPage from "../components/MainPage";
import CaptainPage from "../components/CaptainPage";
import CrewPage from "../components/CrewPage";
import NotFoundPage from "../components/NotFoundPage";

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'LoginPage',
      component: LoginPage
    },
    {
      path: '*',
      component: NotFoundPage
    },
    {
      path: '/space-ship',
      name: 'SpaceshipPage',
      component: SpaceshipPage,
      children: [
        {
          path: '/space-ship/summary',
          name: 'summary',
          component: MainPage
        },
        {
          path: '/space-ship/',
          name: 'summary',
          component: MainPage
        },
        {
          path: '/space-ship/captain-panel',
          name: 'captainPanel',
          component: CaptainPage
        },
        {
          path: '/space-ship/crew',
          name: 'crewList',
          component: CrewPage
        },
      ]
    },
  ]
})
