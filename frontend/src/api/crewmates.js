import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function getShipCrewmates(shipId, page, itemsPerPage) {
  let query = '';

  if (typeof page !== 'undefined' && typeof itemsPerPage !== 'undefined') {
    query = '?page=' + page + '&itemsPerPage=' + itemsPerPage;
  }

  return axios.get(API_HOST + '/api/v1/ships/' + shipId + '/crewmates' + query, {
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

export async function putCrewmate(id, body) {
  return axios.put(API_HOST + '/api/v1/crewmates/' + id, body,  {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function postCrewmate(body) {
  return axios.post(API_HOST + '/api/v1/crewmates', body,  {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function deleteCrewmate(id) {
  return axios.delete(API_HOST + '/api/v1/crewmates/' + id,{
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
