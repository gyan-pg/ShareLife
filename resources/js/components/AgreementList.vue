<template>
  <div>
    <!-- 承認済 -->
    <h2 class="c-title c-title--pending">我が家のルール！</h2>
    <div class="p-container--agreements-row">
      <h3 class="c-title--section-agreements">承認済</h3>
      <ul class="p-container--agreements-content" v-if="approvedAgreements.length">
        <li class="p-container--agreements-item" v-for="(agree, index) in approvedAgreements" :key="agree.id">
          <ApprovedAgreementDetail :agree="agree" :index="index" />
        </li>
      </ul>
      <div  v-else class="p-container--agreements-content">
        <p class="c-text--agreement">登録されていません。</p>
      </div>
    </div>

    <div class="p-container--agreements-row-red u-mb-m">
      <!-- 審議中 -->
      <h3 class="c-title--section-agreements-red">審議中</h3>
      <ul class="p-container--agreements-content" v-if="suspendedAgreements.length">
        <li class="p-container--agreements-item" v-for="(agree, index) in suspendedAgreements" :key="agree.id">
          <SuspendedAgreementDetail :agree="agree" :index="index" @editAgreement="editAgreement" />
        </li>
      </ul>
      <div  v-else class="p-container--agreements-content">
        <p>登録されていません。</p>
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