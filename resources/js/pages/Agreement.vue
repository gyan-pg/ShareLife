<template>
  <section class="p-container--agreements">
    <AgreementList @editAgreement="editAgreement"/>
    <transition name="fade">
      <AgreementForm v-if="showForm" @closeForm="formControll" :editAgreementData="editAgreementData" :editFlg="editFlg"/>
    </transition>
    <div class="p-container--btn-right">
      <button class="c-btn c-btn--submit" @click="formControll">新しいルール</button>
    </div>
  </section>
</template>

<script>
import AgreementForm from '../components/AgreementForm.vue'
import AgreementList from '../components/AgreementList.vue'
import { AGREEMENT } from '../util'
export default {
  data () {
    return {
      showForm: false,
      editAgreementData: null,
      editFlg: false
    }
  },
  components: {
    AgreementForm,
    AgreementList
  },
  methods: {
    formControll () {
      this.showForm = !this.showForm
      this.editFlg = false
    },
    editAgreement (agree) {
      this.showForm = true
      this.editAgreementData = agree
      this.editFlg = true
    }
  },
  created () {
    this.$store.commit('page/setPage', AGREEMENT)
  }
}
</script>