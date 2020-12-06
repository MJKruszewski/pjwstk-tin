import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;


export async function getAllShipTasks(shipId, page, itemsPerPage) {
  let query = '';

  if (typeof page !== 'undefined' && typeof itemsPerPage !== 'undefined') {
    query = '?page=' + page + '&itemsPerPage=' + itemsPerPage;
  }

  return axios.get(API_HOST + '/api/v1/ships/' + shipId + '/tasks' + query, {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function patchTask(id, body) {
  return axios.patch(API_HOST + '/api/v1/tasks/' + id, body, {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function postTask(body) {
  return axios.post(API_HOST + '/api/v1/tasks', body, {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export const TASK_CODES = [
  'engine_check',
  'engine_calibrations',
  'engine_issue',
  'medical_treatment',
  'nurse_help',
  'ship_navigation',
  'ship_driving',
];

export const PRIORITIES = [
  'high',
  'medium',
  'low',
];
