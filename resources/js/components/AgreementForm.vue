<template>
  <section class="p-wrapper--modal" @click.self="closeForm">

    <div class="p-wrapper--agreement-form">
      <form class="c-form c-form--event" @submit.prevent="register">
        <div class="p-container--form-input">
          <input id="agreement-title" class="c-form__input--title" placeholder="タイトル" v-model="agreement.title">
          <div class="p-container--input-notice">
            <ul v-if="errors">
              <li v-for="msg in errors.title" :key="msg" class="c-text--error">{{ msg }}</li>
            </ul>
          </div>
        </div>

        <div class="p-container--form-input">
          <div class="p-container--form-row">
            <div class="p-container--form-left">
              <label for="agreement-detail" class="c-form__label--event"><span class="material-icons c-icon--form-item">notes</span></label>
            </div>
            <div class="p-container--form-right">
              <input id="agreement-detail" class="c-form__input c-form__input--event" v-model="agreement.content">
            </div>
          </div>
          <div class="p-container--input-notice">
            <ul v-if="errors">
              <li v-for="msg in errors.content" :key="msg" class="c-text--error">{{ msg }}</li>
            </ul>
          </div>
        </div>

        <div class="p-container--btn-right">
          <button class="c-btn c-btn--submit" type="submit">保存</button>
          <button class="c-btn c-btn--submit" type="button" @click="closeForm" :disabled="sending">閉じる</button>
        </div>
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
      sending: false
    }
  },
  methods: {
    async register () {
      this.clearError()
      this.sending = true
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
      this.sending = false
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
    }
  }
}
</script>