<template>
  <div>
    <div class="p-container--agreements">
      <div>
        承認済みコーナー
        <div v-if="approvedAgreements.length">
          <div v-for="(agree, index) in approvedAgreements" :key="agree.id">
            <ApprovedAgreementDetail :agree="agree" :index="index" />
          </div>
        </div>
        <p v-else>まだ登録されていません。。</p>
      </div>
      <!-- 未承認のagree -->
      <div>
        未承認コーナー
        <ul v-if="suspendedAgreements.length">
          <li v-for="(agree, index) in suspendedAgreements" :key="agree.id">
            <SuspendedAgreementDetail :agree="agree" :index="index" @editAgreement="editAgreement" />
          </li>
        </ul>
        <p v-else>まだ登録されていません。</p>
      </div>
    </div>
  </div>
</template>

<script>
import SuspendedAgreementDetail from './SuspendedAgreementDetail.vue'
import ApprovedAgreementDetail from './ApprovedAgreementDetail.vue'
export default {
  components: {
    SuspendedAgreementDetail,
    ApprovedAgreementDetail
  },
  computed: {
    suspendedAgreements () {
      return this.$store.getters['agreements/notApprovedAgreements']
    },
    approvedAgreements () {
      return this.$store.getters['agreements/approvedAgreements']
    }
  },
  methods: {
    editAgreement (agree) {
      this.$emit('editAgreement', agree)
    }
  },
  created () {
    this.$store.dispatch('agreements/getAgreements')
  }
}
</script>