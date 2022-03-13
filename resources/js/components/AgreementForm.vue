<template>
  <section class="p-wrapper--modal" @click.self="closeForm">

    <div class="p-wrapper--agreement-form">
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
        <button class="c-btn c-btn--submit" type="submit">{{ submitMsg }}</button>
        <p v-if="errors" class="c-text--error">{{ errors.server }}</p>
      </form>
    </div>
  </section>
</template>

<script>
import { OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  props: {
    editAgreementData: {
      type: Object
    },
    editFlg: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      agreement: {
        id: null,
        title: null,
        content: '',
        team_id: null,
        user_id: null
      },
      errors: null,
      submitMsg: '登録する'
    }
  },
  methods: {
    async register () {
      this.clearError()
      // 新規登録の時
      if (!this.editFlg) {
        const response = await axios.post('/api/agreement/register', this.agreement)
        if (response.status === OK) {
          this.$store.commit('messages/setMessage', '登録完了しました。')
          this.agreement.title = ''
          this.agreement.content = ''
          this.$store.dispatch('agreements/getAgreements')
          this.closeForm()
          return false
        } else if (response.status === UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors
          return false
        }

        this.errors = {
          server: 'サーバーでエラーが発生しました。'
        }        
        this.$store.commit('error/setCode', response.status)
      
      // 登録した決め事を編集するとき
      } else {
        const response = await axios.put('/api/agreement/update', this.agreement)
        if (response.status === OK) {
          this.$store.commit('messages/setMessage', '登録完了しました。')
          this.agreement.title = ''
          this.agreement.content = ''
          this.$store.dispatch('agreements/getAgreements')
          this.closeForm()
          return false
        } else if (response.status === UNPROCESSABLE_ENTITY) {
          this.errors = response.data.errors
          return false
        }
      }
    },
    closeForm () {
      this.$emit('closeForm')
    },
    clearError () {
      this.errors = null
    }
  },
  created () {
    this.agreement.team_id = this.$store.state.auth.team.id
    if (this.editFlg) {
      this.agreement.title = this.editAgreementData.title
      this.agreement.content = this.editAgreementData.content
      this.agreement.id = this.editAgreementData.id
      this.agreement.user_id = this.editAgreementData.user_id
      this.submitMsg = '保存する'
    }
  }
}
</script>