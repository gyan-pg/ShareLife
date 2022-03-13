import Axios from "axios"
import { OK } from '../util'

const state = {
  himoku: []
}

const getters = {
  getKotei (state) {
    const kotei = state.himoku.filter(elm => {
      if (elm.type === 'kotei') {
        return true
      }
    })
    // nameプロパティのみの配列を返す
    return kotei.map(obj => obj.name)
  },
  getHendo (state) {
    const hendo = state.himoku.filter(elm => {
      if (elm.type === 'hendo') {
        return true
      }
    })
    // nameプロパティのみの配列を返す
    return hendo.map(obj => obj.name)
  }
}

const actions = {
  async getHimoku (context) {
    const response = await Axios.get('/api/adjustment/himoku/get');
    if (response.status === OK) {
      context.state.himoku = response.data
    }
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions
}