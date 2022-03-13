<template>
  <div class="p-wrapper--adjustment">
    <AdjustmentForm />
    <AdjustmentTable />
  </div>
</template>

<script>
import AdjustmentForm from '../components/AdjustmentForm.vue'
import AdjustmentTable from '../components/AdjustmentTable.vue'
export default {
  components: {
    AdjustmentForm,
    AdjustmentTable
  },
  created () {
    this.$store.dispatch('payments/getPayments')
  }
}
</script>