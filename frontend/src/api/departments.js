import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function getAllDepartments() {
  return axios.get(API_HOST + '/api/v1/departments', {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}
