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
  },
  // グラフ用のデータを作る
  monthlyReport: state => {
    let himoku = []
    // 費目の一覧を作る
    state.payments.forEach(element => {
      if (element.himoku === 'その他') {
        if (element.type === 'hendo') {
          himoku.push('その他(変動)')
        } else {
          himoku.push('その他(固定)')
        }
      } else {
        himoku.push(element.himoku)
      }
    })
    himoku = new Set(himoku)// 費目の値で構成されたオブジェクトとなる。重複はなくなる。
    let himoku_list = [...himoku]// 費目の値の配列となる。


    let himoku_result = []
    // himoku_listから各費目の金額の合計を求め配列を作る。
    for(const elm of himoku_list) {
      let sum = 0
      state.payments.forEach(element => {
        let name = element.himoku
        if(name === 'その他') {
          if (element.type === 'hendo') {
            name = name+'(変動)'
          } else {
            name = name+'(固定)'
          }
        }
        if(name === elm) {
          sum += element.cost
        }
      })
      himoku_result.push(sum)
    }
    // 金額順にソートするため、各費目と金額を一旦オブジェクトにする。
    // [{himoku:cost},{himoku:cost},...]
    let sort_obj = himoku_result.reduce((accumulator, currentValue, index) => {
      accumulator[himoku_list[index]] = currentValue
      return accumulator
    }, {})

    // オブジェクト化したものを一旦分解してソート。ソート結果は配列の配列になる。金額で降順。
    let pairs = Object.entries(sort_obj)
    pairs.sort((a, b) => {
      let aVal = a[1]
      let bVal = b[1]
      return (aVal > bVal ? -1 : 1)
    })
  
    let result_cost = []
    let result_himoku = []
    // 費目だけの配列、金額だけの配列に直す。
    pairs.forEach(elm => {
      result_himoku.push(elm[0])
      result_cost.push(elm[1])
    })

    const result = []
    result.push(result_himoku, result_cost)

    return result
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