import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function getShipCrewmates(shipId) {
  return axios.get(API_HOST + '/api/v1/ships/' + shipId + '/crewmates', {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function getCrewmate(id) {
  return axios.get(API_HOST + '/api/v1/crewmates/' + id, {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export function isCaptain() {
  return localStore.state.user.roles.includes('captain');
}

export function isRolePresent(role) {
  if (Array.isArray(role)) {
    return localStore.state.user.roles.includes(...role);
  }

  return localStore.state.user.roles.includes(role);
}
