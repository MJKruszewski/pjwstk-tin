import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function getAllDepartments(page, itemsPerPage) {
  let query = '';

  if (typeof page !== 'undefined' && typeof itemsPerPage !== 'undefined') {
    query = '?page=' + page + '&itemsPerPage=' + itemsPerPage;
  }

  return axios.get(API_HOST + '/api/v1/departments' + query, {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function postDepartment(body) {
  return axios.post(API_HOST + '/api/v1/departments', body,{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function patchDepartment(id, body) {
  return axios.patch(API_HOST + '/api/v1/departments/' + id, body,{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}

export async function deleteDepartment(id) {
  return axios.delete(API_HOST + '/api/v1/departments/' + id,{
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}
