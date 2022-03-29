<template>
  <div class="p-container--adjustment-result">
    <div class="p-container--adjustment-result__left">
      {{ item.himoku }}<span v-if="item.comment" @click="openComment" v-click-outside="closeComment" class="material-icons c-icon--adjustment-comment">chat</span>
      <transition name="slideup">
        <div v-if="comment_flg" class="c-popup">{{item.comment}}</div>
      </transition>
    </div>
    <div class="p-container--adjustment-result__left">{{ item.cost }}円</div>
    <div class="p-container--adjustment-result__right">
      <img class="c-image c-image--adjustment-sumbnail" :src="image">
    </div>
    <span class="material-icons c-icon--adjustment-delete" @click="deletePayment(item.id)">delete</span>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import { NUMBER_PATTERN, OK, UNPROCESSABLE_ENTITY } from '../util'
export default {
  props: {
    item: {
      type: Object,
      required: true
    },
    image: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      comment_flg: false
    }
  },
  methods: {
    openComment () {
      this.comment_flg = !this.comment_flg
    },
    closeComment () {
      this.comment_flg = false
    },
    async deletePayment (id) {
      // idのバリデーション
      if (!NUMBER_PATTERN.test(id)) {
        this.$store.commit('messages/setErrorMessage', 'エラーが発生しました。')
        return false
      }
      const response = await axios.delete(`/api/adjustment/delete/${id}`)
      if (response.status === OK) {
        this.$store.commit('messages/setMessage', 'レコードを削除しました。')
        this.$store.dispatch('payments/getPayments')
      } else if (response.data === UNPROCESSABLE_ENTITY) {
        this.$store.commit('messages/setErrorMessage', 'エラーが発生しました。')
      }
    },
  },
  directives: {
    ClickOutside,
  }
}
</script>