import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index'

import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Mypage from '../pages/Mypage.vue'
import SystemError from '../pages/errors/System.vue'
import NotFound from '../pages/errors/NotFound.vue'
import Agreement from '../pages/Agreement.vue'
import Adjustment from '../pages/Adjustment.vue'
import PreRegister from '../pages/PreRegister.vue'
import Withdraw from '../pages/Withdraw.vue'

// これ書いてないと、router-viewなどが使えない
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: Login,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/mypage')
      } else {
        next ()
      }
    }
  },
  {
    path: '/mypage',
    component: Mypage,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/mypage')
      } else {
        next()
      }
    }
  },
  {
    path: '/preRegister',
    component: PreRegister,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/mypage')
      } else {
        next()
      }
    }
  },
  {
    path: '/register',
    component: Register,
    beforeEnter (to, from, next) {
      if (store.getters['auth/checkToken']) {
        next()
      } else {
        next('preRegister')
      }
    }
  },
  {
    path: '/withdraw',
    component: Withdraw
  },
  {
    path: '/agreement',
    component: Agreement,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/adjustment',
    component: Adjustment,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next()
      } else {
        next('/')
      }
    }
  },
  {
    path: '/500',
    component: SystemError,
  },
  {
    path: '*',
    component: NotFound
  }
]

// VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  routes
})

// 500エラーを一回すると、errorCodeがリフレッシュされない問題の暫定解決方法。
router.beforeEach((to, from, next) => {
  store.commit('error/setCode', null)
  next()
})

export default router