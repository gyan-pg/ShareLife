<template>
  <p v-if="messages">{{messages}}</p>
</template>

<script>
export default {
  computed: {
    messages () {
      return this.$store.state.messages.message
    }
  }
}
</script>