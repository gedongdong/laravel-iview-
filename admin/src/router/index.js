import Vue from 'vue'
import Router from 'vue-router'
import routes from './routers'
import store from '@/store'
import iView, {Message} from 'iview'
import {getToken, TOKEN_KEY} from '@/libs/util'
import axios from '@/libs/api.request'
import Cookies from 'js-cookie'

Vue.use(Router)
const router = new Router({
  routes,
  mode: 'history'
})
const LOGIN_PAGE_NAME = 'login'
const HOME_PAGE_NAME = 'home'
const checkToken = (token, to, next) => {
  axios.request({
    url: 'token_check',
    params: {
      token
    },
    method: 'post'
  }).then(res => {
    if (res.data.token_validate) {
      store.dispatch('getUserInfo').then(user => {
        // console.log(user)
      })
      if (to === '') {
        next()
      } else {
        next({name: to})
      }
    } else {
      Cookies.remove(TOKEN_KEY)
      next({
        name: LOGIN_PAGE_NAME // 跳转到登录页
      })
    }
  }).catch(err => {
    // console.log(err)
    // Message.error(err.data.msg)
    Cookies.remove(TOKEN_KEY)
    next({
      name: LOGIN_PAGE_NAME // 跳转到登录页
    })
  })
}

router.beforeEach((to, from, next) => {
  iView.LoadingBar.start()
  const token = getToken()
  if (!token && to.name !== LOGIN_PAGE_NAME) {
    // 未登录且要跳转的页面不是登录页
    next({
      name: LOGIN_PAGE_NAME // 跳转到登录页
    })
  } else if (!token && to.name === LOGIN_PAGE_NAME) {
    // 未登陆且要跳转的页面是登录页
    next() // 跳转
  } else if (token && to.name === LOGIN_PAGE_NAME) {
    // 已登录且要跳转的页面是登录页
    checkToken(token, HOME_PAGE_NAME, next)
  } else {
    checkToken(token, '', next)
  }
})

router.afterEach(to => {
  iView.LoadingBar.finish()
  window.scrollTo(0, 0)
})

export default router
