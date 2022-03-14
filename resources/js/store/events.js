import axios from 'axios'
import dayjs from 'dayjs'
import { OK, UNAUTHORIZED, UNAUTHORIZED_MESSAGE } from '../util'

const state = {
  event: [],
  holiday: {}
}

const mutations = {
  setEvent (state, event) {
    state.event = event
  },
  setHolidays (state, holidays) {
    state.holiday = holidays
  }
}

const actions = {
  async getScheduleList (context) {
    const response = await axios.get('/api/schedule/show')
    if (response.status === OK) {
      context.commit('setEvent', response.data)
    } else if (response.status === UNAUTHORIZED) {
      context.commit('error/setCode', UNAUTHORIZED, { root: true })
      context.commit('messages/setMessage')
    }
  },
  async changeScheduleList(context, newData) {
    // newDataの中身は{id , start}
    let targetEvent = context.state.event.find(target => target.id === parseInt(newData.id))
    const betweenDays = dayjs(targetEvent.end).diff(dayjs(targetEvent.start), 'day')
    const end = dayjs(newData.start).add(betweenDays, 'day').format('YYYY-MM-DD')
    const changeData = {'id': newData.id, 'start': newData.start, 'end': end}
    
    const response = await axios.put('/api/schedule/changeDay', changeData)

    if (response.status === OK) {
      // stateの更新
      context.dispatch('getScheduleList')
      return false
    }
  },
  async getHolidayList (context, year) {
    const response = await axios.get(`https://holidays-jp.github.io/api/v1/${year}/date.json`)
    if (response.status === OK) {
      console.log(response)
    }
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}