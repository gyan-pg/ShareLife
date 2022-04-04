<template>
  <article class="p-wrapper--auth">
    <section class="p-container--auth">
      <h2 class="c-title c-title--head">パスワードの再設定</h2>
      <form class="c-form c-form--login" @submit.prevent="resetPassword" novalidate="novalidate">
        <p class="c-text--notice u-mb-m">
          ご登録のEメールアドレスを入力してください。パスワード再設定用のEメールをお送りいたします。
        </p>
        <label for="email-login" class="c-form__label">Eメール</label>
        <div class="p-container--form-input">
          <input id="email-login" name="email" class="c-form__input" type="text" v-model="resetForm.email">
          <!-- エラー表示 -->
          <div class="p-container--error">
            <div v-if="resetErrors">
              <ul v-if="resetErrors.email">
                <li v-for="msg in resetErrors.email" :key="msg" class="c-text--error">{{ msg }}</li>
              </ul>
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
import { mapState } from 'vuex'
export default {
  data () {
    return {
      resetForm: {
        email: '',
      },
      sending: false
    }
  },
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus,
      resetErrors: state => state.auth.resetErrorMessages
    })
    // apiStatus () {
    //   return this.$store.state.auth.apiStatus
    // },
    // loginErrors () {
    //   return this.$store.state.auth.loginErrorMessages
    // }
  },
  methods: {
    async resetPassword () {
      this.clearError()
      this.sending = true
      await this.$store.dispatch('auth/resetPassword', this.resetForm)
      
      if (this.apiStatus) {
        this.$router.push('/resetemailsend')
      }

      this.sending = false
    },
    clearError () {
      this.$store.commit('auth/setResetErrorMessages', null)
    }
  },
  created () {
    this.clearError()
  }
}
</script>