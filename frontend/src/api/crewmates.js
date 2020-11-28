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
