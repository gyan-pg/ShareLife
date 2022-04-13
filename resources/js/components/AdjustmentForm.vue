<template>
  <section class="p-wrapper--adjustment-form">
    <button class="c-btn c-btn--form-tab-hendo" :class="{'active': open_hendo === true}" type="button" @click="openHendo">変動費入力</button>
    <button class="c-btn c-btn--form-tab-kotei" :class="{'active': open_kotei === true}" type="button" @click="openKotei">固定費入力</button>

    <!-- 変動費の入力 -->
    <div class="p-container--adjustment-hendo" v-if="open_hendo">
      <!-- 金額 -->
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="payments-hendo"><span class="material-icons c-icon--adjustment-form">payments</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <input id="payments-hendo" class="c-form__input--adjustment" type="text" placeholder="金額を入力してください。" v-model="hendo.cost">
        </div>
      </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.hendo.cost" :key="index">{{ error }}</span></div>
      </div>

      <!-- コメント -->
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="comment-hendo"><span class="material-icons c-icon--adjustment-form">notes</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <input id="comment-hendo" class="c-form__input--adjustment" type="text" placeholder="コメントがあれば入力してください。" v-model="hendo.comment">
        </div>
      </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.hendo.comment" :key="index">{{ error }}</span></div>
      </div>

      <!-- 費目 -->
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="himoku-hendo"><span class="material-icons c-icon--adjustment-form">menu_book</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <select id="himoku-hendo" class="c-form__select--adjustment" v-model="hendo.himoku">
            <option value hidden>選択してください</option>
            <option v-for="(himoku, index) in hendo_list" :key="index" :value="himoku">{{ himoku }}</option>
          </select>
        </div>
      </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <!-- サーバーからのエラー文 -->
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.hendo.himoku" :key="index">{{ error }}</span></div>
      </div>

      <!-- 支払った人 -->
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="person-hendo"><span class="material-icons c-icon--adjustment-form">people</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <select id="person-hendo" class="c-form__select--adjustment" v-model="hendo.user">
            <option :value="user_info.id" selected>{{ user_info.name }}</option>
            <option :value="partner_info.id">{{ partner_info.name }}</option>
          </select>
        </div>
      </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.hendo.user" :key="index">{{ error }}</span></div>
      </div>

      <div class="p-container--btn-right">
        <button type="button" class="c-btn c-btn--submit" @click="submitHendo" :disabled="sending">登録</button>
      </div>
    </div>

    <!-- 固定費の入力フォーム -->
    <!-- 金額 -->
    <div class="p-container--adjustment-kotei" v-if="open_kotei">
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="payments-kotei"><span class="material-icons c-icon--adjustment-form">payments</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <input id="payments-kotei" class="c-form__input--adjustment-red" type="text" placeholder="金額を入力してください。" v-model="kotei.cost">
        </div>
      </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.kotei.cost" :key="index">{{ error }}</span></div>
      </div>

      <!-- コメント -->
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="comment-kotei"><span class="material-icons c-icon--adjustment-form">notes</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <input id="comment-kotei" class="c-form__input--adjustment-red" type="text" placeholder="コメントがあれば入力してください。" v-model="kotei.comment">
        </div>
        </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.kotei.comment" :key="index">{{ error }}</span></div>
      </div>

      <!-- 費目 -->
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="himoku-kotei"><span class="material-icons c-icon--adjustment-form">menu_book</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <select id="himoku-kotei" class="c-form__select--adjustment-red" v-model="kotei.himoku">
            <option value hidden>選択してください</option>
            <option v-for="(himoku, index) in kotei_list" :key="index" :value="himoku">{{ himoku }}</option>
          </select>
        </div>
      </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.kotei.himoku" :key="index">{{ error }}</span></div>
      </div>
      
      <!-- 支払った人 -->
      <div class="p-container--adjustment-form-row">
        <div class="p-container--adjustment-form-row__left">
          <label for="person-kotei"><span class="material-icons c-icon--adjustment-form">people</span></label>
        </div>
        <div class="p-container--adjustment-form-row__right">
          <select id="person-kotei" class="c-form__select--adjustment-red" v-model="kotei.user">
            <option :value="user_info.id" selected>{{ user_info.name }}</option>
            <option :value="partner_info.id">{{ partner_info.name }}</option>
          </select>
        </div>
      </div>
      <!-- エラー表示 -->
      <div class="p-container--input-notice">
        <div v-if="error_flg"><span class="c-text--error" v-for="(error, index) in error.kotei.user" :key="index">{{ error }}</span></div>
      </div>

      <div class="p-container--btn-right">
        <button type="button" class="c-btn c-btn--submit-red" @click="submitKotei" :disabled="sending">登録</button>
      </div>
    </div>

  </section>
