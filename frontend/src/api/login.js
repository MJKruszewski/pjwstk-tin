import {API_HOST} from "./consts";
import {localStore} from '../main'

const axios = require('axios').default;

export async function postLogin(login, password) {
  return axios.post(API_HOST + '/api/v1/auth/login', JSON.stringify({
    'login': login,
    'password': password
  }), {
    headers: {
      'Content-Type': 'application/json'
    }
  });
}

