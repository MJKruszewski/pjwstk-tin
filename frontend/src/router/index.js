import Vue from 'vue'
import Router from 'vue-router'
import SpaceshipPage from "../components/SpaceshipPage";
import MainPage from "../components/MainPage";
import CaptainPage from "../components/CaptainPage";
import ClinicPage from "../components/ClinicPage";
import NavigationPage from "../components/NavigationPage";
import EngineersPage from "../components/EngineersPage";
import LoginPage from "../components/auth/LoginPage";
import AddCrewPage from "../components/crew/AddCrewPage";
import EditCrewPage from "../components/crew/EditCrewPage";
import CrewPage from "../components/crew/CrewPage";
import NoPermissionPage from "../components/technical/NoPermissionPage";
import NotFoundPage from "../components/technical/NotFoundPage";
import CrewDetailsPage from "../components/crew/CrewDetailsPage";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'LoginPage',
      component: LoginPage,
      meta: {
      }
    },
    {
      path: '*',
      component: NotFoundPage,
      meta: {
      }
    },
    {
      path: '/no-permission',
      component: NoPermissionPage,

    },
    {
      path: '/space-ship',
      name: 'SpaceshipPage',
      component: SpaceshipPage,
      children: [
        {
          path: '/space-ship/summary',
          name: 'summary',
          component: MainPage,
          meta: {
            sideMenuName: 'summary',
          }
        },
        {
          path: '/space-ship/captain-panel',
          name: 'captainPanel',
          component: CaptainPage,
          meta: {
            sideMenuName: 'captainPanel',
          }

        },
        {
          path: '/space-ship/crew',
          name: 'crewList',
          component: CrewPage,
          meta: {
            sideMenuName: 'crewList',
          }

        },
        {
          path: '/space-ship/crew/details/:id',
          name: 'crewDetails',
          component: CrewDetailsPage,
          meta: {
            sideMenuName: 'crewList',
          }
        },
        {
          path: '/space-ship/crew/:id/edit',
          name: 'crewEdit',
          component: EditCrewPage,
          meta: {
            sideMenuName: 'crewList',
          }
        },
        {
          path: '/space-ship/crew/add',
          name: 'crewAdd',
          component: AddCrewPage,
          meta: {
            sideMenuName: 'crewList',
          }
        },
        {
          path: '/space-ship/med-bay',
          name: 'medBay',
          component: ClinicPage,
          meta: {
            sideMenuName: 'medBay',
          }

        },
        {
          path: '/space-ship/engineers-room',
          name: 'engineersRoom',
          component: EngineersPage,
          meta: {
            sideMenuName: 'engineersRoom',
          }

        },
        {
          path: '/space-ship/navigation-room',
          name: 'navigationRoom',
          component: NavigationPage,
          meta: {
            sideMenuName: 'navigationRoom',
          }

        },
      ]
    },
  ]
})
