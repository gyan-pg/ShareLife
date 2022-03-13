<template>
  <transition name="fade">
    <p v-if="messages" class="c-text--flash">{{messages}}</p>
    <p v-if="errorMessages" class="c-text--flash-error">{{ errorMessages }}</p>
  </transition>
</template>

<script>
export default {
  computed: {
    messages () {
      return this.$store.state.messages.message
    },
    errorMessages () {
      return this.$store.state.messages.errorMessage
    }
  }
}
</script>

<style>
/* .fade-enter-to, .fade-leave-active{
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to{
  opacity: 0;
} */
</style>