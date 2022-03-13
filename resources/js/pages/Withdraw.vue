<template>
<section class="p-container--withdraw">
  <h1>退会ページ</h1>
  <p>下記ボタンを押すと退会が確定します。</p>
  <p>チームの予定や支払いなどの情報は全て削除されます。</p>
  <button class="c-btn c-btn--submit" @click="submit">退会する</button>
</section>
</template>

<script>
import { OK } from '../util'
export default {
  data () {
    return {}
  },
  methods: {
    async submit () {
      const response = await axios.post('/api/withdraw', this.$store.state.auth.user)
      if (response.status === OK) {
        this.$store.commit('auth/setUser', null)
        this.$store.commit('auth/setTeam', null)
        this.$store.commit('messages/setMessage', '退会処理が完了しました。ご利用ありがとうございました。')
        this.$router.push('/')
      }
    }
  }
}
</script>