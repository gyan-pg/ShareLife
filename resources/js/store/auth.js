import Axios from "axios"
import { OK, UNPROCESSABLE_ENTITY } from "../util"

const state = {
  user: null, // user情報のあるなしでログイン状態を判断する。
  apiStatus: null, // 通信の成功/失敗の状態を判断する。
  loginErrorMessages: null,
  registerErrorMessages: null,
  team: null,
  owner: null,
  partner: null
}

const getters = {
  check: state => !!state.user,
  username: state => state.user ? state.user.name : '',
  teamOwner: state => {
    if (state.team){ // チームが作成されているか
      // チームのuser1_idが自分のものなら自分がチームオーナーとする。
      return state.team.user1_id === state.user.id ? true : false
    }
    return null
  }
}

const mutations = {
  setUser (state, user) {
    state.user = user
  },
  setApiStatus (state, status) {
    state.apiStatus = status
  },
  setLoginErrorMessages (state, messages) {
    state.loginErrorMessages = messages
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  },
  setTeam (state, team) {
    state.team = team
  },
  setTeamStatus (state, status) {
    state.team.status = status
  },
  setOwner (state, owner) {
    state.owner = owner
  },
  setPartner (state, partner) {
    state.partner = partner
  }
}

const actions = {
  async register (context, data) {
    context.commit('setApiStatus', null)
    const response = await Axios.post('/api/register', data)

    if (response.status === OK) {
      context.commit('setUser', response.data)
      context.commit('setApiStatus', true)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }

  },
  async login (context, data) {
    context.commit('setApiStatus', null)
    // axiosからの返り値はbootstrap.jsで加工している。
    const response = await Axios.post('/api/login', data)
    console.log(response.data)
    if (response.status === OK) { // responseのstatusがOKの時は、通常の処理
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data[0])
      context.commit('setTeam', response.data[1])
      // a = b ?? c は、aの値がnull/undefindならばcを返すというもの。
      context.commit('setOwner', response.data[2] = response.data[2] ?? null)
      context.commit('setPartner', response.data[3] = response.data[3] ?? null)
      return false
    }

    // responseのstatusが200以外の時は、下記処理
    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }

  },
  async logout (context) {
    context.commit('setApiStatus', null)
    const response = await Axios.post('/api/logout')

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      context.commit('setTeam', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },

  async currentUser (context) { // ログイン確認
    context.commit('setApiStatus', null)
    const response = await Axios.get('/api/user') // /api/userはログインしている人の情報を返却する。

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      const user = response.data || null // response.dataがない時は、||右側の値(null)が代入される。
      context.commit('setUser', user)
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })

  },

  async currentTeam (context) { // チーム確認
    context.commit('setApiStatus', null)
    const response = await Axios.get('/api/user/team')

    console.log('current team')
    console.log(response.data)
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      const team = response.data || null
      if (team){
        context.commit('setTeam', team[0])
        context.commit('setOwner', team[1])
        context.commit('setPartner', team[2])
      }
      return false
    }
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}