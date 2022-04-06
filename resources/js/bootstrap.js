window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// 自作関数
import { getCookieValue, UNAUTHORIZED, UNAUTHORIZED_MESSAGE, INTERNAL_SERVER_ERROR, UNAUTHORIZED_STATUS } from './util'
import store from './store'

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// laravelのcsrfトークンをaxiosのリクエストのヘッダーに付与している。
window.axios.interceptors.request.use(config => {
  // クッキーからトークンを取り出してヘッダーに添付する
  config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN')
  return config
})

// responseを受け取った後の処理を上書き。第一引数は成功時、第二引数は失敗時の処理。
// 第一引数はデフォルトのまま、第二引数を変更。同様の処理が多々あるので。
// エラーで認証切れの際は自動でログインページに飛ばす処理。
window.axios.interceptors.response.use(
  response => response,
  error => {
    if (error.response.status === UNAUTHORIZED || error.response.status === UNAUTHORIZED_STATUS){
      store.commit('messages/setMessage', UNAUTHORIZED_MESSAGE, {root: true})
      store.commit('error/setCode', UNAUTHORIZED , {root: true})
    } else if (error.response.status === INTERNAL_SERVER_ERROR) {
      store.commit('error/setCode', INTERNAL_SERVER_ERROR, {root: true})
      // console.log(error.response)
    } else {
      return error.response || error
    }
  }
)

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });



