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
import RegisterPage from "../components/auth/RegisterPage";
import {localStore} from '../main'
import {isRolePresent} from "../api/crewmates";
import TaskPage from "../components/tasks/TaskPage";
import {getMe} from "../api/login";

Vue.use(Router);

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'LoginPage',
      component: LoginPage,
      meta: {
        requiresAuth: false,
        roles: []
      }
    },    {
      path: '/register',
      name: 'registerPage',
      component: RegisterPage,
      meta: {
        requiresAuth: false,
        roles: []
      }
    },
    {
      path: '*',
      component: NotFoundPage,
      meta: {
        requiresAuth: false,
        roles: []
      }
    },
    {
      path: '/no-permission',
      component: NoPermissionPage,
      meta: {
        requiresAuth: false,
        roles: []
      }
    },
    {
      path: '/space-ship',
      name: 'SpaceshipPage',
      component: SpaceshipPage,
      meta: {
        requiresAuth: true,
        roles: []
      },
      children: [
        {
          path: '/space-ship/summary',
          name: 'summary',
          component: MainPage,
          meta: {
            sideMenuName: 'summary',
            requiresAuth: true,
            roles: []
          }
        },
        {
          path: '/space-ship/captain-panel',
          name: 'captainPanel',
          component: CaptainPage,
          meta: {
            sideMenuName: 'captainPanel',
            requiresAuth: true,
            roles: [
              'captain',
              'secretary',
            ]
          }

        },
        {
          path: '/space-ship/crew',
          name: 'crewList',
          component: CrewPage,
          meta: {
            sideMenuName: 'crewList',
            requiresAuth: true,
            roles: [
            ]
          }
        },
        {
          path: '/space-ship/tasks',
          name: 'tasks',
          component: TaskPage,
          meta: {
            sideMenuName: 'tasks',
            requiresAuth: true,
            roles: [
            ]
          }
        },
        {
          path: '/space-ship/crew/details/:id',
          name: 'crewDetails',
          component: CrewDetailsPage,
          meta: {
            sideMenuName: 'crewList',
            requiresAuth: true,
            roles: []
          }
        },
        {
          path: '/space-ship/crew/:id/edit',
          name: 'crewEdit',
          component: EditCrewPage,
          meta: {
            sideMenuName: 'crewList',
            requiresAuth: true,
            roles: [
              'captain',
              'secretary',
            ]
          }
        },
        {
          path: '/space-ship/crew/add',
          name: 'crewAdd',
          component: AddCrewPage,
          meta: {
            sideMenuName: 'crewList',
            requiresAuth: true,
            roles: [
              'captain'
            ]
          }
        },
        {
          path: '/space-ship/med-bay',
          name: 'medBay',
          component: ClinicPage,
          meta: {
            sideMenuName: 'medBay',
            requiresAuth: true,
            roles: [
              'captain',
              'medic',
              'nurse',
            ]
          }

        },
        {
          path: '/space-ship/engineers-room',
          name: 'engineersRoom',
          component: EngineersPage,
          meta: {
            sideMenuName: 'engineersRoom',
            requiresAuth: true,
            roles: [
              'captain',
              'engineer',
              'programmer',
              'calibrator',
            ]
          }

        },
        {
          path: '/space-ship/navigation-room',
          name: 'navigationRoom',
          component: NavigationPage,
          meta: {
            sideMenuName: 'navigationRoom',
            requiresAuth: true,
            roles: [
              'captain',
              'pilot',
              'navigator',
            ]
          }
        },
      ]
    },
  ]
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {

    await getMe().then((res) => {
      localStore.dispatch('setCrewmate', res.data.data)
    }).catch((res) => {
      next({
        path: '/',
      })
    });

    if (
      localStore.state.user.id === null
      || localStore.state.session.expireAt === null
      || localStore.state.session.token === null
      || new Date(localStore.state.session.expireAt) < new Date()
    ) {
      next({
        path: '/',
      })
    } else {
      if (to.meta.roles.length > 0) {
        if (isRolePresent(to.meta.roles)) {
          next()
        } else {
          next({
            name: 'summary'
          })
        }
      } else {
        next()
      }
    }
  } else {
    next()
  }
});


export default router;
