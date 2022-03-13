<template>
  <section class="p-wrapper--profile">
    <div class="p-container--profile">
      <p>{{userInfo.name}}</p>
      <p>{{userInfo.email}}</p>
      <div class="p-container--image-profile">
        <img class="c-image c-image--profile" :src="userInfo.image">
      </div>
      <i class="fa-solid fa-ellipsis" @click="openProfEdit"></i>
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