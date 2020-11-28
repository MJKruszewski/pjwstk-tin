import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function getAllShips() {
  return axios.get(API_HOST + '/api/v1/ships', {
    headers: {
      Authorization: localStore.state.session.token
    }
  });
}
