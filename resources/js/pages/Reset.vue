<template>
<div class="p-wrapper--auth">
    <section class="p-container--auth">
      <h2 class="c-title c-title--head">パスワード再設定</h2>
      <form class="c-form c-form--login u-pt-s" @submit.prevent="resetting" novalidate="novalidate">
        <label for="name" class="c-form__label">パスワード</label>
        <div class="p-container--form-input">
          <input id="name" class="c-form__input" type="password" v-model="formData.password">
          <!-- エラー表示 -->
          <div class="p-container--error">
            <div v-if="resettingErrors">
              <ul v-if="resettingErrors.password">
                <li v-for="msg in resettingErrors.password" :key="msg" class="c-text--error">{{ msg }}</li>
              </ul>
            </div>
          </div>
        </div>
        
        <label for="password" class="c-form__label">パスワード(再入力)</label>
        <div class="p-container--form-input">
          <input id="password" class="c-form__input" type="password" v-model="formData.password_confirmation">
          <!-- エラー表示 -->
          <div class="p-container--error">
            <div v-if="resettingErrors">
              <ul v-if="resettingErrors.password_confirm">
                <li v-for="msg in resettingErrors.password_confirm" :key="msg" class="c-text--error">{{ msg }}</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="p-container--btn-center">
          <button type="submit" class="c-btn c-btn--submit" :disabled="sending">送信</button>
        </div>
      </form>
    </section>

  </div>
</template>

<script>
export default{
  data () {
    return {
      formData: {
        password: null,
        password_confirmation: null,
        token: null
      },
      sending: false
    }
  },
  computed: {
    tokenCheck () {
      return this.$store.state.auth.tokenStatus
    },
    resettingErrors () {
      return this.$store.state.auth.resettingErrorMessages
    },
    apiStatus () {
      return this.$store.state.auth.apiStatus
    }
  },
  methods: {
    async resetting () {
      await this.$store.dispatch('auth/passwordResetting', this.formData)
      if (this.apiStatus) {
        this.$router.push('/mypage')
      }
    }
  },
  created () {
    this.formData.token = this.$route.params.token
    this.$store.dispatch('auth/tokenCheck', this.formData)
  },
  watch: {
    tokenCheck (newValue) {
      if (newValue === "expired") {
        this.$store.commit('messages/setErrorMessage', 'トークンの有効期限が切れました。')
        this.$router.push('/forgotpass')
      } else if (newValue === 'ng') {
        this.$store.commit('messages/setErrorMessage', 'エラーが発生しました。再度手続きをお願いします。')
        this.$router.push('/forgotpass')
      } else {
        return false
      }
    }
  }
}
</script>