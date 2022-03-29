const state = {
  page: null
}

const mutations = {
  setPage (state, page) {
    state.page = page
  }
}

export default {
  namespaced: true,
  state,
  mutations
}