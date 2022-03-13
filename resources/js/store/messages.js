const state = {
  message: null,
  errorMessage: null
}

const mutations = {
  setMessage (state, message) {
    state.message = message
    setTimeout(() => {state.message = ''} , 3000)
  },
  setErrorMessage (state, message) {
    state.errorMessage = message
    setTimeout(() => {state.errorMessage = ''} , 3000)
  }
}

export default {
  namespaced: true,
  state,
  mutations
}