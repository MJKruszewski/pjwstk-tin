import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function getAllStations(page, itemsPerPage) {
  let query = '';

  if (typeof page !== 'undefined' && typeof itemsPerPage !== 'undefined') {
    query = '?page=' + page + '&itemsPerPage=' + itemsPerPage;
  }

  return axios.get(API_HOST + '/api/v1/stations' + query, {
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

export async function postStation(body) {
  return axios.post(API_HOST + '/api/v1/stations', body,{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function postResign(crewmateId, stationId) {
  return axios.post(API_HOST + '/api/v1/crewmates/' + crewmateId +'/stations/' + stationId, {},{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function patchStation(id, body) {
  return axios.patch(API_HOST + '/api/v1/stations/' + id, body,{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function deleteStation(id,) {
  return axios.delete(API_HOST + '/api/v1/stations/' + id,{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}
