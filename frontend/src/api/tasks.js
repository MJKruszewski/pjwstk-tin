import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;


export async function getAllTasks() {
  return axios.get(API_HOST + '/api/v1/tasks', {
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
