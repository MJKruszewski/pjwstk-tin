import Vue from 'vue'
import Router from 'vue-router'
import LoginPage from "../components/LoginPage";
import SpaceshipPage from "../components/SpaceshipPage";
import MainPage from "../components/MainPage";
import CaptainPage from "../components/CaptainPage";
import CrewPage from "../components/CrewPage";
import NotFoundPage from "../components/NotFoundPage";
import ClinicPage from "../components/ClinicPage";
import NavigationPage from "../components/NavigationPage";
import EngineersPage from "../components/EngineersPage";
import CrewDetailsPage from "../components/CrewDetailsPage";

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
        {
          path: '/space-ship/crew/:id',
          name: 'crewDetails',
          component: CrewDetailsPage
        },
        {
          path: '/space-ship/med-bay',
          name: 'medBay',
          component: ClinicPage
        },
        {
          path: '/space-ship/engineers-room',
          name: 'engineersRoom',
          component: EngineersPage
        },
        {
          path: '/space-ship/navigation-room',
          name: 'navigationRoom',
          component: NavigationPage
        },
      ]
    },
  ]
})
