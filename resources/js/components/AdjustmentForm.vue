<template>
  <div>
    <p>固定費入力フォーム<i class="fa-regular fa-plus" @click="openKotei"></i></p>
    <div v-if="open_kotei">
      <p>金額</p>
      <input class="c-form__input" type="text" v-model="kotei.cost">
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <!-- サーバーからのエラー文 -->
        <span class="c-text--error" v-for="(error, index) in error.kotei.cost" :key="index">{{ error }}</span>
      </div>

      <p>コメント</p>
      <input class="c-form__input" type="text" v-model="kotei.comment">
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <span class="c-text--error" v-for="(error, index) in error.kotei.comment" :key="index">{{ error }}</span>
      </div>

      <!-- 費目 -->
      <p>費目</p>
      <select class="c-form__select" v-model="kotei.himoku">
        <option value hidden>選択してください</option>
        <option v-for="(himoku, index) in kotei_list" :key="index" :value="himoku">{{ himoku }}</option>
      </select>
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <span class="c-text--error" v-for="(error, index) in error.kotei.himoku" :key="index">{{ error }}</span>
      </div>
      
      <p>支払った人</p>
      <select class="c-form__select" v-model="kotei.user">
        <option :value="user_info.id" selected>{{ user_info.name }}</option>
        <option :value="partner_info.id">{{ partner_info.name }}</option>
      </select>
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <span class="c-text--error" v-for="(error, index) in error.kotei.user" :key="index">{{ error }}</span>
      </div>

      <button type="button" class="c-btn c-btn--submit" @click="submitKotei">登録</button>
    </div>

    <p>変動費入力フォーム<i class="fa-regular fa-plus" @click="openHendo"></i></p>
    <div v-if="open_hendo">
      食費、交遊費、その他
      <p>金額</p>
      <input class="c-form__input" type="text" v-model="hendo.cost">
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <!-- サーバーからのエラー文 -->
        <span class="c-text--error" v-for="(error, index) in error.hendo.cost" :key="index">{{ error }}</span>
      </div>
      <p>コメント</p>
      <input class="c-form__input" type="text" v-model="hendo.comment">
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <span class="c-text--error" v-for="(error, index) in error.hendo.comment" :key="index">{{ error }}</span>
      </div>

      <select class="c-form__select" v-model="hendo.himoku">
        <option value hidden>選択してください</option>
        <option v-for="(himoku, index) in hendo_list" :key="index" :value="himoku">{{ himoku }}</option>
      </select>
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <!-- サーバーからのエラー文 -->
        <span class="c-text--error" v-for="(error, index) in error.hendo.himoku" :key="index">{{ error }}</span>
      </div>

      <p>支払った人</p>
      <select class="c-form__select" v-model="hendo.user">
        <option :value="user_info.id" selected>{{ user_info.name }}</option>
        <option :value="partner_info.id">{{ partner_info.name }}</option>
      </select>
      <!-- エラー表示 -->
      <div v-if="error_flg">
        <span class="c-text--error" v-for="(error, index) in error.hendo.user" :key="index">{{ error }}</span>
      </div>

      <button type="button" class="c-btn c-btn--submit" @click="submitHendo">登録</button>
    </div>
  </div>
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
      open_hendo: false
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
        this.open_kotei = false
      }
    },
    async submitHendo () {
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
        this.open_hendo = false
      }
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
        return comment.length < 400 ? 'コメントは400文字以内で入力してください' : ''
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
      this.open_kotei = !this.open_kotei
      this.error_flg = false
    },
    openHendo () {
      this.open_hendo = !this.open_hendo
      this.error_flg = false
    }
  },
  created () {
    this.$store.dispatch('himoku/getHimoku')
    this.kotei.user = this.user_info.id
    this.hendo.user = this.user_info.id
  }
}
</script>