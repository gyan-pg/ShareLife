<template>
<section class="p-container--withdraw">
  <h2 class="c-title c-title--head">退会ページ</h2>
  <div class="p-container--withdraw-body">
    <p>下記ボタンを押すと退会が確定します。</p>
    <p>チームの予定や支払いなどの情報は全て削除されます。</p>
    <div class="p-container--btn-center u-mt-m">
      <button class="c-btn c-btn--submit-navy" :class="{'c-btn--disabled': guest}" @click="submit" :disabled="sending || guest">退会する</button>
    </div>
  </div>
</section>
</template>

<script>
import { OK } from '../util'
export default {
  data () {
    return {
      sending: false
    }
  },
  computed: {
    guest () {
      return this.$store.getters['auth/guest']
    }
  },
  methods: {
    async submit () {
      this.sending = true
      const response = await axios.post('/api/withdraw', this.$store.state.auth.user)
      if (response.status === OK) {
        this.$store.commit('auth/setUser', null)
        this.$store.commit('auth/setTeam', null)
        this.$store.commit('messages/setMessage', '退会処理が完了しました。ご利用ありがとうございました。')
        this.$router.push('/')
      } else {
        this.sending = false
        this.$store.commit('messages/setErrorMessage', '退会処理に失敗しました。管理者にご連絡ください。')
      }
    }
  }
}
</script>