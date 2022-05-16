<template>
  <article class="p-wrapper--auth">
    <section class="p-container--auth">
      <h2 class="c-title c-title--head">ゲストログイン</h2>
      <div class="c-text c-text--guest">ゲストユーザーとしてログインできます。</div>
      <form class="c-form c-form--login">
        <div class="p-container--guest-login">
          <button type="button" class="c-btn--guest-login" @click="loginAsGuest1">guest1</button>
          <button type="button" class="c-btn--guest-login" @click="loginAsGuest2">guest2</button>
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
    },
    loginAsGuest1 () {
      this.loginForm.email = 'guest1@guest__1.com'
      this.loginForm.password = 'password'
      this.login()
    },
    loginAsGuest2 () {
      this.loginForm.email = 'guest2@guest__2.com'
      this.loginForm.password = 'password'
      this.login()
    }
  },
  created () {
    this.clearError()
  }
}
</script>