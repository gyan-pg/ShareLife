<template>
  <header id="l-header">
    <nav class="c-nav">
      <div class="c-nav__left">
        <router-link to="/" class="c-logo">Agree</router-link>
      </div>
      <!-- ゲスト時に表示 -->
      <div v-if="!isLogin" class="c-nav__right">
        <router-link to="/login" class="c-nav__menu">ログイン</router-link>
        <router-link to="/register" class="c-nav__menu">ユーザー登録</router-link>
      </div>
      <!-- ログイン時に表示 -->
      <div v-else class="c-nav__right">
        <div class="p-container--header-icon" data-text="わりかん！"><i class="fa-solid fa-money-check-dollar c-icon--header"></i></div>
        <router-link to="/agreement"><div class="p-container--header-icon" data-text="きめごと！"><i class="fa-solid fa-book c-icon--header"></i></div></router-link>
        <router-link to="/mypage"><div class="p-container--header-icon" data-text="カレンダー"><i class="fa-solid fa-calendar-days c-icon--header"></i></div></router-link>
        <div class="p-container--header-icon" data-text="ユーザー！"><i class="fa-solid fa-user c-icon--header"></i></div>
        <div class="p-container--header-icon" data-text="せってい！" @click="showSmallMenu" v-click-outside="closeSmallMenu">
          <i class="fa-solid fa-gear c-icon--header"></i>
          <transition name="fade">
            <div v-if="show_flg" class="p-container--header-submenu">
              <span class="c-text--menu" @click="logout">ログアウト</span>
              <span class="c-text--menu" @click="logout">退会</span>
            </div>
          </transition>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
import ClickOutside from 'vue-click-outside'
export default {
  data () {
    return {
      show_flg: false
    }
  },
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
    },
    showSmallMenu () {
      this.show_flg = !this.show_flg
    },
    closeSmallMenu () {
      this.show_flg = false
    }
  },
  // directivesオプションでローカルディレクティブに登録することで、
  // ライブラリの機能が使用できるようになる。
  // importとdirectivesに登録する名前はClickOutsideとしないと動かない。
  directives: {
    ClickOutside,
  }
}
</script>