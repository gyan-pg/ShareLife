<template>
  <header id="l-header">
    <nav class="c-nav">
      <div class="c-nav__left">
        <a v-if="!isLogin" class="c-logo" :href="home_url">Share Life</a>
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
        <div class="p-container--header-icon" data-text="ユーザー！"><span class="material-icons c-icon--header" @click.stop="showProfile">person</span></div>
        <div class="p-container--header-icon" data-text="その他"><span class="material-icons c-icon--header" @click.stop="showSmallMenu">logout</span></div>
      </div>
    </nav>
    <transition name="fade">
      <ProfileEditForm v-if="show_edit_flg" @closeProfileEdit="closeProfileEdit"/>
    </transition>
    <transition name="fade">
      <PasswordEditForm v-if="show_pass_flg" @closePassEdit="closePassEdit"/>
    </transition>
    <transition name="fade">
      <Profile v-if="show_prof_flg" @openProfEdit="openProfileEdit" @closeProfile="closeProfile"/>
    </transition>
    <transition name="fade">
      <SmallMenu v-if="show_menu_flg" @showSmallMenu="showSmallMenu" @logout="logout" @openPasswordEdit="openPasswordEdit" @closeSmallMenu="closeSmallMenu"/>
    </transition>
  </header>
</template>

<script>
import Profile from './Profile.vue'
import ProfileEditForm from './ProfileEditForm.vue'
import PasswordEditForm from './PasswordEditForm.vue'
import SmallMenu from './SmallMenu.vue'
import { ADJUSTMENT, CALENDAR, AGREEMENT} from '../util'
export default {
  data () {
    return {
      show_menu_flg: false,
      show_prof_flg: false,
      show_edit_flg: false,
      show_pass_flg: false,
      ADJUSTMENT,
      CALENDAR,
      AGREEMENT,
      home_url: null,
      // スマホとPCでclick outsideの動作順が違うため、
      // 動作判定用の変数を準備
      closeOutSide: false
    }
  },
  components: {
    Profile,
    ProfileEditForm,
    PasswordEditForm,
    SmallMenu
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
      this.show_menu_flg = false
      const response = await this.$store.dispatch('auth/logout')
      this.$store.commit('auth/setTeam', null)
      this.$store.commit('auth/setOwner', null)
      this.$store.commit('auth/setTeamMember', null)
      this.$router.push('/login')
    },
    showSmallMenu () {
      if (!this.closeOutSide) this.show_menu_flg = !this.show_menu_flg
      this.show_prof_flg = false
    },
    closeSmallMenu () {
      this.show_menu_flg = false
      this.closeOutSide = true
      setTimeout(() => {
        this.closeOutSide = false
      }, 200);
    },
    showProfile () {
      if (!this.closeOutSide) this.show_prof_flg = !this.show_prof_flg
      this.show_menu_flg = false
    },
    closeProfile () {
      this.show_prof_flg = false
      this.closeOutSide = true
      setTimeout(() => {
        this.closeOutSide = false
      }, 200);
    },
    openProfileEdit () {
      this.show_edit_flg = !this.show_edit_flg
      this.show_prof_flg = false
    },
    closeProfileEdit () {
      this.show_edit_flg = false
    },
    openPasswordEdit () {
      this.show_pass_flg = !this.show_pass_flg
      this.show_menu_flg = false
    },
    closePassEdit () {
      this.show_pass_flg = false
    }
  },
  created () {
    // ロゴのURLを取得
    this.home_url = window.location.protocol + '//' + window.location.host
  }
}
</script>