const state = {
  message: null
}

const mutations = {
  setMessage (state, message) {
    state.message = message
    setTimeout(() => {state.message = ''} , 3000)
  }
}

export default {
  namespaced: true,
  state,
  mutations
}