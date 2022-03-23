<template>
  <div>
    <span class="c-text--number-orange">No.{{ index+1 }}</span>
    <span>{{ agree.title }}</span>
    <div class="p-container--agreements-detail">
      <span v-if="agree.content" class="material-icons c-icon--adjustment-comment" @click="openContent" v-click-outside="closeContent">chat</span>
      <transition name="slideup">
        <div class="c-popup c-popup--agreement" v-if="open_flg">{{ agree.content }}</div>
      </transition>
    </div>
    <span v-if="agree.user_id === myId" class="material-icons c-icon--agreement-delete" @click="deleteAgreement">delete</span>
    <span v-if="agree.user_id === myId" class="material-icons c-icon--agreement-delete" @click="editAgreement(agree)">edit</span>
    
    <!-- パートナーから決め事を拒否された時用メッセージ -->
    <span v-if="agree.approval === 'deny'">パートナーに否認されました。内容を修正しましょう！</span>
    <!-- パートナーが作った決め事で、まだ承認されていない時。 -->
    <div v-if="agree.user_id !== myId">
      <button type="button" class="c-btn" @click="approveAgreement(agree.id)">承認する</button>
      <button type="button" class="c-btn" @click="denyAgreement(agree.id)">承認しない</button>
    </div>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import { OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  props: {
    index: {
      type: Number,
    },
    agree: {
      type: Object,
    }
  },
  data () {
    return {
      open_flg: false
    }
  },
  computed: {
    myId () {
      return this.$store.state.auth.user.id
    }
  },
  methods: {
    async deleteAgreement () {
      const response = await axios.delete(`/api/agreement/${this.agree.id}`)
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '削除完了しました。')
        this.$store.dispatch('agreements/getAgreements')
      }
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.$store.commit('messages/setErrorMessage', 'サーバーでエラーが発生しました。')
      }
    },
    async approveAgreement (id) {
      const response = await axios.put(`/api/agreement/approve`, {'id': id, 'team_id': this.$store.state.auth.team.id})
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '承認しました。')
        this.$store.dispatch('agreements/getAgreements')
      }
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.$store.commit('messages/setErrorMessage', 'サーバーでエラーが発生しました。')
      }
    },
    async denyAgreement (id) {
      const response = await axios.put(`/api/agreement/deny`, {'id': id, 'team_id': this.$store.state.auth.team.id})
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '拒否しました。')
        this.$store.dispatch('agreements/getAgreements')
      }
      if (response.status === UNPROCESSABLE_ENTITY) {
        this.$store.commit('messages/setErrorMessage', 'サーバーでエラーが発生しました。')
      }
    },
    editAgreement (agree) {
      this.$emit('editAgreement', agree)
    },
    openContent () {
      this.open_flg = !this.open_flg
    },
    closeContent () {
      this.open_flg = false
    }
  },
  directives: {
    ClickOutside,
  }
}
</script>