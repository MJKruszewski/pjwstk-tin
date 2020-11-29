import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function getAllStations() {
  return axios.get(API_HOST + '/api/v1/stations', {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function postCrewmateStation(crewmate, stationId, from) {
  return axios.post(API_HOST + '/api/v1/crewmates/' + crewmate + '/stations', {
    stationId: stationId,
    from: from,
  },{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}
