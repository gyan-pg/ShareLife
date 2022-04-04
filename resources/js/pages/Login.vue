<template>
  <article class="p-wrapper--auth">
    <section class="p-container--auth">
      <h2 class="c-title c-title--head">ログイン</h2>
      <form class="c-form c-form--login" @submit.prevent="login" novalidate="novalidate">
        <label for="email-login" class="c-form__label">Eメール</label>
        <div class="p-container--form-input">
          <input id="email-login" name="email" class="c-form__input" type="text" v-model="loginForm.email">
          <!-- エラー表示 -->
          <div class="p-container--error">
            <div v-if="loginErrors">
              <ul v-if="loginErrors.email">
                <li v-for="msg in loginErrors.email" :key="msg" class="c-text--error">{{ msg }}</li>
              </ul>
            </div>
          </div>
        </div>

        <label for="password-login" class="c-form__label">パスワード</label>
        <div class="p-container--form-input">
          <input id="password-login" name="password" class="c-form__input" type="password" v-model="loginForm.password">
          <!-- エラー表示 -->
          <div class="p-container--error">
            <div v-if="loginErrors">
              <ul v-if="loginErrors.password">
                <li v-for="msg in loginErrors.password" :key="msg" class="c-text--error">{{ msg }}</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="p-container--btn-center">
          <button type="submit" class="c-btn c-btn--submit" :disabled="sending">送信</button>
        </div>
        <div class="p-container--password-forget">
          <router-link to="/forgotpass" class="c-link--sm">パスワードを忘れた場合はこちら！</router-link>
        </div>
      </form>
    </section>
  </article>
</template>

<script>
import { mapState } from 'vuex'
export default {
  data () {
    return {
      loginForm: {
        email: '',
        password: ''
      },
      sending: false
    }
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      loginErrors: state => state.auth.loginErrorMessages
    })
    // apiStatus () {
    //   return this.$store.state.auth.apiStatus
    // },
    // loginErrors () {
    //   return this.$store.state.auth.loginErrorMessages
    // }
  },
  methods: {
    async login () {
      this.sending = true
      await this.$store.dispatch('auth/login', this.loginForm)
      if (this.apiStatus) {
        this.$router.push('/mypage')
      }
      this.sending = false
    },
    clearError () {
      this.$store.commit('auth/setLoginErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
}
</script>