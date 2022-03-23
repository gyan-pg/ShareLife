<template>
  <section>
    <div class="p-container--top-adjustment">
      <div class="p-container--top-adjustment__left">
        <div class="p-container--top-adjustment__left-frame">
          <div class="c-date--adjustment">{{ currentMonth }}</div>
          <div class="p-container--btn-center">
            <button class="c-btn--prev" type="button" @click="prevMonth"><i class="fa-solid fa-caret-left"></i>前月</button>
            <button class="c-btn--next" type="button" @click="nextMonth">翌月<i class="fa-solid fa-caret-right"></i></button>
          </div>
        </div>
        <AdjustmentForm />
      </div>
      <div class="p-container--top-adjustment__right">
        <div class="p-container--top-adjustment__right-frame">
          <h2 class="c-title c-title--pending">集計</h2>

          <!-- 総経費 -->
          <div class="p-container--adjustment-row">
            <div class="p-container--adjustment-row__title">
              <span class="material-icons c-icon--adjustment">currency_yen</span><h3 class="c-title--section-adjustment">総支出</h3>
            </div>
            <p v-if="totalPayments" class="c-text u-mt-s">{{ totalPayments }}円</p>
            <p v-else class="c-text u-mt-s">お支払いが登録されていません。</p>
          </div>

          <!-- 変動費 -->
          <div class="p-container--adjustment-row">
            <div class="p-container--adjustment-row__title">
              <span class="material-icons c-icon--adjustment">restaurant</span>
              <h3 class="c-title--section-adjustment">変動費</h3>
              <div v-if="hendo_list.length" class="c-text c-text--result">合計{{ totalHendoPayments }}円</div>
            </div>
            <div v-if="hendo_list.length">
              <ul>
                <li v-for="hendo in hendo_list" :key="hendo.id">
                  <AdjustmentResult :item="hendo" :image="userImage(hendo.user_id)"/>
                </li>
              </ul>
              <div v-if="hendo_over" class="p-container--adjustment-expand">
                <span v-if="!hendo_open" @click="openHendoList" class="material-icons c-icon--adjustment-expand">add_circle</span>
                <span v-if="hendo_open" @click="openHendoList" class="material-icons c-icon--adjustment-expand">remove_circle</span>
              </div>
            </div>
            <div v-else class="c-text">登録されていません。</div>
          </div>

          <!-- 固定費 -->
          <div class="p-container--adjustment-row">
            <div class="p-container--adjustment-row__title">
              <span class="material-icons c-icon--adjustment">other_houses</span>
              <h3 class="c-title--section-adjustment">固定費</h3>
              <div v-if="kotei_list.length" class="c-text c-text--result">合計{{ totalKoteiPayments }}円</div>
            </div>
            <div v-if="kotei_list.length">
              <ul>
                <li v-for="kotei in kotei_list" :key="kotei.id">
                  <AdjustmentResult :item="kotei" :image="userImage(kotei.user_id)"/>
                </li>
              </ul>
              <div v-if="kotei_over" class="p-container--adjustment-expand">
                <span v-if="!kotei_open" @click="openKoteiList" class="material-icons c-icon--adjustment-expand">add_circle</span>
                <span v-if="kotei_open" @click="openKoteiList" class="material-icons c-icon--adjustment-expand">remove_circle</span>
              </div>
            </div>
            <div v-else class="c-text">登録されていません。</div>
          </div>

          <!-- 計算 -->
          <div class="p-container--adjustment-row">
            <div class="p-container--adjustment-row__title">
              <span class="material-icons c-icon--adjustment">receipt_long</span><h3 class="c-title--section-adjustment">お支払い計算</h3>
            </div>
            <p class="c-text u-mt-s">{{ resultText }}</p>
          </div>

        </div>
      </div>
    </div>
  </section>
</template>

<script>
import AdjustmentForm from './AdjustmentForm.vue'
import AdjustmentResult from './AdjustmentResult.vue'
export default {
  data () {
    return {
      kotei_over: false,
      hendo_over: false,
      kotei_open: false,
      hendo_open: false,
      show_list_num: 3
    }
  },
  components: {
    AdjustmentForm,
    AdjustmentResult
  },
  computed: {
    kotei_list () {
      let list = this.$store.getters['payments/getKoteiPayments']
      if (list.length > this.show_list_num) {
        this.kotei_over = true
        if (this.kotei_open) {
          return list
        } else {
          return list.slice(0, 3)
        }
      }
      this.kotei_over = false
      return list
    },
    hendo_list () {
      let list = this.$store.getters['payments/getHendoPayments']
      if (list.length > this.show_list_num) {
        this.hendo_over = true
        if (this.hendo_open) {
          return list
        } else {
          return list.slice(0, 3)
        }
      }
      this.hendo_over = false
      return list
    },
    currentMonth () {
      return this.$store.state.payments.currentDate.format('YYYY[年]MM[月]')
    },
    totalPayments () {
      if (this.$store.getters['payments/totalPayments']) {
        return this.$store.getters['payments/totalPayments']
      }
    },
    myPayments () {
      return this.$store.getters['payments/myPayments']
    },
    // 計算結果が負の値ならもらう。正なら払う。
    resultPayments () {
      return (this.totalPayments / 2) - this.myPayments
    },
    totalKoteiPayments () {
      let koteiPayments = this.$store.getters['payments/getKoteiPayments']
      let sum = 0
      koteiPayments.forEach(element => {
        sum += element.cost
      })
      return sum
    },
    totalHendoPayments () {
      let hendoPayments = this.$store.getters['payments/getHendoPayments']
      let sum = 0
      hendoPayments.forEach(element => {
        sum += element.cost
      })
      return sum
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
  },
  methods: {
    nextMonth () {
      this.$store.commit('payments/nextMonth')
      this.$store.dispatch('payments/getPayments')
    },
    prevMonth () {
      this.$store.commit('payments/prevMonth')
      this.$store.dispatch('payments/getPayments')
    },
    userImage (id) {
      if (this.$store.state.auth.user.id === id) {
        return this.$store.state.auth.user.image
      } else {
        return this.$store.getters['auth/partner'].image
      }
    },
    openKoteiList () {
      this.kotei_open = !this.kotei_open
    },
    openHendoList () {
      this.hendo_open = !this.hendo_open
    }
  }
}
</script>