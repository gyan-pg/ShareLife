<template>
  <article class="p-wrapper--auth">
    <section class="p-container--auth">
      <h2 class="c-title c-title--head">仮会員登録</h2>
      <form class="c-form c-form--login" @submit.prevent="submit" novalidate="novalidate">
        <p class="c-text--notice">確認用のEメールをお送りいたします。下記フォームに使用可能なメールアドレスを入力してください。</p>
        <p class="c-text--notice">※一部ご利用いただけないメールアドレスがあります。</p>
        <label for="email" class="c-form__label">Eメール</label>
        <div class="p-container--form-input">
          <input id="email" class="c-form__input" type="email" v-model="preRegisterForm.email">
          <!-- エラー表示 -->
          <div class="p-container--error">
            <div v-if="error">
              <span v-for="(err, index) in error" :key="index" class="c-text--error">{{ err }}</span>
            </div>
            <span v-if="sending" class="c-text--sending">メールを送信しています。</span>
          </div>
        </div>
        <div class="p-container--btn-center">
          <button type="submit" class="c-btn c-btn--submit" :disabled="sending">送信</button>
        </div>
      </form>
    </section>
  </article>
</template>

<script>
import { EMAIL_PATTERN, OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  data () {
    return {
      preRegisterForm: {
        email: ''
      },
      error: null,
      sending: false
    }
  },
  computed: {

  },
  methods: {
    validEmail () {
      if (!this.preRegisterForm.email) {
        this.error = '入力をお願いします。'
        this.sending = false
        return false
      }
      // chromeでは全角文字を入力すると、xn--などと変換されることがあるため、その対策
      if (!EMAIL_PATTERN.test(this.preRegisterForm.email) || this.preRegisterForm.email.match('xn--')) {
        this.error = 'ご利用いただけないアドレスです。'
        this.sending = false
        return false
      }
    },
    async submit () {
      this.error = null
      this.sending = true
      // フォームバリデーション
      this.validEmail()

      if (!this.error) {
        const response = await axios.post('/api/preRegister', this.preRegisterForm)

        if (response.status === OK) {
          
          let registerUser = {}
          registerUser.token = response.data.register_token
          registerUser.email = this.preRegisterForm.email

          this.$store.commit('auth/setRegisterToken' ,registerUser)
          this.$router.push('/register')
        }

        if (response.status === UNPROCESSABLE_ENTITY) {
          this.error = response.data.errors.email
          this.sending = false
          return false
        }
      }
    }
  }
}
</script>