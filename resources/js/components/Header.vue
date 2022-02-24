<template>
  <header id="l-header">
    <nav class="c-nav">
      <div class="c-nav__left">
        <router-link to="/" class="c-logo">Agree</router-link>
      </div>
      <div v-if="!isLogin" class="c-nav__right">
        <router-link to="/login" class="c-nav__menu">ログイン</router-link>
        <router-link to="/register" class="c-nav__menu">ユーザー登録</router-link>
      </div>
      <div v-else class="c-nav__right">
        <button class="c-nav__menu" @click="logout">ログアウト</button>
      </div>
    </nav>
  </header>
</template>

<script>
export default {
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    }
  },
  methods: {
    async logout () {
      const response = await this.$store.dispatch('auth/logout')
      this.$store.commit('auth/setTeam', null)
      this.$store.commit('auth/setOwner', null)
      this.$store.commit('auth/setPartner', null)
      this.$router.push('/login')
    }
  }
}
</script>