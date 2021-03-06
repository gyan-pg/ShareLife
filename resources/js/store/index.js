import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import messages from './messages'
import events from './events'
import agreements from './agreements'
import himoku from './himoku'
import payments from './payments'
import page from './page'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    error,
    messages,
    events,
    agreements,
    himoku,
    payments,
    page
  }
})

export default store