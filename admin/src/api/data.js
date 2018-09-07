import axios from '@/libs/api.request'
import { getToken } from '@/libs/util'

const token = getToken()

export const getCategoryData = () => {
  return axios.request({
    url: 'category',
    method: 'get'
  })
}

export const getArticleData = (page) => {
  return axios.request({
    url: 'article',
    params: {
      page
    },
    method: 'get'
  })
}

export const getCategoryInfo = (id) => {
  return axios.request({
    url: 'category_info',
    params: {
      id
    },
    method: 'get'
  })
}

export const addCategoryData = (params) => {
  return axios.request({
    url: 'category',
    params: params,
    method: 'post'
  })
}

export const addArticleData = (params) => {
  return axios.request({
    url: 'article',
    params: params,
    method: 'post'
  })
}

export const getArticleInfo = (id) => {
  return axios.request({
    url: 'article_info',
    params: {
      id
    },
    method: 'get'
  })
}

export const deleteCategoryData = (id) => {
  return axios.request({
    url: 'category',
    params: {
      token,
      id
    },
    method: 'delete'
  })
}
