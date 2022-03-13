<template>
  <section>
    <p>{{ currentMonth }}</p>
    <button class="c-btn" @click="prevMonth">前月</button><button class="c-btn" @click="nextMonth">翌月</button>
    <div>
      <p>固定</p>
      <div v-if="kotei_list.length">
        <ul>
          <li v-for="kotei in kotei_list" :key="kotei.id">
            {{ kotei.himoku }}{{ kotei.cost }}円<span>{{ userName(kotei.user_id) }}</span><button class="c-btn c-btn--delete" @click="deletePayment(kotei.id)">削除</button>
          </li>
        </ul>
      </div>
      <div v-else>登録されていません。</div>
    </div>
    <div>
      <p>変動</p>
      <div v-if="hendo_list.length">
        <ul>
          <li v-for="hendo in hendo_list" :key="hendo.id">
            {{ hendo.himoku }}{{ hendo.cost }}円<span>{{ userName(hendo.user_id) }}</span><button class="c-btn c-btn--delete" @click="deletePayment(hendo.id)">削除</button>
          </li>
        </ul>
      </div>
      <div v-else>登録されていません。</div>
    </div>
    <div>
      <p>総経費</p>
      <span>{{ totalPayments }}円</span>
      <p>お支払い計算</p>
      <span>{{ resultText() }}</span>
    </div>
  </section>
</template>

<script>
import { OK, NUMBER_PATTERN, UNPROCESSABLE_ENTITY } from '../util'
export default {
  data () {
    return {
    }
  },
  computed: {
    kotei_list () {
      return this.$store.getters['payments/getKoteiPayments']
    },
    hendo_list () {
      return this.$store.getters['payments/getHendoPayments']
    },
    currentMonth () {
      return this.$store.state.payments.currentDate.format('YYYY[年]MM[月]')
    },
    totalPayments () {
      return this.$store.getters['payments/totalPayments']
    },
    myPayments () {
      return this.$store.getters['payments/myPayments']
    },
    // 計算結果が負の値ならもらう。正なら払う。
    resultPayments () {
      return (this.totalPayments / 2) - this.myPayments
    }
  },
  methods: {
    async deletePayment (id) {
      // idのバリデーション
      if (!NUMBER_PATTERN.test(id)) {
        this.$store.commit('messages/setErrorMessage', 'エラーが発生しました。')
        return false
      }
      const response = await axios.delete(`/api/adjustment/delete/${id}`)
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', 'レコードを削除しました。')
        this.$store.dispatch('payments/getPayments')
      } else if (response.data === UNPROCESSABLE_ENTITY) {
        this.$store.commit('messages/setErrorMessage', 'エラーが発生しました。')
      }
    },
    nextMonth () {
      this.$store.commit('payments/nextMonth')
      this.$store.dispatch('payments/getPayments')
    },
    prevMonth () {
      this.$store.commit('payments/prevMonth')
      this.$store.dispatch('payments/getPayments')
    },
    userName (id) {
      if (this.$store.state.auth.user.id === id) {
        return this.$store.state.auth.user.name
      } else {
        return this.$store.getters['auth/partner'].name
      }
    },
    resultText () {
      // 負の値なら相手からもらう
      if (this.totalPayments) {
        if (this.resultPayments < 0) {
          return `${this.$store.getters['auth/partner'].name}さんからあなたへ${Math.abs(this.resultPayments)}円のお支払いです。`
        } else if (this.resultPayments > 0) {
          return `${this.$store.getters['auth/partner'].name}さんへ${Math.abs(this.resultPayments)}円のお支払いです。`
        } else {
          return '綺麗に割り切れました。やったね！'
        }
      } else {
        return 'お支払いが登録されていません。'
      }
    }
  }
}
</script>