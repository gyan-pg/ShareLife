<template>
  <div>
    <AgreementForm v-if="showForm" />
    <AgreementList />
    <button class="c-btn" @click="appear">show form</button>
  </div>
</template>

<script>
import AgreementForm from '../components/AgreementForm.vue'
import AgreementList from '../components/AgreementList.vue'
export default {
  data () {
    return {
      showForm: false
    }
  },
  components: {
    AgreementForm,
    AgreementList
  },
  methods: {
    appear () {
      this.showForm = !this.showForm
    }
  }
}
</script>