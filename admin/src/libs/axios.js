import Axios from 'axios'
import { Message } from 'iview'
import Cookies from 'js-cookie'
import { TOKEN_KEY } from '@/libs/util'
import config from '@/config'
class httpRequest {
  constructor () {
    this.options = {
      method: '',
      url: ''
    }
    // 存储请求队列
    this.queue = {}
  }
  // 销毁请求实例
  destroy (url) {
    delete this.queue[url]
    const queue = Object.keys(this.queue)
    return queue.length
  }
  // 请求拦截
  interceptors (instance, url) {
    // 添加请求拦截器
    instance.interceptors.request.use(config => {
      if (!config.url.includes('/users')) {
        config.headers['x-access-token'] = Cookies.get(TOKEN_KEY)
      }
      // Spin.show()
      // 在发送请求之前做些什么
      return config
    }, error => {
      // 对请求错误做些什么
      return Promise.reject(error)
    })

    // 添加响应拦截器
    instance.interceptors.response.use((res) => {
      let { data } = res
      const is = this.destroy(url)
      if (!is) {
        setTimeout(() => {
          // Spin.hide()
        }, 500)
      }
      if (data.errcode !== 0) {
        // 后端服务在个别情况下回报201，待确认
        if (data.errcode === 10001) {
          Cookies.remove(TOKEN_KEY)
          window.location.href = window.location.pathname + '#/login'
          Message.error('未登录，或登录已过期，请重新登录')
        } else if (data.errcode === 20000) {
          // 表单校验失败，错误信息为数组
          if (data.errmsg) {
            Message.error({
              content: JSON.stringify(data.errmsg),
              duration: 3
            })
          }
        } else {
          if (data.errmsg) {
            Message.error({
              content: data.errmsg,
              duration: 3
            })
          }
        }
        return false
      }
      return data
    }, (error) => {
      Message.error(JSON.stringify(error.response.data.errmsg))
      // 对响应错误做点什么
      return Promise.reject(error.response)
    })
  }
  // 创建实例
  create () {
    let conf = {
      baseURL: config.axiosBaseUrl,
      // timeout: 2000,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'X-URL-PATH': location.pathname
      }
    }
    return Axios.create(conf)
  }
  // 合并请求实例
  mergeReqest (instances = []) {
    //
  }
  // 请求实例
  request (options) {
    var instance = this.create()
    this.interceptors(instance, options.url)
    options = Object.assign({}, options)
    this.queue[options.url] = instance
    return instance(options)
  }
}
export default httpRequest
