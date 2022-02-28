<template>
  <div>
    Mypage
    <Calendar />
    <router-link to="/agreement" class="c-link--router">agree</router-link>
    <!-- パートナーの招待関係 component化する？ -->
    <div v-if="team">
      <!-- 承認待ち表示 -->
      <!-- 承認確認を送った方 owner -->
      <p v-if="team.status === 'unapproved' && teamRole">パートナーの承認を待っています。</p>
      <!-- 両方承認済みの時 -->
      <p v-else-if="team.status === 'approve'">チーム承認されました。</p>
      <!-- 承認確認を送られた方 partner user -->
      <div v-else>
        <p>{{ owner.name }}({{ owner.email }})さんから招待が届いています。</p>
        <p>承認しますか？</p>
        <button class="c-btn c-btn--submit" @click="approve">ok</button><button class="c-btn c-btn--submit" @click="deny">no</button>
      </div>
    </div>
    <div v-if="!team">
      <form class="c-form" @submit.prevent="invitation">
        <label for="formData.email">パートナーを招待する</label>
        <input id="formData.email" class="c-form__input" v-model="formData.email">
        <p v-if="error" class="c-text--error">{{ error.email }}</p>
        <button class="c-btn c-btn--submit">送信</button>
      </form>
    </div>
    <!-- パートナーの招待関係終了 -->
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
      }
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
    owner () {
      return this.$store.state.auth.owner
    },
    teamRole () {
      return this.$store.getters['auth/teamOwner']
    }
  },
  methods: {
    // パートナーを招待する
    async invitation () {
      const result = this.validEmail()
      if (result){
        const response = await axios.post('/api/team/invitation', this.formData)
        console.log(response)

        if (response.status === UNPROCESSABLE_ENTITY) {
          this.error.email = response.data.errors.email[0]
          return false
        }

        this.$store.commit('auth/setTeam', response.data[0])
        this.$store.commit('auth/setOwner', response.data[1])
        this.$store.commit('auth/setPartner', response.data[2])
        this.$store.commit('messages/setMessage', 'パートナーを招待しました。')
      }
    },
    async approve () {
      const response = await axios.put('/api/team/invitation', this.$store.state.auth.team)
      console.log(response)
      if (response.status === OK) {
        this.$store.commit('auth/setTeamStatus', response.data)
        return false
      }

      this.$store.commit('messages/setMessage', 'エラーが発生しました。')
    },
    async deny () {
      const response = await axios.delete('/api/team/invitation')
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