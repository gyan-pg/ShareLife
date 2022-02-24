import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index'

import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Mypage from '../pages/Mypage.vue'
import Index from '../pages/Index.vue'
import SystemError from '../pages/errors/System.vue'
import NotFound from '../pages/errors/NotFound.vue'
import Agreement from '../pages/Agreement.vue'

// これ書いてないと、router-viewなどが使えない
Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: Index,
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
    path: '/register',
    component: Register,
    beforeEnter (to, from, next) {
      if (store.getters['auth/check']) {
        next('/mypage')
      } else {
        next()
      }
    }
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
    path: '/500',
    component: SystemError
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

export default router