import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import messages from './messages'
import events from './events'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    messages,
    events
  }
})

export default store