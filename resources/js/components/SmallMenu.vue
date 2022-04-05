<template>
<section class="p-wrapper--submenu">
  <div class="p-container--header-submenu">
    <a class="c-text--menu" @click="logout">ログアウト</a>
    <router-link to="/withdraw"><span class="c-text--menu">退会</span></router-link>
    <a class="c-text--menu" @click="openPasswordEdit">パスワード変更</a>
  </div>
  <div class="p-outside" @click="closeSmallMenu"></div>
</section>
</template>

<script>
export default {
  methods: {
    logout () {
      this.$emit('logout')
    },
    openPasswordEdit () {
      this.$emit('openPasswordEdit')
    },
    closeSmallMenu () {
      this.$emit('closeSmallMenu')
    }
  }
}
</script>