<template>
  <div>
    <Header />
    <main id="l-main">
      <Messages />
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </main>
    <Footer />
  </div>
</template>

<script>
// components
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import Messages from './components/Messages.vue'
// constants
import { INTERNAL_SERVER_ERROR, UNAUTHORIZED } from './util'

export default {
  components: {
    Header,
    Footer,
    Messages,
  },
  computed: {
    errorCode () {
      return this.$store.state.error.code
    }
  },
  watch: {
    errorCode: { // computedのerrorCodeの監視。
      async handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        } else if (val === UNAUTHORIZED) {
          // トークンをリフレッシュ
          await axios.get('/api/reflesh-token')
          // フロント側もユーザーをログアウト扱いにする
          this.$store.commit('auth/setUser', null)
          this.$store.commit('auth/setTeam', null)
          this.$store.commit('auth/setOwner', null)
          this.$store.commit('auth/setTeamMember', null)
          this.$router.push('/login')
        }
      },
      immediate: true
    }
  },
  $route () {
    this.$store.commit('error/setCode', null)
  }
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .25s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>