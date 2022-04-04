<template>
  <section class="p-wrapper--profile" v-click-outside="closeProfile">
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
import ClickOutside from 'vue-click-outside'
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
    closeProfile () {
      this.$emit('closeProfile')
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