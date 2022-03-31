<template>
  <header id="l-header">
    <nav class="c-nav">
      <div class="c-nav__left">
        <a v-if="!isLogin" class="c-logo" href="http://share-life-pg.com/top">Share Life</a>
        <router-link v-else to="/" class="c-logo">Share Life</router-link>
      </div>
      <!-- ゲスト時に表示 -->
      <div v-if="!isLogin" class="c-nav__right">
        <router-link to="/login" class="c-nav__menu">ログイン</router-link>
        <router-link to="/preRegister" class="c-nav__menu">ユーザー登録</router-link>
      </div>
      <!-- ログイン時に表示 -->
      <div v-else class="c-nav__right">
        <router-link to="/mypage" v-if="checkTeam"><div class="p-container--header-icon" data-text="カレンダー"><span class="material-icons c-icon--header" :class="[page === CALENDAR ? 'active' : '']">calendar_month</span></div></router-link>
        <router-link to="/adjustment" v-if="checkTeam"><div class="p-container--header-icon" data-text="わりかん！"><span class="material-icons c-icon--header" :class="[page === ADJUSTMENT ? 'active' : '']">payments</span></div></router-link>
        <router-link to="/agreement" v-if="checkTeam"><div class="p-container--header-icon" data-text="きめごと！"><span class="material-icons c-icon--header" :class="[page === AGREEMENT ? 'active' : '']">library_books</span></div></router-link>
        <div class="p-container--header-icon" data-text="ユーザー！" v-click-outside="closeProfile"><span class="material-icons c-icon--header" @click="showProfile">person</span>
          <transition name="fade">
            <Profile v-if="show_prof_flg" @openProfEdit="openProfileEdit"/>
          </transition>
        </div>

        <div class="p-container--header-icon" data-text="その他" @click="showSmallMenu" v-click-outside="closeSmallMenu">
          <span class="material-icons c-icon--header">logout</span>
          <!-- <i class="fa-solid fa-gear c-icon--header"></i> -->
          <transition name="fade">
            <div v-if="show_menu_flg" class="p-container--header-submenu">
              <span class="c-text--menu" @click="logout">ログアウト</span>
              <router-link to="/withdraw"><span class="c-text--menu">退会</span></router-link>
            </div>
          </transition>
        </div>
      </div>
    </nav>
    <transition name="fade">
      <ProfileEditForm v-if="show_edit_flg" @closeProfileEdit="closeProfileEdit"/>
    </transition>
  </header>
</template>

<script>
import ClickOutside from 'vue-click-outside'
import Profile from './Profile.vue'
import ProfileEditForm from './ProfileEditForm.vue'
import { ADJUSTMENT, CALENDAR, AGREEMENT} from '../util'
export default {
  data () {
    return {
      show_menu_flg: false,
      show_prof_flg: false,
      show_edit_flg: false,
      ADJUSTMENT,
      CALENDAR,
      AGREEMENT
    }
  },
  components: {
    Profile,
    ProfileEditForm
  },
  computed: {
    isLogin () {
      return this.$store.getters['auth/check']
    },
    userEmail () {
      return this.$store.state.auth.user.email
    },
    checkTeam () {
      return this.$store.getters['auth/checkTeam']
    },
    page () {
      return this.$store.state.page.page
    }
  },
  methods: {
    async logout () {
      const response = await this.$store.dispatch('auth/logout')
      this.$store.commit('auth/setTeam', null)
      this.$store.commit('auth/setOwner', null)
      this.$store.commit('auth/setTeamMember', null)
      this.$router.push('/login')
    },
    showSmallMenu () {
      this.show_menu_flg = !this.show_menu_flg
    },
    closeSmallMenu () {
      this.show_menu_flg = false
    },
    showProfile () {
      this.show_prof_flg = !this.show_prof_flg
    },
    closeProfile () {
      this.show_prof_flg = false
    },
    openProfileEdit () {
      this.show_edit_flg = !this.show_edit_flg
      this.show_prof_flg = false
    },
    closeProfileEdit () {
      this.show_edit_flg = false
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