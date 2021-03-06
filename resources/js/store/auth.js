import Axios from "axios"
import { CREATED, OK, UNPROCESSABLE_ENTITY } from "../util"

const state = {
  user: null, // user情報のあるなしでログイン状態を判断する。
  apiStatus: null, // 通信の成功/失敗の状態を判断する。
  loginErrorMessages: null,
  resetErrorMessages: null,
  registerErrorMessages: null,
  resettingErrorMessages: null,
  passwordChangeErrorMessages: null,
  team: null,
  owner: null,
  teamMember: null,
  registerUser: null,
  tokenStatus: null,
}

const getters = {
  check: state => !!state.user,
  username: state => state.user ? state.user.name : '',
  checkToken: state => state.registerUser ? state.registerUser : '',
  guest: state => {
    if ( state.user.id === 100001 || state.user.id === 100002) {
      return true
    } else {
      return false
    }
  },
  // チームの相手の情報を返す
  partner: state => {
    if(state.owner.id === state.user.id) {
      return state.teamMember
    } else {
      return state.owner
    }
  },
  teamOwner: state => {
    if (state.team){ // チームが作成されているか
      // チームのuser1_idが自分のものなら自分がチームオーナーとする。
      return state.team.user1_id === state.user.id ? true : false
    }
    return null
  },
  checkTeam: state => {
    // チームがある時
    if (state.team){
      // チームステータスがapproveの時
      if (state.team.status === 'approve') {
        return true
      } else {
        return false
      }
    // チームが作られていない時
    } else {
      return false
    }
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
  setResetErrorMessages (state, messages) {
    state.resetErrorMessages = messages
  },
  setRegisterErrorMessages (state, messages) {
    state.registerErrorMessages = messages
  },
  setTeam (state, team) {
    state.team = team
  },
  setTeamStatus (state, status) {
    if (status !== null) {
      state.team.status = status
    } else {
      state.team = status
    }
  },
  setOwner (state, owner) {
    state.owner = owner
  },
  setTeamMember (state, teamMember) {
    state.teamMember = teamMember
  },
  setRegisterToken (state, registerUserInfo) {
    state.registerUser = registerUserInfo
    setTimeout(() => { state.registerUser = null }, 1000 * 60 * 30)
  },
  setTokenStatus (state, status) {
    state.tokenStatus = status
  },
  setResettingErrorMessages (state, messages) {
    state.resettingErrorMessages = messages
  },
  setPasswordChangeErrorMessages (state, messages) {
    state.passwordChangeErrorMessages = messages
  }
}

const actions = {
  async register (context, data) {
    context.commit('setApiStatus', null)
    const response = await Axios.post('/api/register', data)
    if (response.status === CREATED) {
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
    if (response.status === OK) { // responseのstatusがOKの時は、通常の処理
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data[0])
      context.commit('setTeam', response.data[1])
      // a = b ?? c は、aの値がnull/undefindならばcを返すというもの。
      context.commit('setOwner', response.data[2] = response.data[2] ?? null)
      context.commit('setTeamMember', response.data[3] = response.data[3] ?? null)
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
      context.commit('agreements/setAgreements', [], { root: true })
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

    if (response.status === OK) {
      context.commit('setApiStatus', true)
      const team = response.data || null
      if (team){
        context.commit('setTeam', team[0])
        context.commit('setOwner', team[1])
        context.commit('setTeamMember', team[2])
      }
      return false
    }
    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },
  // パスワードリセット関係
  async resetPassword (context, data) {
    context.setApiStatus = null
    const response = await Axios.post('/api/password/reception', data)
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setResetErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },
  // パスワード再設定フォームから情報が送られた時の処理
  async passwordResetting (context, data) {
    context.commit('setResettingErrorMessages', null)
    context.commit('setApiStatus', null)
    const response = await Axios.put('/api/password/reset', data)
    if (response.status === OK) {
      await context.dispatch('login', response.data)
      return false
    }

    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setResettingErrorMessages', response.data.errors)
    }
  },
  // パスワードの変更
  async passwordChange (context, data) {
    context.commit('setPasswordChangeErrorMessages', null)
    context.commit('setApiStatus', null)
    const response = await Axios.put('/api/password/change', data)
    // バリデーションに問題がなかった時
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      return false
    }
    // バリデーションエラーの時
    context.commit('setApiStatus', false)
    if (response.status === UNPROCESSABLE_ENTITY) {
      context.commit('setPasswordChangeErrorMessages', response.data.errors)
    }
  },

  // Eメールのリンクに添付しているトークンが正しいのか確認する。
  async tokenCheck (context, data) {
    context.setApiStatus = null
    const response = await Axios.post('/api/checktoken', data)
    if (response.status === OK) {
      context.commit('setApiStatus', true)
      context.commit('setTokenStatus', response.data.result)
      return false
    }
    context.commit('setApiStatus', false)
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}