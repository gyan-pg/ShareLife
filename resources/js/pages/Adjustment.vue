<template>
  <div class="p-wrapper--adjustment">
    <AdjustmentTable />
  </div>
</template>

<script>
import AdjustmentTable from '../components/AdjustmentTable.vue'
import { ADJUSTMENT } from '../util'
export default {
  components: {
    AdjustmentTable
  },
  created () {
    this.$store.dispatch('payments/getPayments')
    this.$store.commit('page/setPage', ADJUSTMENT)
  }
}
</script>