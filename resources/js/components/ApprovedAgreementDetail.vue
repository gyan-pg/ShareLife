<template>
  <div>
    <div>
      <span class="c-text--number">No.{{ index+1 }}</span>
      <div class="p-container--agreements-detail">
        <span v-if="agree.content" class="material-icons c-icon--adjustment-comment" @click="openContent" v-click-outside="closeContent">chat</span>
        <span class="material-icons c-icon--agreement-delete" @click="deleteAgreement">delete</span>
        <transition name="slideup">
          <div class="c-popup c-popup--agreement" v-if="open_flg">{{ agree.content }}</div>
        </transition>
      </div>
    </div>

    <span class="c-text--content">{{ agree.title }}</span>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import { OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  props: {
    index: {
      type: Number
    },
    agree: {
      type: Object
    }
  },
  data () {
    return {
      open_flg: false
    }
  },
  methods: {
    openContent () {
      this.open_flg = !this.open_flg
    },
    closeContent () {
      this.open_flg = false
    },
    async deleteAgreement () {
      const result = confirm('このルールを消去しますか？')
      console.log(this.agree.id)
      if (result) {
        const response = await axios.delete(`/api/agreement/${this.agree.id}`)
        if (response.status === OK) {
          this.$store.commit('messages/setMessage', 'ルールを削除しました')
          this.$store.dispatch('agreements/getAgreements')
        }
        if (response.status === UNPROCESSABLE_ENTITY) {
          this.$store.commit('messages/setErrorMessage', 'サーバーでエラーが発生しました。')
        }
      }
    }
  },
  directives: {
    ClickOutside,
  },
  created () {
    console.log(this.agree)
  }
}
</script>