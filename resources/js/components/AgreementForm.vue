<template>
  <div class="p-wrapper--auth">

    <form class="c-form" @submit.prevent="register">
      <label for="agreement-title">title</label>
      <div class="p-container--form-input">
        <input id="agreement-title" class="c-form__input" v-model="agreement.title">
        <div v-if="errors">
          <ul v-if="errors.title">
            <li v-for="msg in errors.title" :key="msg" class="c-text--error">{{ msg }}</li>
          </ul>
        </div>
      </div>

      <label for="agreement-content">content</label>
      <div class="p-container--form-input">
        <textarea id="agreement-content" class="c-form__textarea" v-model="agreement.content"></textarea>
        <div v-if="errors">
          <ul v-if="errors.content">
            <li v-for="msg in errors.content" :key="msg" class="c-text--error">{{ msg }}</li>
          </ul>
        </div>
      </div>
      <button class="c-btn c-btn--submit" type="submit">submit</button>
      <p v-if="errors" class="c-text--error">{{ errors.server }}</p>
    </form>
  </div>
</template>

<script>
import { OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  data () {
    return {
      agreement: {
        title: '',
        content: '',
        user_id: 1,
        team_id: 1
      },
      errors: null
    }
  },
  methods: {
    async register () {
      this.clearError()
      const response = await axios.post('/api/agreement/register', this.agreement)
      console.log(response)
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '登録完了しました。')
        this.agreement.title = ''
        this.agreement.content = ''
        return false
      } else if (response.status === UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      this.errors = {
        server: 'サーバーでエラーが発生しました。'
      }
      
      this.$store.commit('error/setCode', response.status)

    },
    clearError () {
      this.errors = null
    }
  }
}
</script>