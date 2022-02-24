import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import messages from './messages'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    messages
  }
})

export default store