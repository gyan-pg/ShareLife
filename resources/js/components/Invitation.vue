<template>
  <div class="p-wrapper--invitation">
    <div v-if="team">
      <!-- 招待を送った人に表示 -->
      <div class="p-container--pending" v-if="team.status === 'unapproved' && teamOwner">
        <h2 class="c-title c-title--pending">パートナーに招待を送りました!</h2>
        <div class="p-container--notice">
          <div class="u-tc">
            <p class="c-title--wait u-tc">pleese wait...</p>
          </div>
          <p class="c-text--notice">パートナーの承認を待っています。パートナーが承認、または否認するとこの画面は表示されなくなります。</p>
        </div>
        <div class="p-container--wait">
          <img class="c-image c-image--wait" src="/storage/images/coffee.jpeg" alt="ちょっと待ってねの画像">
        </div>
      </div>
      <!-- 招待を受け取った人に表示 -->
      <div class="p-container--pending" v-else-if="!teamOwner && team.status !== 'approve'">
        <h2 class="c-title c-title--pending">パートナーから招待が届きました！</h2>
        <div class="p-container--notice">
          <p class="c-text--notice">{{ owner.name }}({{ owner.email }})さんから招待が届いています。</p>
          <p class="c-text--notice">承認しますか？</p>
          <div class="p-container--btn-center u-mt-m">
            <button class="c-btn c-btn--submit u-mr-s" @click="approve">承認</button>
            <button class="c-btn c-btn--submit u-ml-s" @click="deny">否認</button>
          </div>
        </div>
      </div>
    </div>
    <!-- パートナーに招待を送るフォーム -->
    <section class="p-container--invitation" v-if="!team">
      <h2 class="c-title c-title--head">チームを結成しましょう！</h2>
      <form class="c-form" @submit.prevent="invitation">
        <label for="formData.email">パートナーを招待する</label>
        <input id="formData.email" class="c-form__input" v-model="formData.email" placeholder="お相手のEメールアドレスを入力してください。">
        <div class="p-container--error">
          <p v-if="error" class="c-text--error">{{ error.email }}</p>
          <span v-if="sending" class="c-text--sending">メールを送信しています。</span>
        </div>
        <div class="p-container--btn-center">
          <button class="c-btn c-btn--submit" :disabled="sending">送信</button>
        </div>
      </form>
    </section>

  </div>
</template>

<script>
import { OK, EMAIL_PATTERN, UNPROCESSABLE_ENTITY } from '../util'
import Calendar from '../components/Calendar.vue'
import EventForm from '../components/EventForm.vue'
export default {
  data () {
    return {
      formData: {
        email: ''
      },
      error: {
        email: null
      },
      sending: false
    }
  },
  components: {
    Calendar,
    EventForm
  },
  computed: {
    team () {
      return this.$store.state.auth.team
    },
    checkTeam () {
      return this.$store.getters['auth/checkTeam']
    },
    owner () {
      return this.$store.state.auth.owner
    },
    teamOwner () {
      return this.$store.getters['auth/teamOwner']
    }
  },
  methods: {
    // パートナーを招待する
    async invitation () {
      const result = this.validEmail()
      
      if (result){
        this.sending = true
        const response = await axios.post('/api/team/invitation', this.formData)

        if (response.status === UNPROCESSABLE_ENTITY) {
          this.error.email = response.data.errors.email[0]
          this.sending = false
          return false
        }

        if (response.status === OK) {
          this.sending = false
          this.$store.commit('auth/setTeam', response.data[0])
          this.$store.commit('auth/setOwner', response.data[1])
          this.$store.commit('auth/setTeamMember', response.data[2])
          this.$store.commit('messages/setMessage', 'パートナーを招待しました。')
        }
      }
    },
    async approve () {
      this.sending = true
      const response = await axios.put('/api/team/invitation', this.$store.state.auth.team)
      if (response.status === OK) {
        this.$store.commit('auth/setTeamStatus', response.data)
        this.$store.commit('messages/setMessage', 'チームを結成しました。')
        this.sending = false
        return false
      }

      this.$store.commit('messages/setErrorMessage', 'エラーが発生しました。')
      this.sending = false
    },
    async deny () {
      this.sending = true
      const response = await axios.delete('/api/team/invitation', )
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', 'チーム招待を拒否しました。')
        this.$store.commit('auth/setTeam', null)
        this.$store.commit('auth/setTeamStatus', null)
        this.$store.commit('auth/setOwner', null)
        this.$store.commit('auth/setTeamMember', null)
        this.sending = false
        return false
      }

      this.$store.commit('messages/setErrorMessage', 'エラーが発生しました。')
      this.sending = false
    },
    validEmail () {
      this.clearErrors()
      let result
      // 空入力チェック
      result = this.formData.email.length
      if (!result) {
        this.error.email = '入力必須項目です。'
        return false
      }

      // Eメール形式チェック
      result = EMAIL_PATTERN.test(this.formData.email)
      if (!result) {
        this.error.email = 'Eメールの形式で入力してください。'
        return false
      }
      return true
    },
    clearErrors () {
      this.error.email = null
    }
  }
}
</script>