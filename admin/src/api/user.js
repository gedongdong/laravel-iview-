import axios from '@/libs/api.request'

export const login = ({email, password}) => {
  const data = {
    email,
    password
  }
  return axios.request({
    url: 'get_token',
    data,
    method: 'post'
  })
}

export const getUserInfo = (token) => {
  return axios.request({
    url: 'get_user',
    params: {
      token
    },
    method: 'post'
  })
}

export const logout = (token) => {
  return axios.request({
    url: 'delete_token',
    params: {
      token
    },
    method: 'post'
  })
}

