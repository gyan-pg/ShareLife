<template>
<div>
  <span>No.{{ index+1 }}</span>
  <span>{{ agree.title }}</span>
  <i v-if="agree.content" class="fa-solid fa-arrow-up-right-from-square" @click="openContent"></i>
  <button v-if="agree.user_id === myId" type="button" class="c-btn" @click="deleteAgreement(agree.id)">削除</button>
  <button v-if="agree.user_id === myId" type="button" class="c-btn" @click="editAgreement(agree)">編集</button>
  <!-- パートナーから決め事を拒否された時用メッセージ -->
  <span v-if="agree.approval === 'deny'">パートナーに否認されました。内容を修正しましょう！</span>
  <!-- パートナーが作った決め事で、まだ承認されていない時。 -->
  <div v-if="agree.user_id !== myId">
    <button type="button" class="c-btn" @click="approveAgreement(agree.id)">承認する</button>
    <button type="button" class="c-btn" @click="denyAgreement(agree.id)">承認しない</button>
  </div>
  
  <transition name="fade">
    <div v-if="open_flg">{{ agree.content }}</div>
  </transition>
</div>
</template>

<script>
import { OK } from '../util'
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
    async deleteAgreement (id) {
      const response = await axios.delete(`/api/agreement/${id}`)
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '削除完了しました。')
        this.$store.dispatch('agreements/getAgreements')
      }
    },
    async approveAgreement (id) {
      const response = await axios.put(`/api/agreement/approve`, {'id': id, 'team_id': this.$store.state.auth.team.id})
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '承認しました。')
        this.$store.dispatch('agreements/getAgreements')
      }
    },
    async denyAgreement (id) {
      const response = await axios.put(`/api/agreement/deny`, {'id': id, 'team_id': this.$store.state.auth.team.id})
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', '拒否しました。')
        this.$store.dispatch('agreements/getAgreements')
      }
    },
    editAgreement (agree) {
      this.$emit('editAgreement', agree)
    },
    openContent () {
      this.open_flg = !this.open_flg
    }
  }
}
</script>