</template>

<script>
import { OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  data () {
    return {
      kotei: {
        cost: null,
        type: '',
        himoku: '',
        comment: '',
        user: '',
        date: null
      },
      hendo: {
        cost: null,
        type: '',
        himoku: '',
        comment: '',
        user: '',
        date: null
      },
      error: {
        kotei: {},
        hendo: {}
      },
      error_flg: false,
      open_kotei: false,
      open_hendo: true,
      sending: false,
    }
  },
  computed: {
    kotei_list () {
      return this.$store.getters['himoku/getKotei']
    },
    hendo_list () {
      return this.$store.getters['himoku/getHendo']
    },
    user_info () {
      return this.$store.state.auth.user
    },
    partner_info () {
      return this.$store.getters['auth/partner']
    }
  },
  methods: {
    async submitKotei () {
      this.sending = true
      this.kotei.type = 'kotei'
      this.kotei.date = this.$store.state.payments.currentDate.format('YYYY-MM-DD')
      this.error_flg = false
      this.error.kotei = {}
      // フロント側バリデーション
      this.error.kotei.cost = this.validCost(this.kotei.cost)
      this.error.kotei.himoku = this.validHimoku(this.kotei.himoku)
      this.error.kotei.comment = this.validComment(this.kotei.comment)

      if (!this.error_flg) {
        const error = await this.submit(this.kotei)
        // エラーがサーバーから帰ってきていた時
        if (error) {
          this.error_flg = true
          this.error.kotei = error
        }
        // 初期化
        for (let key in this.kotei) {
          if (key === 'user') {
            this.kotei[key] = this.user_info.id
          } else if (key === 'himoku') {
            this.kotei[key] = ""
          } else {
            this.kotei[key] = null
          }
        }
      }
      this.sending = false
    },
    async submitHendo () {
      this.sending = true
      this.hendo.type = 'hendo'
      this.hendo.date = this.$store.state.payments.currentDate.format('YYYY-MM-DD')
      this.error_flg = false
      this.error.hendo = {}
      // フロント側バリデーション
      this.error.hendo.cost = this.validCost(this.hendo.cost)
      this.error.hendo.himoku = this.validHimoku(this.hendo.himoku)
      this.error.hendo.comment = this.validComment(this.hendo.comment)

      if (!this.error_flg) {
        const error = await this.submit(this.hendo)
        // エラーがサーバーから帰ってきていた時
        if (error) {
          this.error_flg = true
          this.error.hendo = error
          return false
        }
        // 初期化
        for (let key in this.hendo) {
          if (key === 'user') {
            this.hendo[key] = this.user_info.id
          } else if (key === 'himoku') {
            this.hendo[key] = ""
          } else {
            this.hendo[key] = null
          }
        }
      }
      this.sending = false
    },
    async submit (data) {
      this.error.kotei = {}
      this.error.hendo = {}

      const response = await axios.post('/api/adjustment/register', data)
      if (response.status === UNPROCESSABLE_ENTITY) {
        return response.data.errors
      }
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '支払いを登録しました。')
        this.$store.dispatch('payments/getPayments')
      }
      return false
    },
    validCost (cost) {
      if (!cost) {
        this.error_flg = true
        return '金額は入力必須項目です。'
      }
      if (!cost.match(/^[0-9]+$/)) {
        this.error_flg =true
        return '金額は半角数字で入力してください。'
      }
      if (cost >= 1000000) {
        this.error_flg = true
        return '金額は999,999以内で入力してください。'
      }
      return false
    },
    validComment (comment) {
      // コメントがある時
      if (comment) {
        if (comment.length > 400) {
          this.error_flg = true
          return 'コメントは400文字以内で入力してください'
        }
      }
      return false
    },
    validHimoku (himoku) {
      // 費目が選択されているか確認
      if (!himoku) {
        this.error_flg = true
        return '費目を選択してください。'
      }
      return false
    },
    openKotei () {
      if (this.open_kotei === false) {
        this.open_kotei = !this.open_kotei
        this.open_hendo = !this.open_hendo
        this.error_flg = false
      }
    },
    openHendo () {
      if (this.open_hendo === false) {
        this.open_hendo = !this.open_hendo
        this.open_kotei = !this.open_kotei
        this.error_flg = false
      }
    }
  },
  created () {
    this.$store.dispatch('himoku/getHimoku')
    this.kotei.user = this.user_info.id
    this.hendo.user = this.user_info.id
  }
}
</script>