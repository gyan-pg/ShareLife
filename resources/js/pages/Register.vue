<template>
  <div class="p-wrapper--auth">
    <section class="p-container--auth">
      <h2 class="c-title c-title--head">ユーザー登録</h2>
      <div class="p-container--notice-register" v-if="notice_flg">
        <div class="c-text--notice u-c-r">この画面は30分間有効です。<br>ブラウザを閉じたり、再読み込みしないでください。</div>
      </div>
      <form class="c-form c-form--login u-pt-s" @submit.prevent="register" novalidate="novalidate">
        <label for="name" class="c-form__label">お名前</label>
        <div class="p-container--form-input">
          <input id="name" class="c-form__input" type="text" v-model="registerForm.name">
          <!-- エラー表示 -->
          <div v-if="registerErrors">
            <ul v-if="registerErrors.name">
              <li v-for="msg in registerErrors.name" :key="msg" class="c-text--error">{{ msg }}</li>
            </ul>
          </div>
        </div>

        <label for="email" class="c-form__label">Eメール</label>
        <p>{{registerForm.email}}</p>
        
        <label for="password" class="c-form__label">パスワード</label>
        <div class="p-container--form-input">
          <input id="password" class="c-form__input" type="password" v-model="registerForm.password">
          <!-- エラー表示 -->
          <div v-if="registerErrors">
            <ul v-if="registerErrors.password">
              <li v-for="msg in registerErrors.password" :key="msg" class="c-text--error">{{ msg }}</li>
            </ul>
          </div>
        </div>
        
        <label for="password-confirmation" class="c-form__label">パスワード(再入力)</label>
        <div class="p-container--form-input">
          <input id="password-confirmation" class="c-form__input" type="password" v-model="registerForm.password_confirmation">
        </div>

        <label for="register-token" class="c-form__label">トークン</label>
        <div class="p-container--form-input">
          <input id="register-token" class="c-form__input" type="text" v-model="token">
        </div>
        <div class="p-container-btn">
          <button type="submit" class="c-btn c-btn--submit" :disabled="sending">送信</button>
        </div>
      </form>
    </section>

  </div>
</template>

<script>
export default {
  data () {
    return {
      registerForm: {
        email: '',
        password: '',
        password_confirmation: ''
      },
      notice_flg: true,
      token: null,
      sending: false
    }
  },
  computed: {
    registerErrors () {
      return this.$store.state.auth.registerErrorMessages
    },
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
  methods: {
    async register () {
      this.sending = true
      if (this.token !== this.$store.getters['auth/checkToken'].token) {
        this.$store.commit('messages/setErrorMessage', 'トークンの値に誤りがあります。')
        this.sending = false
        return false
      }

      if (this.$store.getters['auth/checkToken']) {
        
        await this.$store.dispatch('auth/register', this.registerForm)
        
        if (this.apiStatus){
          this.$store.commit('auth/setRegisterToken', null)
          this.$router.push('/mypage')
        }
      // トークンの有効期限が切れていた場合
      } else {
        this.$store.commit('messages/setErrorMessage', 'トークン有効期限が切れました。もう一度登録をメール送信してください。')
        this.$router.push('/preRegister')
      }
    },
    clearError () {
      this.$store.commit('auth/setRegisterErrorMessages', null)
    },
    closeNotice () {
      this.notice_flg = false
    }
  },
  created () {
    this.clearError()
    this.registerForm.email = this.$store.state.auth.registerUser.email
  }
}
</script>