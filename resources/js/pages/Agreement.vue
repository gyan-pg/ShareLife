<template>
  <div>
    <transition name="fade">
      <AgreementForm v-if="showForm" @closeForm="formControll" :editAgreementData="editAgreementData" :editFlg="editFlg"/>
    </transition>
    <AgreementList @editAgreement="editAgreement"/>
    <button class="c-btn" @click="formControll">show form</button>
  </div>
</template>

<script>
import AgreementForm from '../components/AgreementForm.vue'
import AgreementList from '../components/AgreementList.vue'
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
  }
}
</script>