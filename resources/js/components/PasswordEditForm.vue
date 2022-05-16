<template>
<section class="p-wrapper--modal" @click.self="closePassEdit">
    <div class="p-wrapper--agreement-form">
      <h2 class="c-title c-title--password-change">パスワード変更</h2>
      <form class="c-form" @submit.prevent="submit">
        <div class="p-container--form-row">
          <div class="p-container--form-row__left-40">
            <label for="name" class="c-form__label c-form__label--password">パスワード</label>
          </div>
          <div class="p-container--form-row__right-60">
            <input id="name" class="c-form__input c-form__input--underline" :class="{'c-form__input--disable': guest}" :disabled="guest" type="password" v-model="formData.password" placeholder="8文字以上半角">
          </div>
        </div>
        <!-- エラー表示 -->
        <div class="p-container--error">
          <div v-if="errors">
            <ul v-if="errors.password">
              <li v-for="msg in errors.password" :key="msg" class="c-text--error u-tr">{{ msg }}</li>
            </ul>
          </div>
        </div>
      

        <div class="p-container--form-row">
          <div class="p-container--form-row__left-40">
            <label for="password" class="c-form__label c-form__label--password">パスワード(再入力)</label>
          </div>
          <div class="p-container--form-row__right-60">
            <input id="password" class="c-form__input c-form__input--underline" :class="{'c-form__input--disable': guest}" :disabled="guest" type="password" v-model="formData.password_confirmation">
          </div>
          <!-- エラー表示 -->
        </div>
        <div class="p-container--error">
          <div v-if="errors">
            <ul v-if="errors.password_confirmation">
              <li v-for="msg in errors.password_confirm" :key="msg" class="c-text--error u-tr">{{ msg }}</li>
            </ul>
          </div>
        </div>

        <div class="p-container--btn-right">
          <button class="c-btn c-btn--submit" :class="{'c-btn--disabled': guest}" type="submit" :disabled="sending || guest">保存する</button>
          <button class="c-btn c-btn--submit" type="button" @click="closePassEdit">閉じる</button>
        </div>
      </form>
    </div>
  </section>
</template>

<script>
export default {
  data () {
    return {
      formData: {
        password: null,
        password_confirmation: null
      },
      sending: false
    }
  },
  computed: {
    errors () {
      return this.$store.state.auth.passwordChangeErrorMessages
    },
    apiStatus () {
      return this.$store.state.auth.apiStatus
    },
    guest () {
      return this.$store.getters['auth/guest']
    }
  },
  methods: {
    closePassEdit () {
      this.$emit('closePassEdit')
    },
    async submit () {
      this.sending = true
      await this.$store.dispatch('auth/passwordChange', this.formData)
      if (this.apiStatus) {
        this.$store.commit('messages/setMessage', 'パスワードを変更しました。')
        this.closePassEdit()
      }
      this.sending = false
    }
  },
  created () {
    this.$store.commit('auth/setPasswordChangeErrorMessages', null)
  }
}
</script>