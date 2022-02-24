<template>
  <div>
    <ul v-if="agreements">
      <li v-for="agree in agreements" :key="agree.id">{{ agree.title }}</li>
    </ul>
    <p v-else>まだ登録されていません。</p>
    <button @click="getAgreements">test</button>
  </div>
</template>

<script>
export default {
  data () {
    return {
      agreements: null
    }
  },
  methods: {
    async getAgreements () {
      const response = await axios.get(`api/agreement/${this.$store.state.auth.team.id}/list`)
      console.log(response.data)
      this.agreements = response.data
    }
  }
}
</script>