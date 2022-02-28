<template>
  <transition name="fade">
    <p v-if="messages" class="c-text--flash">{{messages}}</p>
  </transition>
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

<style>
/* .fade-enter-to, .fade-leave-active{
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to{
  opacity: 0;
} */
</style>