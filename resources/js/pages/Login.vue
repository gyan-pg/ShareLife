<template>
  <div class="p-wrapper--auth">
    <ul class="c-auth-nav">
      <li class="c-auth-nav__item"><router-link to="/login">ログイン</router-link></li>
      <li class="c-auth-nav__item"><router-link to="/register">ユーザー登録</router-link></li>
    </ul>
    <form class="c-form c-form--login" @submit.prevent="login" novalidate="novalidate">
      <label for="email-login" class="c-form__label">Eメール</label>
      <div class="p-container--form-input">
        <input id="email-login" name="email" class="c-form__input" type="text" v-model="loginForm.email">
        <!-- エラー表示 -->
        <div v-if="loginErrors">
          <ul v-if="loginErrors.email">
            <li v-for="msg in loginErrors.email" :key="msg" class="c-text--error">{{ msg }}</li>
          </ul>
        </div>
      </div>

      <label for="password-login" class="c-form__label">パスワード</label>
      <div class="p-container--form-input">
        <input id="password-login" name="password" class="c-form__input" type="password" v-model="loginForm.password">
        <!-- エラー表示 -->
        <div v-if="loginErrors">
          <ul v-if="loginErrors.password">
            <li v-for="msg in loginErrors.password" :key="msg" class="c-text--error">{{ msg }}</li>
          </ul>
        </div>
      </div>

      <div class="p-container-btn">
        <button type="submit" class="c-btn c-btn--submit">送信</button>
      </div>
    </form>
    <button class="c-btn" @click="testMsg">message test</button>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  data () {
    return {
      loginForm: {
        email: '',
        password: ''
      }
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
    testMsg () {
      this.$store.commit('messages/setMessage', 'testメッセージです。')
    },
    async login () {
      await this.$store.dispatch('auth/login', this.loginForm)

      if (this.apiStatus) {
        this.$router.push('/mypage')
      }
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