import auth from "./auth"

const state = {
  agreements: []
}

const mutations = {
  setAgreements (state, agreement) {
    state.agreements = agreement
  }
}

const getters = {
  // 承認済みのagreementを返す
  approvedAgreements: state => {
    return state.agreements.filter(agree => {
      if (agree.approval === 'approved') {
        return true
      }
    })
  },
  // 未承認のagreementを返す
  notApprovedAgreements: state => {
    const result = state.agreements.filter(agree => {
      if (agree.approval === '0') {
        return true
      } else if (agree.approval === 'deny' && agree.user_id === auth.state.user.id) {
        return true
      }
    })
    return result
  }
}

const actions = {
  async getAgreements (context) {
    const response = await axios.get(`api/agreement/${context.rootState.auth.team.id}/list`)
    context.commit('setAgreements', response.data)
  },
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions
}