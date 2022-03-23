<template>
  <section class="p-wrapper--profile">
    <div class="p-container--profile">
      <div class="p-container--user-title">
        <span class="c-title--user-name">{{userInfo.name}}</span>
      </div>
      <div class="p-container--image-profile">
        <img class="c-image c-image--profile" :src="userInfo.image">
      </div>
      <span class="material-icons c-icon--profile" @click="openProfEdit">more_horiz</span>
    </div>
  </section>
</template>

<script>
export default {
  data () {
    return {
      open_flg: false
    }
  },
  computed: {
    userInfo () {
      return this.$store.state.auth.user
    }
  },
  methods: {
    openProfEdit () {
      this.$emit('openProfEdit')
    },
    closeEditForm () {
      this.open_flg = false
    }
  }
}
</script>