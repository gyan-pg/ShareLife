import Axios from "axios"
import dayjs from "dayjs"
import auth from "./auth"
import { OK } from '../util'

const state = {
  payments: [],
  currentDate: dayjs()
}

const mutations = {
  setPayments(state, data) {
    state.payments = data
  },
  nextMonth(state) {
    state.currentDate = dayjs(state.currentDate).add(1, 'month')
  },
  prevMonth(state) {
    state.currentDate = dayjs(state.currentDate).subtract(1, 'month')
  }
}

const getters = {
  getKoteiPayments: state => {
    return state.payments.filter(elm => {
      if (elm.type === 'kotei') {
        return true
      }
    })
  },
  getHendoPayments: state => {
    return state.payments.filter(elm => {
      if (elm.type === 'hendo') {
        return true
      }
    }) 
  },
  totalPayments: state => {
    let sum = 0
    state.payments.forEach(element => {
      sum += element.cost
    });
    return sum
  },
  myPayments: state => {
    let sum = 0
    state.payments.forEach(element => {
      if (element.user_id === auth.state.user.id)
      sum += element.cost
    })
    return sum
  }
}

const actions = {
  async getPayments (context) {
    const currentMonth = context.state.currentDate.format('YYYY-MM')
    const response = await Axios.get(`/api/adjustment/show/${currentMonth}`)
    if (response.status === OK) {
      context.commit('setPayments', response.data)
    }
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions
}